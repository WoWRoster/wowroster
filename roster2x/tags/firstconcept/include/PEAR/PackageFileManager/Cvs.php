<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: Package File Manager                                           |
// +------------------------------------------------------------------------+
// | Copyright (c) 2003-2004 Gregory Beaver                                 |
// | Email         cellog@phpdoc.org                                        |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
// | Portions of this code based on phpDocumentor                           |
// | Web           http://www.phpdoc.org                                    |
// | Mirror        http://phpdocu.sourceforge.net/                          |
// +------------------------------------------------------------------------+
// $Id$
//
/**
 * @package PEAR_PackageFileManager
 */
/**
 * The PEAR_PackageFileManager_File class
 */
require_once 'PEAR/PackageFileManager/File.php';

/**
 * Generate a file list from a CVS checkout
 *
 * Note that this will <b>NOT</b> work on a
 * repository, only on a checked out CVS module
 * @package PEAR_PackageFileManager
 */
class PEAR_PackageFileManager_CVS extends PEAR_PackageFileManager_File {
    /**
     * List of CVS-specific files that may exist in CVS but should be
     * ignored when building the package's file list.
     * @var array
     * @access private
     */
    var $_cvsIgnore = array('.cvsignore');

    /**
     * Return a list of all files in the CVS repository
     *
     * This function is like {@link parent::dirList()} except
     * that instead of retrieving a regular filelist, it first
     * retrieves a listing of all the CVS/Entries files in
     * $directory and all of the subdirectories.  Then, it
     * reads the Entries file, and creates a listing of files
     * that are a part of the CVS repository.  No check is
     * made to see if they have been modified, but newly
     * added or removed files are ignored.
     * @return array list of files in a directory
     * @param string $directory full path to the directory you want the list of
     * @uses _recurDirList()
     * @uses _readCVSEntries()
     */
    function dirList($directory)
    {
        static $in_recursion = false;
        if (!$in_recursion) {
            // include only CVS/Entries files
            $this->_setupIgnore(array('*/CVS/Entries'), 0);
            $this->_setupIgnore(array(), 1);
            $in_recursion = true;
            $entries = parent::dirList($directory);
            $in_recursion = false;
        } else {
            return parent::dirList($directory);
        }
        if (!$entries || !is_array($entries)) {
            return PEAR_PackageFileManager::raiseError(PEAR_PACKAGEFILEMANAGER_NOCVSENTRIES, $directory);
        }
        return $this->_readCVSEntries($entries);
    }
    
    /**
     * Iterate over the CVS Entries files, and retrieve every
     * file in the repository
     * @uses _getCVSEntries()
     * @uses _isCVSFile()
     * @param array array of full paths to CVS/Entries files
     * @access private
     */
    function _readCVSEntries($entries)
    {
        $ret = array();
        $ignore = array_merge((array) $this->_options['ignore'], $this->_cvsIgnore);
        // implicitly ignore packagefile
        $ignore[] = $this->_options['packagefile'];
        $include = $this->_options['include'];
        $this->ignore = array(false, false);
        $this->_setupIgnore($ignore, 1);
        $this->_setupIgnore($include, 0);
        foreach($entries as $cvsentry) {
            $directory = @dirname(@dirname($cvsentry));
            if (!$directory) {
                continue;
            }
            $d = $this->_getCVSEntries($cvsentry);
            if (!is_array($d)) {
                continue;
            }
            foreach($d as $entry) {
                if ($ignore) {
                	if ($this->_checkIgnore($this->_getCVSFileName($entry),
                          $directory . '/' . $this->_getCVSFileName($entry), 1)) {
    //                    print 'Ignoring '.$file."<br>\n";
                        continue;
                    }
                }
                if ($include) {
                    if ($this->_checkIgnore($this->_getCVSFileName($entry),
                          $directory . '/' . $this->_getCVSFileName($entry), 0)) {
    //                    print 'Including '.$file."<br\n";
                        continue;
                    }
                }
                if ($this->_isCVSFile($entry)) {
                    $ret[] = $directory . '/' . $this->_getCVSFileName($entry);
                }
            }
        }
        return $ret;
    }
    
    /**
     * Retrieve the filename from an entry
     *
     * This method assumes that the entry is a file,
     * use _isCVSFile() to verify before calling
     * @param string a line in a CVS/Entries file
     * @return string the filename (no path information)
     * @access private
     */
    function _getCVSFileName($cvsentry)
    {
        $stuff = explode('/', $cvsentry);
        array_shift($stuff);
        return array_shift($stuff);
    }
    
    /**
     * Retrieve the entries in a CVS/Entries file
     * @return array each line of the entries file, output of file()
     * @uses function file()
     * @param string full path to a CVS/Entries file
     * @access private
     */
    function _getCVSEntries($cvsentryfilename)
    {
        $cvsfile = @file($cvsentryfilename);
        if (is_array($cvsfile)) {
            return $cvsfile;
        } else {
            return false;
        }
    }
    
    /**
     * Check whether an entry is a file or a directory
     * @return boolean
     * @param string a line in a CVS/Entries file
     * @access private
     */
    function _isCVSFile($cvsentry)
    {
        // make sure we ignore entries that have either been removed or added, but not committed yet
        return $cvsentry{0} == '/' && !strpos($cvsentry, 'dummy timestamp');
    }
}
?>
