<?php
/* 
* $Date: 2006/01/11 19:45:26 $ 
* $Revision: 1.9 $ 
*/ 

if ( !defined( 'CPG_NUKE' ) ) {
	exit;
}

$header_title .= $wordings[$roster_conf['roster_lang']]['talenttree']; 
/*echo "<script type=\"text/javascript\" language=\"JavaScript\">


<center><script>
var mt_width = 504;  
var mt_css   = './modules/wowroster/addons/talenttree/defualt.css';
</script><br />
<br />
<script src='http://www.wowhead.com/talent/include.js'></script>";
*/
$output = "	<script>
						var mt_width = 600;
						var mt_css   = '".BASEDIR.$module_name."/addons/talenttree/default.css';
					</script>
					
					<script src=\"http://www.wowhead.com/talent/include.js\"></script>";

	echo $output;

?>





