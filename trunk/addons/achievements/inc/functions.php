<?php
//require_once ($addon['dir'] . 'inc/simpleclass.lib.php');

class achv
{
	var $b = '';
	var $y;
	var $form = '1';
	var $cfg = array();
	var $header_name = '';

	function getConfigDatamod($member_id)
	{
		global $roster, $addon;

		$sql = "SELECT * FROM `" . $roster->db->table('data',$addon['basename']) . "` "
			. "WHERE `member_id` = '" . $member_id . "' "
			. "ORDER BY `achv_cat` ASC, `achv_date` DESC ,`achv_cat_sub` DESC;";

		$this->cfg = '';

		// Get the current config values
		$results = $roster->db->query($sql);
		$is = 0;

//		echo $roster->db->num_rows($results) . '<br/>';

		if( $results && $roster->db->num_rows($results) > 0 )
		{
			while($row = $roster->db->fetch($results))
			{
				$b = $row['member_id'];
				$c = $row['achv_cat'];
				$d = $row['achv_cat_title'];
				$e = $row['achv_cat_sub'];
				$is++;

				$this->cfg[$c][$d][$e]['menue'] = $is;
				$this->cfg[$c][$d][$e]['info'][$is]['achv_title'] = $row['achv_title'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_icon'] = $row['achv_icon'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_disc'] = $row['achv_disc'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_id'] = $row['achv_id'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_criteria'] = $row['achv_criteria'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_progress'] = $row['achv_progress'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_progress_width'] = $row['achv_progress_width'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_points'] = $row['achv_points'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_date'] = $row['achv_date'];
				$this->cfg[$c][$d][$e]['info'][$is]['achv_complete'] = $row['achv_complete'];
			}

			$roster->db->free_result($results);

			return $this->cfg;
		}
		else
		{
			return $roster->db->error();
		}
	}


	function getConfigDatamod2($member_id)
	{
		global $roster, $addon;

		$sql = "SELECT * FROM `" . $roster->db->table('data',$addon['basename']) . "` "
			. "WHERE `member_id` = '" . $member_id . "' ORDER BY `" . $roster->db->table('data',$addon['basename']) . "`.`achv_date` DESC 
LIMIT 0 , 5 ;";

		$this->cfg = '';
		// Get the current config values
		$results = $roster->db->query($sql);
		$d = 0;

		if( $results && $roster->db->num_rows($results) > 0 )
		{
			while($row = $roster->db->fetch($results))
			{
				$d++;

				$this->cfg[$d]['title'] = $row['achv_title'];
				$this->cfg[$d]['disc'] = $row['achv_disc'];
				$this->cfg[$d]['date'] = $row['achv_date'];
				$this->cfg[$d]['points'] = $row['achv_points'];

			}

			$roster->db->free_result($results);

			return $this->cfg;
		}
		else
		{
			return $roster->db->error();
		}
	}

	function bar_width( $c, $t )
	{
		global $roster, $addon;

//		list($c,$t) = explode(' / ', $val);
		$width = ($c / $t)*100;
		return $width;
	}
}
