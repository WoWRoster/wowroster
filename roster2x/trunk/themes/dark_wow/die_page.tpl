
{include file='roster_header.tpl'}

{$text}

{if $roster_conf.debug eq true}
<br /><br />
<div class="default_border">
	<span style="font-weight:bold; font-size:14px; padding:5px;">Roster Debug Info</span>
{if isset($sql)}
	<div class="default_border" style="margin:3px; padding:5px;">
		<b>SQL:</b><br />
		{$sql}
	</div>
{/if}
{if isset($file)}
	<div class="default_border" style="margin:3px; padding:5px;">
		<b>FILE:</b> {$file}
	</div>
{/if}
{if isset($line)}
	<div class="default_border" style="margin:3px; padding:5px;">
		<b>LINE:</b> {$line}
	</div>
{/if}
</div>
{/if}

{include file='roster_footer.tpl'}
