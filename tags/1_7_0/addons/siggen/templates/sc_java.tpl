<?php
/*******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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
 *******************************/
?>

<!-- Begin Java Link -->
<script type="text/javascript" language="JavaScript">
<!--
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	var initialtab=[1, 't1'];
	var savetabname='<?php print (!empty($_GET['roster_addon_name']) ? '?'.$_GET['roster_addon_name'] : '' ); ?>';
	window.onload=do_onload;
	window.onunload=savetabstate;
//-->
</script>
<script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/addons/siggen/inc/color/color_functions.js"></script>
<script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/addons/siggen/inc/color/js_color_picker_v2.js"></script>
<!-- End Java Link -->