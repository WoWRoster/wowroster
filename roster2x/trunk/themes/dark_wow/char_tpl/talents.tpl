{literal}
<script type="text/javascript">

//Set tab to intially be selected when page loads:
//[which tab (1=first tab), ID of tab content to display]:
var initialtab=[1, 'treetab0']
var previoustab=''

if (window.addEventListener)
	window.addEventListener('load', charpage_onload, false)
else if (window.attachEvent)
	window.attachEvent('onload', charpage_onload)
else if (document.getElementById)
	window.onload=charpage_onload


</script>
{/literal}
<div class="char_panel">

	<img class="char_tab_image" src="{$roster_conf.imagepath}/char_page/menubar/icon_talents.gif" alt=""/>
	<div class="char_name">{$roster_wordings[$roster_conf.lang].charpage_menu_talents}</div>
	<img src="{$roster_conf.imagepath}/char_page/talentbar_top.gif" style="position:absolute;margin-top:43px;margin-left:65px;" alt="" />
	<img src="{$roster_conf.imagepath}/char_page/talentbar_bottom.gif" style="position:absolute;margin-top:402px;margin-left:12px;" alt="" />
	<div class="talent_points_unused"><span style="color:#FFDD00">Talent Points:</span> {$data.unused_talent_points}</div>

{foreach from=$treelayer item=tree name=tree}
	<div id="treetab{$smarty.foreach.tree.index}" class="char_tab"{if not $smarty.foreach.tree.first} style="display:none;"{/if}>

		<div class="talent_points"><span style="color:#FFDD00">Points spent in {$tree.name} Talents:</span> {$tree.points}</div>
		<img class="talent_background" src="{$roster_conf.iconpath}/{$tree.image}" alt="" />

		<div class="talent_container">

{foreach from=$tree.talents item=row}
			<div class="talent_row">
{foreach from=$row item=cell}
{if $cell.name neq ''}
{if $cell.rank neq '0'}
				<div class="talent_cell" onmouseover="overlib({$cell.tooltipid})"; onmouseout="return nd();">
					<img class="talent_rank_icon" src="{$roster_conf.imagepath}/char_page/talent_rank.gif">
					<div class="talent_rank_text" style="font-weight:bold;color:#{$cell.numcolor};">{$cell.rank}</div>
					<img src="{$roster_conf.iconpath}/{$cell.image}" alt="" /></div>
{else}
				<div class="talent_cell" onmouseover="overlib({$cell.tooltipid})"; onmouseout="return nd();">
					<img class="talent_icon_grey" src="{$roster_conf.iconpath}/{$cell.image}" alt="" /></div>
{/if}
{else}
				<div class="talent_cell">&nbsp;</div>
{/if}

{/foreach}
			</div>
{/foreach}

		</div>

	</div>
{/foreach}

	<div id="char_navagation">
		<ul>
			<li onclick="return displaypage('treetab0',this);"><div class="text">{$treelayer[0].name}</div></li>
			<li onclick="return displaypage('treetab1',this);"><div class="text">{$treelayer[1].name}</div></li>
			<li onclick="return displaypage('treetab2',this);"><div class="text">{$treelayer[2].name}</div></li>
		</ul>
	</div>

</div>
