
<!-- Begin guild list body -->
<table id="guildlist" cellspacing="0">
	<tr>
		<th colspan="3">List of Guilds in Roster</th>
	</tr>

{section loop=$guilds_data name=guilds}
	<tr>
		<td><img class="faction_image" style="cursor:help;" onmouseover="overlib(guild_{$smarty.section.guilds.iteration});" onmouseout="return nd();" src="{$roster_conf.imagepath}/badge_{$guilds_data[guilds].faction_en}.gif" alt="{$guilds_data[guilds].faction}" /></td>
		<td><a href="?p=members&amp;guild={ $guilds_data[guilds].guild_id }">{$guilds_data[guilds].guild_name}</a></td>
		<td>{$guilds_data[guilds].realm}</td>
	</tr>
{/section}
</table>


<!-- Color Stripe rows -->
{literal}
<script type="text/javascript">
<!--
	window.onload=stripe('guildlist');
//-->
</script>
{/literal}
<!-- End guild list body -->

