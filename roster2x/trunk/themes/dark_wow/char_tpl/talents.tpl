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

{section loop=3 name=talenttree}
	<div id="treetab{$smarty.section.talenttree.index}" class="char_tab"{if not $smarty.section.talenttree.first} style="display:none;"{/if}>

		<div class="talent_points"><span style="color:#FFDD00">Points spent in {$treelayer[talenttree].name} Talents:</span> {$treelayer[talenttree].points}</div>
		<img class="talent_background" src="{$roster_conf.iconpath}/{$treelayer[talenttree].image}" alt="" />

		<div class="talent_container">

<!-- Begin TreeCells[{$smarty.section.talenttree.index}] -->
{section loop=8 name=talentrow start=1}
			<div class="talent_row">
{section loop=5 name=talentcell start=1}
{if $talents[talenttree][talentrow][talentcell].name neq ''}
{if $talents[talenttree][talentrow][talentcell].rank neq '0'}
				<div class="talent_cell" onmouseover="overlib({$talents[talenttree][talentrow][talentcell].tooltipid})"; onmouseout="return nd();">
					<img class="talent_rank_icon" src="{$roster_conf.imagepath}/char_page/talent_rank.gif">
					<div class="talent_rank_text" style="color:#{$talents[talenttree][talentrow][talentcell].numcolor};">{$talents[talenttree][talentrow][talentcell].rank}</div>
					<img src="{$roster_conf.iconpath}/{$talents[talenttree][talentrow][talentcell].image}" alt="" /></div>
{else}
				<div class="talent_cell" onmouseover="overlib({$talents[talenttree][talentrow][talentcell].tooltipid})"; onmouseout="return nd();">
					<img class="talent_icon_grey" src="{$roster_conf.iconpath}/{$talents[talenttree][talentrow][talentcell].image}" alt="" /></div>
{/if}
{else}
				<div class="talent_cell">&nbsp;</div>
{/if}

{/section}
			</div>
{/section}
<!-- END TreeCells[{$smarty.section.talenttree.index}] -->

		</div>

	</div>
{/section}

	<div id="char_navagation">
		<ul>
			<li onclick="return displaypage('treetab0',this);"><div class="text">{$treelayer[0].name}</div></li>
			<li onclick="return displaypage('treetab1',this);"><div class="text">{$treelayer[1].name}</div></li>
			<li onclick="return displaypage('treetab2',this);"><div class="text">{$treelayer[2].name}</div></li>
		</ul>
	</div>

</div>
