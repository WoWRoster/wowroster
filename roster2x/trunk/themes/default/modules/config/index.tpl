{include file='header.tpl' title="Config - $file"}

<h1>Settings overview for {$file}</h1>
{foreach from=$status item=line}
	{$line}<br />
{foreachelse}
{/foreach}
<form method="post" action="">
	<input type="text" name="file" id="file" value="{$file}" />
	<input type="submit" value="Submit" />
</form>
<form method="post" action="">
	<input type="hidden" name="file" id="file" value="{$file}" />
	<input type="hidden" name="save" id="save" value="1" />
	<input type="reset" value="Reset fields" />
	<input type="submit" value="Write config to file" />
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
				<td>{$row.metaraw|replace:' ':"<br />\n"}</td>
				<td>{phpdata data=$row.default}</td>
				<td>
	{if $row.meta.type eq 'text'}
					<input name="config_{$name}" id="config_{$name}" type="text" value="{$row.value}" maxlength="{$row.meta.length|default:'30'}" size="{$row.meta.size|default:'30'}" />
	{elseif $row.meta.type eq 'password'}
					<input name="config_{$name}[]" id="config_{$name}_0" type="password" value="" maxlength="{$row.meta.length|default:'30'}" size="{$row.meta.size|default:'30'}" />
					<input name="config_{$name}[]" id="config_{$name}_1" type="password" value="" maxlength="{$row.meta.length|default:'30'}" size="{$row.meta.size|default:'30'}" />
	{else}
					{phpdata data=$row.value}
	{/if}
				</td>
				<td>{$row.comment}</td>
			</tr>
{foreachelse}
			<tr>
				<td colspan="4">No config data in this file</td>
			</tr>
{/foreach}
		</tbody>
	</table>
</form>
{include file='footer.tpl'}
