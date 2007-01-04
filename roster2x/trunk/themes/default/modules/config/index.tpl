{include file='header.tpl'}

<h1>Settings overview for {$file}</h1>
{$status}
<form method="post" action="">
	<input type="text" name="file" id="file" />
	<input type="submit" value="Submit" />
</form>
<form method="post" action="">
	<input type="hidden" name="save" id="save" value="1" />
	<input type="submit" value="Update personal config" />
</form>
<table border="1">
	<thead>
		<tr>
			<th>Setting name</th>
			<th>Meta info string</th>
			<th>Default setting</th>
			<th>Current setting</th>
			<th>Comment</th>
		</tr>
	</thead>
	<tbody>
{foreach from=$config key=name item=row}
		<tr>
			<td>{$name}</td>
			<td>{$row.metaraw}</td>
			<td>{phpdata data=$row.default}</td>
			<td>{if $row.meta.type eq 'password'}<i>Password hidden</i>{else}{phpdata data=$row.value}{/if}</td>
			<td>{$row.comment}</td>
		</tr>
{foreachelse}
		<tr>
			<td colspan="4">No config data in this file</td>
		</tr>
{/foreach}
	</tbody>
</table>

{include file='footer.tpl'}