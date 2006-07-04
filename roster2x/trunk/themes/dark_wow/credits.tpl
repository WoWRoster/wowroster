
<!-- Begin Credits body -->
{$roster_wordings[$roster_conf.lang].credit_page_top|nl2br}
<br />
<br />

<div class="dev_list">
<span class="header">Active Devs</span>
{section loop=$credits_table.active name=member}
<div class="member">
<strong>{$credits_table.active[member].name}</strong>
	<div align="center">{$credits_table.active[member].info|nl2br}</div>
</div>
{/section}
</div>

<div class="dev_list">
<span class="header">In-Active Devs</span>
{section loop=$credits_table.inactive name=member}
<div class="member">
<strong>{$credits_table.inactive[member].name}</strong>
	<div align="center">{$credits_table.inactive[member].info|nl2br}</div>
</div>
{/section}
</div>
<!-- End Credits body -->

