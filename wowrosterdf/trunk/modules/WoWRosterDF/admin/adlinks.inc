<?php
/*********************************************
  CPG-NUKE: Advanced Content Management System
  ********************************************
  Copyright (c) 2004 by CPG-Nuke Dev Team
  http://www.cpgnuke.com

  CPG-Nuke is released under the terms and conditions
  of the GNU GPL version 2 or any later version


  $Revision:  1.0 $
  $Author:  Merllin $
  $Date: 2005/03/31 04:02:52 $

***********************************************************************/
if (!defined('CPG_NUKE')) { exit; }

$menuitems['_AMENU5'][$module]['URL'] = adminlink($module);
$menuitems['_AMENU5'][$module]['IMG'] = 'wowrosterdf';
$menuitems['_AMENU5'][$module]['MOD'] = $module;
$menuitems['_AMENU5'][$module]['SUB']['Roster Config'] = adminlink($module);
$menuitems['_AMENU5'][$module]['SUB']['Upload Profile'] = getlink($module.'&amp;file=update');
$menuitems['_AMENU5'][$module]['SUB']['Siggen Config'] = getlink($module.'&amp;file=addon&amp;roster_addon_name=siggen');
$menuitems['_AMENU5'][$module]['SUB']['EQDKP Sync'] = adminlink($module.'&amp;file=dkpsync');
