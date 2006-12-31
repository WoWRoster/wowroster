<!--$Id$-->
<html>
<head>
<title>Settings overview for {$file}</title>
</head>
<body>
	<h1>Settings overview for {$file}</h1>
	<form method="post" action="">
		<input type="text" name="file" id="file">
		<input type="submit" value="Submit">
	</form>
	<form method="post" action="">
		<input type="hidden" name="save" id="save" value="1">
		<input type="submit" value="Update personal config">
	</form>
	<table>
		<tr><th>Setting name<th>Meta info string<th>Default setting<th>Current setting
	{foreach from=$config key=name item=row}
		{if $row.meta.type eq 'password'}
			<tr><td>{$name}<td>{$row.metaraw}<td>{$row.default}<td><i>Password hidden</i>
		{else}
			<tr><td>{$name}<td>{$row.metaraw}<td>{$row.default}<td>{$row.value}
		{/if}
	{foreachelse}
		<tr><td colspan="4">No config data in this file
	{/foreach}
	</table>
</body>
</html>
