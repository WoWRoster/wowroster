<?php


class mainFunctions
{

	var $block = array();

	function newsUPDATE($post,$html)
	{
		global $roster, $addon;

		$query = "UPDATE `" . $roster->db->table('news',$addon['basename']) . "` SET "
					. "`poster` = '" . $post['author'] . "', "
					. "`title` = '" . $post['title'] . "', "
					. "`text` = '" . $post['news'] . "', "
					. "`html` = '" . $html . "' "
					. "WHERE `news_id` = '" . $post['id'] . "';";

		if( $roster->db->query($query) )
		{
			$roster->set_message($roster->locale->act['news_edit_success']);
		}
		else
		{
			$roster->set_message('There was a DB error while editing the article.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		}
		return;
	}

	function newsADD($post,$html)
	{
		global $roster, $addon;

		$query = "INSERT INTO `" . $roster->db->table('news',$addon['basename']) . "` SET "
					. "`poster` = '" . $post['author'] . "', "
					. "`title` = '" . $post['title'] . "', "
					. "`text` = '" . $post['news'] . "', "
					. "`html` = '" . $html . "', "
					. "`date` = '". $roster->db->escape(gmdate('Y-m-d H:i:s')). "';";

		if( $roster->db->query($query) )
		{
			$roster->set_message($roster->locale->act['news_add_success']);
		}
		else
		{
			$roster->set_message('There was a DB error while adding the article.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		}
	}

	function makeUSERmenu( $sections )
	{
		global $roster;

		// Save current locale array
		// Since we add all locales for button name localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;

		$menue = array();

		// Add all addon locale files
		foreach( $roster->addon_data as $addondata )
		{
			foreach( $roster->multilanguages as $lang )
			{
				$roster->locale->add_locale_file(ROSTER_ADDONS . $addondata['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
			}
		}

		$section = "'" . implode("','",array_keys($sections)) . "'";

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename` "
			   . "FROM `" . $roster->db->table('menu_button') . "` AS mb "
			   . "LEFT JOIN `" . $roster->db->table('addon') . "` AS a "
			   . "ON `mb`.`addon_id` = `a`.`addon_id` "
			   . "WHERE `a`.`addon_id` IS NULL "
			   . "OR `a`.`active` = 1;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database .  MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while ($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$palet['b' . $row['button_id']] = $row;
		}

		$roster->db->free_result($result);

		// --[ Fetch menu configuration from DB ]--
		$query = "SELECT * FROM `" . $roster->db->table('menu') . "` WHERE `section` IN (" . $section . ") ORDER BY `config_id`;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$data[$row['section']] = $row;
		}

		$roster->db->free_result($result);

		$page = array();
		$arrayButtons = array();

		foreach( $sections as $name => $visible )
		{
			if( isset($data[$name]) )
			{
				$page[$name] = $data[$name];
			}
		}

		// --[ Parse DB data ]--
		foreach( $page as $name => $value )
		{
			$config[$name] = explode(':',$value['config']);
			foreach( $config[$name] as $pos=>$button )
			{
				if( isset($palet[$button]) )
				{
					$arrayButtons[$name][$pos] = $palet[$button];
				}
			}
		}

		foreach( $arrayButtons as $id => $page )
		{
			switch( $id )
			{
				case 'char':
					$panel_label = $roster->data['name'] . ' @ ' . $roster->data['region'] . '-' . $roster->data['server'];
					break;

				default:
					$panel_label = (isset($roster->locale->act['menupanel_' . $id]) ? sprintf($roster->locale->act['menu_header_scope_panel'], $roster->locale->act['menupanel_' . $id]) : '');
					break;
			}

			$menue[$panel_label][] = array(
				'ID' => $id,
				'OPEN' => !$sections[$id],
				'LABEL' => $panel_label
				);

			foreach( $page as $button )
			{
				if( !empty($button['icon']) )
				{
					if( strpos($button['icon'],'.') !== false )
					{
						$button['icon'] = ROSTER_PATH . 'addons/' . $button['basename'] . '/images/' . $button['icon'];
					}
					else
					{
						$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
					}
				}
				else
				{
					$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
				}

				if( !in_array($button['scope'],array('util','user','realm','guild','char')) || $button['addon_id'] == 0 )
				{
					$button['url'] = makelink($button['url']);
				}
				elseif( substr($button['url'],0,7) != 'http://')
				{
					$button['url'] = makelink($button['scope'] . '-' . $button['basename'] . (empty($button['url']) ? '' : '-' . $button['url']));
				}

				$button['title'] = isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'];
				if( strpos($button['title'],'|') )
				{
					list($button['title'],$button['tooltip']) = explode('|',$button['title'],2);
					$button['tooltip'] = ' ' . makeOverlib($button['tooltip'],$button['title'],'',1,'',',WRAP');
				}
				else
				{
					$button['tooltip'] = ' ' . makeOverlib($button['title']);
				}

				$menue[$button['scope']][] = array(
					'TOOLTIP'  => $button['tooltip'],
					'ICON'     => $button['icon'],
					'NAME'     => $button['title'],
					'SCOPE'    => $button['scope'],
					'BASENAME' => $button['basename'],
					'U_LINK'   => $button['url']
					);
			}
		}

		// Restore our locale array
		unset($localetemp);
		return $menue;
	}
	
	function image_resize($src, $dst, $width, $height, $crop=0)
	{
		global $roster;

		if (!file_exists($src)) return $roster->set_message('image "'.$src.'" not found!');
		
		if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

		$type = strtolower(substr(strrchr($src,"."),1));
		if($type == 'jpeg') $type = 'jpg';
		switch($type)
		{
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		// resize
		if($crop)
		{
			if($w < $width or $h < $height) return "Picture is too small!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		}
		else
		{
			if($w < $width and $h < $height) return "Picture is too small!";
			$ratio = min($width/$w, $height/$h);
			//$width = $w * $ratio;
			//$height = $h * $ratio;
			$x = 0;
		}

		$new = imagecreatetruecolor($width, $height);

		// preserve transparency
		if($type == "gif" or $type == "png")
		{
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}

		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		switch($type)
		{
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst); break;
			case 'png': imagepng($new, $dst); break;
		}
		return true;
	}
	/**
	 * Build the list of plugins to include based on roster scope and if plugins have plugins
	 *
	 *
	 */

	function _initPlugins()
	{
		global $roster, $addon;
		$plugins = $roster->plugin_data;
		
		$blocks = array();
		$query = "SELECT * FROM `" . $roster->db->table('blocks',$addon['basename']) . "` ORDER BY `block_location` ASC;";
		$result = $roster->db->query($query);
		while( $row = $roster->db->fetch($result) )
		{
				$blocks[$row['block_name']] = $row;
		}

		foreach ($blocks as $name => $data)
		{
			if (isset($plugins[$name]['active']) && $data['block_name'] == $plugins[$name]['basename'])
			{
				$xplugin = getplugin($name);
						
				foreach( $roster->multilanguages as $lang )
				{
					$roster->locale->add_locale_file($xplugin['locale_dir'] . $lang . '.php', $lang);
				}
				$plugin['scope'] = explode('|',$plugins[$name]['scope']);

				if (in_array( $roster->scope, $plugin['scope'] ) )
				{
					$classfile = ROSTER_PLUGINS . $name . DIR_SEP . $name . '.php';
					require($classfile);
					$pluginstuff = new $name($xplugin);

					$blocks[$name] = array(
						'name'   => $roster->locale->act[$name]['title'],//$pluginstuff->fullname,
						'output' => $pluginstuff->output,
						'icon'   => $pluginstuff->icon
					);
					unset($pluginstuff);
				}
			}
			else
			{
				$query = "DELETE FROM `" . $roster->db->table('blocks',$addon['basename']) . "` WHERE `block_name` = '".$name."';";
				$result = $roster->db->query($query);
				unset($blocks[$name]);
			}
		}
		
		$this->block = $blocks;
		return true;

	}
}
