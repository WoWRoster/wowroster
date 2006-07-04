
<!-- Begin Character Menu -->
<div id="char_menubar">
	<a href="?p=char&amp;member={$data.member_id}&amp;action=character" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_character}',WRAP);" onmouseout="return nd();">
		<img class="char_image" src="{$roster_conf.imagepath}/char_page/char_portrait/{$char_image|lower|strip:''}.gif" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=talents" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_talents}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'talents'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_talents.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=spellbook" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_spellbook}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'spellbook'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_spellbook.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=bags" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_bags}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'bags'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_bags.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=bank" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_bank}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'bank'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_bank.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=quests" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_quests}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'quests'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_questlog.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=professions" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_professions}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'professions'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_professions.jpg" alt="" /></a>

	<a href="?p=char&amp;member={$data.member_id}&amp;action=pvp" onmouseover="overlib('{$roster_wordings[$roster_conf.lang].charpage_menu_pvplog}',WRAP);" onmouseout="return nd();">
		<img class="icon{if $action eq 'pvp'}_selected{/if}" src="{$roster_conf.imagepath}/char_page/menubar/menu_pvp.jpg" alt="" /></a>
</div>
<!-- End Character Menu -->
