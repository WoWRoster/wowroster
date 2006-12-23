
<div class="char_panel">
	<img src="{$roster_conf.imagepath}/char_page/menubar/icon_spellbook.gif" class="char_tab_image" alt=""/>
	<div class="char_name">{$roster_wordings[$roster_conf.lang].charpage_menu_spellbook}</div>
	<div class="SpellBackground">&nbsp;</div>

	<!-- Skill Type Icons Menu -->
	<div class="spell_skill_tab_bar">
{section loop=$spelltree name=treeicon_id}
		<div class="spell_skill_tab">
			<img class="spell_skill_tab_icon" src="{$roster_conf.iconpath}/{$spelltree[treeicon_id].icon}.{$roster_conf.icon_ext}" onmouseover="overlib('{$spelltree[treeicon_id].name}',WRAP);" onmouseout="return nd();" alt="" onclick="showSpellTree('spelltree_{$smarty.section.treeicon_id.index}');" />
		</div>
{/section}
	</div>

{section loop=$spelltree name=tree_id}
{if $smarty.section.tree_id.first}
	<div id="spelltree_{$smarty.section.tree_id.index}">
{else}
	<div id="spelltree_{$smarty.section.tree_id.index}" style="display:none;">
{/if}

{section loop=$spelltree[tree_id].spells name=page_id}
{if $smarty.section.page_id.first and $smarty.section.page_id.last}
		<div id="page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}">
{elseif $smarty.section.page_id.first}
		<div id="page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}">

			<div class="spell_page_forward" onclick="showLayer('page_{$smarty.section.page_id.index_next}_{$smarty.section.tree_id.index}');hideLayer('page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}');">Next <img src="{$roster_conf.imagepath}/char_page/pageforward.gif" class="navicon" alt="" /></div>
{elseif $smarty.section.page_id.last}
		<div id="page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}" style="display:none;">

			<div class="spell_page_back" onclick="showLayer('page_{$smarty.section.page_id.index_prev}_{$smarty.section.tree_id.index}');hideLayer('page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}');"><img src="{$roster_conf.imagepath}/char_page/pageback.gif" class="navicon" alt="" /> Prev</div>
{else}
		<div id="page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}" style="display:none;">

			<div class="spell_page_back" onclick="showLayer('page_{$smarty.section.page_id.index_prev}_{$smarty.section.tree_id.index}');hideLayer('page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}');"><img src="{$roster_conf.imagepath}/char_page/pageback.gif" class="navicon" alt="" /> Prev</div>
			<div class="spell_page_forward" onclick="showLayer('page_{$smarty.section.page_id.index_next}_{$smarty.section.tree_id.index}');hideLayer('page_{$smarty.section.page_id.index}_{$smarty.section.tree_id.index}');">Next <img src="{$roster_conf.imagepath}/char_page/pageforward.gif" class="navicon" alt="" /></div>
{/if}
			<div class="spell_pagenumber">Page {$smarty.section.page_id.iteration}</div>


{section loop=$spelltree[tree_id].spells[page_id] name=icon_id}
{if $smarty.section.icon_id.first}
			<div class="spell_container_1">
{elseif $smarty.section.icon_id.index eq 7}
			</div>
			<div class="spell_container_2">
{/if}
				<div class="spell_info_container">
					<img src="{$roster_conf.iconpath}/{$spelltree[tree_id].spells[page_id][icon_id].icon}.{$roster_conf.icon_ext}" class="icon" onmouseover="overlib(spelltip_{$smarty.section.tree_id.index}_{$smarty.section.page_id.index}_{$smarty.section.icon_id.index},WRAP);" onmouseout="return nd();" alt="" />
					<span class="text"><span class="myYellow">{$spelltree[tree_id].spells[page_id][icon_id].name}</span>{if $spelltree[tree_id].spells[page_id][icon_id].rank neq ''}<br /><span class="myBrown">{$spelltree[tree_id].spells[page_id][icon_id].rank}{/if}</span></span>
				</div>
{/section}
			</div>

		</div>
{/section}


	</div>
{/section}

</div>
