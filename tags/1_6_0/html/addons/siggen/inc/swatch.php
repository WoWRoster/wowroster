<?php
/*
* $Date: 2005/12/31 04:14:34 $
* $Revision: 1.2 $
* $Author: zanix $
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
<title>Color Swatch</title>

<style type="text/css">
<!--
body {
	background-color: #404040;
	color: #fff;
}

td {
	border: solid 1px #333; 
}

.over {
	border-color: white; 
}

.out {
	border-color: #333333; 
}

img {
	border: 0;
}

//-->
</style>
</head>

<body>

<script type="text/javascript">
<!--
	var r = 0, g = 0, b = 0;
	var numberList = new Array(6);
	numberList[0] = "00";
	numberList[1] = "33";
	numberList[2] = "66";
	numberList[3] = "99";
	numberList[4] = "CC";
	numberList[5] = "FF";
	document.writeln('<table cellspacing="0" cellpadding="0" border="0">');
	for(r = 0; r < 6; r++)
	{
		document.writeln('<tr>');
		for(g = 0; g < 6; g++)
		{
			for(b = 0; b < 6; b++)
			{
				color = String(numberList[r]) + String(numberList[g]) + String(numberList[b]);
				document.write('<td bgcolor="#' + color + '" onmouseover="this.className=\'over\'" onmouseout="this.className=\'out\'">');
				document.write('<a href="javascript:cell(\'' + color + '\');"><img src="./spacer.gif" width="12" height="10" alt="#' + color + '" title="#' + color + '" \/><\/a>');
				document.writeln('<\/td>');
			}
		}
		document.writeln('<\/tr>');
	}
	document.writeln('<\/table>');

function cell(color)
{
	opener.document.forms['<?php echo htmlspecialchars(addslashes($_GET['form'])); ?>'].<?php echo htmlspecialchars(addslashes($_GET['name'])); ?>.value = color;
}
//-->
</script>

</body>
</html>