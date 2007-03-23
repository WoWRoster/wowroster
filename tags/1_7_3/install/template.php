<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

/**
* NOTICE: This file was not written by the WoWRoster Dev Team
* It was originally written for the EQdkp project and used here
*
* It has been HEAVILY modified and adapted to fit WoWRoster's needs.
*
* The EQdkp Group's original notice appears below
*/

/******************************
 * EQdkp
 * Copyright 2002-2003
 * Licensed under the GNU GPL.  See COPYING for full terms.
 * ------------------
 * class_template.php
 * Began: Thu December 19 2002
 *
 * $Id$
 *
 ******************************/

/**
* NOTICE: This class was not written for the EQdkp project.
* It was originally written for the phpBB project and used here
* under the GNU GPL.
*
* It has been modified and adapted to fit the EQdkp project's needs.
*
* The phpBB Group's original notice appears below
*/

/***************************************************************************
 *                              template.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/*
	Template class.

	Nathan Codding - Original version design and implementation
	Crimsonbane - Initial caching proposal and work
	psoTFX - Completion of file caching, decompilation routines and implementation of
	conditionals/keywords and associated changes

	The interface was inspired by PHPLib templates,  and the template file (formats are
	quite similar)

	The keyword/conditional implementation is currently based on sections of code from
	the Smarty templating engine (c) 2001 ispi of Lincoln, Inc. which is released
	(on its own and in whole) under the LGPL. Section 3 of the LGPL states that any code
	derived from an LGPL application may be relicenced under the GPL, this applies
	to this source
*/

// Changes for 2.2:
//
// * Allow use of Smarty plug-ins?
// * Allow use of DB for storage of compiled templates
// * Reduce number of methods and variables

class Template_Wrap
{
	// variable that holds all the data we'll be substituting into
	// the compiled templates. Takes form:
	// --> $this->_tpldata[block.][iteration#][child.][iteration#][child2.][iteration#][variablename] == value
	// if it's a root-level variable, it'll be like this:
	// --> $this->_tpldata[.][0][varname] == value
	var $_tpldata = array();

    var $error_message   = array();           // Array of errors      @var $error_message
    var $install_message = array();           // Array of messages    @var $install_message
    var $header_inc      = false;             // Printed header?      @var $header_inc
    var $template_file   = '';                // Template filename    @var $template_file

    var $MSG_TITLE       = '';
    var $MSG_TEXT        = '';

    function template_wrap($template_file,$header,$footer)
    {
        $this->assign_vars(array(
            'MSG_TITLE' => '',
            'MSG_TEXT'  => '')
        );

        $this->set_filename('body',$template_file);
        $this->set_filename('header',$header);
        $this->set_filename('footer',$footer);
    }

	/**
	 * Sets the template filename for handle. $filename
	 * should be a hash of handle => filename pairs.
	 */
	function set_filename($handle,$filename)
	{
		global $roster_root_path;

		if (empty($filename))
		{
			trigger_error("Template error - Empty filename specified for $handle", E_USER_ERROR);
		}

		$filename = $roster_root_path.'install/templates/'.$filename;

		$this->template_file[$handle] = $filename;

		return true;
	}

	function message_die($text = '', $title = '')
	{
        $this->set_filename('body','install_message.html');

        $this->assign_vars(array(
            'MSG_TITLE' => ( $title != '' ) ? $title : '&nbsp;',
            'MSG_TEXT'  => ( $text  != '' ) ? $text  : '&nbsp;')
        );

        if ( !$this->header_inc )
        {
        	$this->page_header();
        }

	    $this->page_tail();
	}

	function error_append($error)
	{
	    $this->error_message[ (sizeof($this->error_message) + 1) ] = $error;
	}

	function message_append($message)
	{
	    $this->install_message[ sizeof($this->install_message) + 1 ] = $message;
	}

	function assign_vars($vararray)
	{
		foreach ($vararray as $key => $val)
		{
			$this->_tpldata[$key] = $val;
		}

		return true;
	}

	function assign_var($varname, $varval)
	{
		$this->_tpldata[$varname] = $varval;

		return true;
	}

	function page_header()
	{
	    global $STEP;

	    $this->header_inc = true;

        $this->assign_vars(array(
            'INSTALL_STEP' => $STEP)
        );

		$_tpldata = $this->_tpldata;
		include_once( $this->template_file['header'] );

	}

	function page_tail()
	{
	    global $DEFAULTS, $wowdb;

	    $this->assign_var('S_SHOW_BUTTON', true);

	    if ( sizeof($this->install_message) > 0 )
	    {
	        $this->message_out(false);
	    }

	    if ( sizeof($this->error_message) > 0 )
	    {
	        $this->assign_var('S_SHOW_BUTTON', false);
	        $this->error_message[0] = '<span style="font-weight: bold; font-size: 14px;" class="negative">NOTICE</span>';
	        $this->error_out(false);
	    }

	    $this->assign_var('ROSTER_VERSION', $DEFAULTS['version']);

	    if ( is_object($wowdb) )
	    {
	        $wowdb->closeDb();
	    }

	    $this->display('body');

		$_tpldata = $this->_tpldata;
		include_once( $this->template_file['footer'] );

	    exit;
	}

	function assign_block_vars($blockname, $vararray)
	{
		$this->_tpldata[$blockname][] = $vararray;

		return true;
	}

    function message_out($die = false)
    {
        sort($this->install_message);
        reset($this->install_message);

        $install_message = implode('<br /><br />', $this->install_message);

        if ( $die )
        {
            $this->message_die($install_message, 'Installation ' . (( sizeof($this->install_message) == 1 ) ? 'Note' : 'Notes'));
        }
        else
        {
            $this->assign_vars(array(
                'MSG_TITLE' => 'Installation ' . (( sizeof($this->install_message) == 1 ) ? 'Note' : 'Notes'),
                'MSG_TEXT'  => $install_message)
            );
        }
    }

    function error_out($die = false)
    {
        sort($this->error_message);
        reset($this->error_message);

        $error_message = implode('<br /><br />', $this->error_message);

        if ( $die )
        {
            $this->message_die($error_message, 'Installation ' . (( sizeof($this->error_message) == 1 ) ? 'Error' : 'Errors'));
        }
        else
        {
            $this->assign_vars(array(
                'MSG_TITLE' => 'Installation ' . (( sizeof($this->error_message) == 1 ) ? 'Error' : 'Errors'),
                'MSG_TEXT'  => $error_message)
            );
        }
    }

	function display($handle)
	{
		$_tpldata = $this->_tpldata;
		include_once( $this->template_file[$handle] );

		return true;
	}

}

?>