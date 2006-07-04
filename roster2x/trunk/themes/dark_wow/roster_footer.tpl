

<!-- Begin Roster Footer -->
</div>
<!-- END Roster Main Container -->


<!-- Begin Credits -->
<div id="roster_footer">
	WoW Roster v{$roster_conf.roster_version}<br /><br />
	{$roster_wordings[$roster_conf.lang].credit_page_bottom|nl2br}
	<div style="margin-top: 10px;">
		<a href="http://validator.w3.org/check?uri=referer" target="_new"><img style="border:0;width:80px;height:15px" src="{$roster_conf.imagepath}/validxhtml.gif" alt="Valid XHTML 1.0 Transitional" /></a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_new"><img style="border:0;width:80px;height:15px" src="{$roster_conf.imagepath}/validcss.gif" alt="Valid CSS!" /></a>
		<a href="http://smarty.php.net" target="_new"><img style="border:0;width:80px;height:15px" src="{$roster_conf.imagepath}/smarty_small.png" alt="Utilizes the power of the Schwarts!" /></a>
		<a href="http://pear.php.net" target="_new"><img style="border:0;width:80px;height:15px" src="{$roster_conf.imagepath}/pear_small.png" alt="Powered by PEAR" /></a>
	</div>
</div>


{if $tooltip_strings neq ''}
<!-- Tooltip strings in javascript variables -->
{$tooltip_strings}
{/if}

{if $sql_strings neq ''}
<!-- SQL queries
{$sql_strings}
 -->
{/if}

<!-- Set opacity -->
<script type="text/javascript">
<!--
	setOpacity( 'overDiv',8.5 );
//-->
</script>
<!-- End opacity -->

</body>
</html>