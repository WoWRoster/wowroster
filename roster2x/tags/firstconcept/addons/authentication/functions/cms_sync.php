<?php
#-----------| CMS Synchronisation |-----------#	
class CMS_Sync extends Interface_Helper
{
	// Construct
	function CMS_Sync()
	{
		return;
	}
	
	function list_adapters()
	{
		$files = $this->safe_glob('../cms_sync_adapters/*/adapter_*.php');
		
		$adapter = array();
		foreach($files as $key => $adapter_link)
		{
			$folder_name = explode('/', $adapter_link, 4);
			$folder_name = $folder_name[2];
			$icon = $this->safe_glob('../cms_sync_adapters/'.$folder_name.'/icon_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
			$adapter[$key]['adapter'] = $adapter_link;
			$adapter[$key]['icon'] = (!empty($icon) ? $icon[0] : NULL);
			$adapter[$key]['name'] = $folder_name;
		}
		
		$buffer = '<table border="0" cellpadding="1" cellspacing="1" width="530px">';
		$i = 1;
		foreach($adapter as $key => $files)
		{
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$buffer .='	<tr class="'.$line_color.'" style="font-size:14px; font-weight:bold; vertical-align:middle;">
							<td style="height:30px; width:80px;">';
							if(!empty($files['icon'])){
			$buffer .='			<a href="?adapter='.base64_encode($files['adapter']).'&name='.urlencode($files['name']).'&print=true&display=dsn"><img src="'.$files['icon'].'" border="0" /></a>'; 
			}$buffer .='	</td>
							<td style="width:100%; padding-left:10px;">
								<a href="?adapter='.base64_encode($files['adapter']).'&name='.urlencode($files['name']).'&print=true&display=dsn">'.$files['name'].'</a>
							</td>
						</tr>';
		}
		$buffer .= '</table>';
		print $buffer;
	}
	
	
	function load_adapter($arguments=array())
	{
		if(!empty($arguments['adapter'])&&!empty($arguments['name']))
		{
			$adapter = base64_decode($arguments['adapter']);
			require_once $adapter;
			$this->Adapter = new $arguments['name'];
		}
	}
	
	
	function show_adapter($arguments=array())
	{
		$this->load_adapter(array('adapter'=>$arguments['adapter'], 'name'=>$arguments['name']));
		$this->Adapter->treat_get_post($_REQUEST);
		print $this->Adapter->display;
	}
	
	
	/*
		safe_glob($pattern, $flags)
		Original script found at and submitted by:
		http://au2.php.net/manual/en/function.glob.php
		BigueNique at yahoo dot ca
		---
		This function replicates (more or less) the native function 'glob()' if its not available due to:
		The PHP version being lower than 4.3.0 or
		The function being disabled by the host because of security concerns.
		In any case, just call this function instead of straight up glob(), if it works with glob() then thats it, if not, then the rest of this function is used.
		---
		Changed it to support one directory wildcard: * before the filename search pattern and added the GLOB_BRACE flag.
		GLOB_BRACE adds support for patterns like: 'pictures.{jpg,jpeg,gif,png}' and all files with the name pictures and one of those endings will be returned.
		Eg: '../movies/* /*.{mpg,mpeg}' (no space after *) This would go up one directory, into the movies folder there,
		then search every subdirectory for .mpg and .mpeg files and return the list of path&filenames in an array,
		like this: array(0=>'../movies/x-files/episode01.mpg', 1=> '../movies/lost/lost_ep1.mpeg')
		
		From the last specific directory name downwards, every directory is searched for files matching the filename pattern.
		Keep that in mind if you wonder about the performance on a very general path with lots of subdirectories and files to search.
	*/
	function safe_glob($pattern, $flags=NULL) 
	{
		if(version_compare(phpversion(), '4.3.0', '>='))
		{
			$glob = @glob($pattern, $flags);
			if(!empty($glob)) return $glob;
		}


		/*
			Script found at and submitted by:
			http://au2.php.net/manual/en/function.fnmatch.php
			soywiz at php dot net
			---
			The function 'fnmatch()' doesnt exist on Windows due to the underlying program not existing on Windows.
			This offers the same functionality but runs on Windows and is only used when executed on a Windows server.
		*/
		if (!function_exists('fnmatch')) {
			function fnmatch($pattern, $string) {
				return @preg_match('/^' . strtr(addcslashes($pattern, '\\.+^$(){}=!<>|'), array('*' => '.*', '?' => '.?')) . '$/i', $string);
			}
		}
		// -----------| safe_glob |----------- \\
		$split=explode('/',$pattern);
		$match=array_pop($split);
		$split_copy = $split;
		$path = $this->get_path($split_copy);
		if (($dir=opendir($path))!==false) {
			$glob = $this->glob_dir($dir, $path, $match, $flags);
			$glob = $this->rekey_array($glob);
			if (!($flags&GLOB_NOSORT)) sort($glob);
			return $glob;
		} else {
			return false;
		}
	}
		
		
	function rekey_array($big_array, $new_array=array())
	{
		foreach($big_array as $array_index => $array)
		{
			if(!empty($array)) 
			{
				if(is_array($array[0])) $new_array = $this->rekey_array($array, $new_array);
				elseif(is_string($array)) $new_array[] = $array;
				else $new_array[] = $array[0];
			}
		}
		return $new_array;
	}
		
		
	function glob_dir($dir, $path, $match, $flags)
	{
		$glob=array();
		while(($file=readdir($dir))!==false) 
		{
			if (is_dir($path.'/'.$file))
			{
				if (($file!=='.' && $file!=='..')&&($sub_dir=opendir($path.'/'.$file))!==false) {
					$glob[] = $this->glob_dir($sub_dir, $path.'/'.$file, $match, $flags);
				}
			}else{
				if($flags==GLOB_BRACE)
				{
					$new_pattern = array_shift(explode('{', $match));
					$new_endings = explode(',', array_shift(explode('}', array_pop(explode('{', $match)))));
					foreach($new_endings as $key => $ending)
					{
						if (fnmatch($new_pattern.$ending,$file)) {
							if ((is_dir($path.'/'.$file))||(!($flags&GLOB_ONLYDIR))) {
								if ($flags&GLOB_MARK) $file.='/';
								$glob[]=$path.'/'.$file;
							}
						}
					}
				}else{
					if (fnmatch($match,$file)) {
						if ((is_dir($path.'/'.$file))||(!($flags&GLOB_ONLYDIR))) {
							if ($flags&GLOB_MARK) $file.='/';
							$glob[]=$path.'/'.$file;
						}
					}
				}
			}
		}
		return $glob;
	}
	
	
	function get_path($split_copy)
	{
		if(in_array('*', $split_copy))
		{
			foreach($split_copy as $key => $value)
			{
				if($value == '*')
				{
					while(count($split_copy)>$key) $next = array_pop($split_copy);
					return implode('/', $split_copy);
				}
			}
		}else{
			return implode('/', $split_copy);
		}
	}
		
}
	

?>