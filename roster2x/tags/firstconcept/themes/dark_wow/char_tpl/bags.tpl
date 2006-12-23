
{section loop=$bagarray name=bag step=$loop}
<div class="{$bagarray[bag].type}" style="float:left;margin:3px;" id="{$bagarray[bag].id}">
	<div class="{$bagarray[bag].text_css}">{$bagarray[bag].name}</div>
	<img src="{$roster_conf.iconpath}/{$bagarray[bag].icon}" class="bagicon" alt="" /><img src="{$roster_conf.imagepath}/bags/{$bagarray[bag].icon_mask}.gif" class="bagmask" alt="" onmouseover="overlib({$bagarray[bag].id});" onclick="overlib({$bagarray[bag].id}_link,STICKY,MOUSEOFF);" onmouseout="return nd();" />
	<div class="bagholder">
		<div class="bagspacer">&nbsp;</div>
		<div class="bagspacer">&nbsp;</div>

{section loop=$bagarray[bag].items name=bagitem start=1}
{if $bagarray[bag].items[bagitem].image == ''}
		<div class="bagitem"><img src="{$roster_conf.imagepath}/spacer.gif" class="noicon" alt="" /></div>
{else}
		<div class="bagitem">
			{if $bagarray[bag].items[bagitem].quantity gt 1}<span class="itemQuantity_shadow">{$bagarray[bag].items[bagitem].quantity}</span><span class="itemQuantity">{$bagarray[bag].items[bagitem].quantity}</span>{/if}
			<img onmouseover="overlib({$bagarray[bag].id}_{$bagarray[bag].items[bagitem].id});" onclick="overlib({$bagarray[bag].id}_{$bagarray[bag].items[bagitem].id}_link,STICKY,MOUSEOFF);" onmouseout="return nd();" src="{$roster_conf.iconpath}/{$bagarray[bag].items[bagitem].image}" class="icon" alt="" /></div>
{/if}
{/section}

	</div>
</div>
{/section}
