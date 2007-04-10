<?php 
$versions['versionDate']['thotsearch'] = '$Date: 2005/12/30 20:40:53 $'; 
$versions['versionRev']['thotsearch'] = '$Revision: 1.5 $'; 
$versions['versionAuthor']['thotsearch'] = '$Author: mordon $';
?>
<!-- BEGIN THOT SEARCH BOX -->
<table bgcolor="#292929" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td bgcolor="#000000" colspan="4" class="rankbordertop"><span class="rankbordertopleft"></span>
      <span class="rankbordertopright"></span></td></tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft">
    <td valign="middle">
      <p align="center">	
        <img src='img/Thottbot.gif' alt='Thottbot' width=158 height=51><br>
        <br>
      </p>
      <form method="post" action="http://www.thottbot.com/">
        <p align="center">
          <span class="gensmall"><?php print $wordings[$roster_lang]['search'] ?>: 
          <input type=text name="s" size=45>&nbsp;&nbsp;
          <input class="liteoption" type=submit value="Go" onclick="win=window.open('','myWin',''); this.form.target='myWin'"></span>
        </p>
      </form></td>
    <td valign="middle"></td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft"></td>
    <td align="center" colspan="2"><div class="membersRow"></div></td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td bgcolor="#000000" colspan="4" class="rankborderbot"><span class="rankborderbotleft"></span>
      <span class="rankborderbotright"></span></td>
  </tr>
</table>
<!-- END THOT SEARCH BOX -->