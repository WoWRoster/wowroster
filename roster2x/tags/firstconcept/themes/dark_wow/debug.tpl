{* Smarty *}
{* debug.tpl, last updated version 2.0.1 *}
{assign_debug_info}


<!-- TEMPLATE SYSTEM DEBUG INFO -->
	<table border="0" width="100%" class="default_border">
		<tr>
			<th colspan="2">Smarty Debug Console</th>
		</tr>
		<tr>
			<td colspan="2"><b>included templates & config files (load time in seconds):</b></td>
		</tr>
{section name=templates loop=$_debug_tpls}
		<tr>
			<td colspan="2">{section name=indent loop=$_debug_tpls[templates].depth}&nbsp;&nbsp;&nbsp;{/section}<span style="color:{if $_debug_tpls[templates].type eq 'template'}brown{elseif $_debug_tpls[templates].type eq 'insert'}black{else}green{/if};">{$_debug_tpls[templates].filename|escape:html}</span>{if isset($_debug_tpls[templates].exec_time)} <font size="-1"><em>({$_debug_tpls[templates].exec_time|string_format:"%.5f"}){if %templates.index% eq 0} (total){/if}</em></font>{/if}</td>
		</tr>
{sectionelse}
		<tr>
			<td colspan="2"><em>no templates included</em></td>
		</tr>
{/section}
		<tr>
			<td colspan="2"><b>assigned template variables:</b></td>
		</tr>
{section name=vars loop=$_debug_keys}
		<tr>
			<td valign="top"style="color:blue">{ldelim}${$_debug_keys[vars]}{rdelim}</td>
			<td style="white-space:nowrap;color:green">{$_debug_vals[vars]|@debug_print_var}</td>
		</tr>
{sectionelse}
		<tr>
			<td colspan="2"><em>no template variables assigned</em></td>
		</tr>
{/section}
		<tr>
			<td colspan="2"><b>assigned config file variables (outer template scope):</b></td>
		</tr>
{section name=config_vars loop=$_debug_config_keys}
		<tr>
			<td valign="top" style="color:maroon;">{ldelim}#{$_debug_config_keys[config_vars]}#{rdelim}</td>
			<td style="color:green;">{$_debug_config_vals[config_vars]|@debug_print_var}</td>
		</tr>
{sectionelse}
		<tr>
			<td colspan="2"><em>no config vars assigned</em></td>
		</tr>
{/section}
	</table>

