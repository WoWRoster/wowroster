
<!-- Begin Roster Main Menu -->
<div id="main">
	<ul>
{*}Disabling multi-guild mode selection temporarly{if $roster_conf.multiple_guilds}{/*}
		<li><a href="?p=guilds">Guild List</a></li>
{*}{else}{/*}
		<li><a href="?p=members">{$roster_wordings[$roster_conf.lang].pagetitle_members}</a></li>
{*}{/if}{/*}
		<li><a href="?p=update">{$roster_wordings[$roster_conf.lang].pagetitle_update}</a></li>
		<li><a href="?p=addon">{$roster_wordings[$roster_conf.lang].pagetitle_addons}</a></li>

	</ul>
</div>
<!-- End Roster Main Menu -->
