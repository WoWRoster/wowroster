
<!-- Begin Members list body -->
<h2>Members of { $guildname } on { $realmname }</h2>
<br>
<table id="memberslist" cellspacing="0">
	<tr>
		<th>Name</th>
		<th>Level</th>
		<th>Class</th>
		<th>Honor rank</th>
		<th>Guild title</th>
		<th>Guild rank</th>
	</tr>

{section loop=$data name=member}
	<tr>
		<td>{if $data[member].realm neq ''}<a href="?p=char&member={ $data[member].member_id }">{$data[member].name}</a>{else}{$data[member].name}{/if}</td>
		<td>{ $data[member].level }</td>
		<td>{ $data[member].class }</td>
		<td>{ $data[member].honor_current_name }</td>
		<td>{ $data[member].guild_title }</td>
		<td>{ $data[member].guild_rank }</td>
	</tr>
{/section}

</table>

<!-- Color Stripe rows -->
{literal}
<script type="text/javascript">
<!--
	window.onload=stripe('memberslist');
//-->
</script>
{/literal}
<!-- End Members list body -->

