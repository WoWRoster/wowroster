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
			. "ORDER BY `achv_cat` ASC, `achv_date` DESC ,`achv_cat_sub2` DESC;";

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

		$sql = "SELECT * FROM `" . $roster->db->table('summary',$addon['basename']) . "` "
			. "WHERE `member_id` = '" . $member_id . "';";

		$this->cfg = '';
		// Get the current config values
		$results = $roster->db->query($sql);
		$is = 0;

//		echo $roster->db->num_rows($results) . '<br/>';

		if( $results && $roster->db->num_rows($results) > 0 )
		{
			while($row = $roster->db->fetch($results))
			{
				$d = 'Summary';

//				$this->cfg[$d][$e]['menue'] = $is;

				$this->cfg[$d]['total'] = $row['total'];
				$this->cfg[$d]['general'] = $row['general'];
				$this->cfg[$d]['quests'] = $row['quests'];
				$this->cfg[$d]['exploration'] = $row['exploration'];
				$this->cfg[$d]['pvp'] = $row['pvp'];
				$this->cfg[$d]['dn_raids'] = $row['dn_raids'];
				$this->cfg[$d]['prof'] = $row['prof'];
				$this->cfg[$d]['rep'] = $row['rep'];
				$this->cfg[$d]['world_events'] = $row['world_events'];
				$this->cfg[$d]['feats'] = $row['feats'];

				$this->cfg[$d]['title_1'] = $row['title_1'];
				$this->cfg[$d]['disc_1'] = $row['disc_1'];
				$this->cfg[$d]['date_1'] = $row['date_1'];
				$this->cfg[$d]['points_1'] = $row['points_1'];

				$this->cfg[$d]['title_2'] = $row['title_2'];
				$this->cfg[$d]['disc_2'] = $row['disc_2'];
				$this->cfg[$d]['date_2'] = $row['date_2'];
				$this->cfg[$d]['points_2'] = $row['points_2'];

				$this->cfg[$d]['title_3'] = $row['title_3'];
				$this->cfg[$d]['disc_3'] = $row['disc_3'];
				$this->cfg[$d]['date_3'] = $row['date_3'];
				$this->cfg[$d]['points_3'] = $row['points_3'];

				$this->cfg[$d]['title_4'] = $row['title_4'];
				$this->cfg[$d]['disc_4'] = $row['disc_4'];
				$this->cfg[$d]['date_4'] = $row['date_4'];
				$this->cfg[$d]['points_4'] = $row['points_4'];

				$this->cfg[$d]['title_5'] = $row['title_5'];
				$this->cfg[$d]['disc_5'] = $row['disc_5'];
				$this->cfg[$d]['date_5'] = $row['date_5'];
				$this->cfg[$d]['points_5'] = $row['points_5'];

/*
				$this->cfg[$d][$e]['info']['achv_disc'] = $row['achv_disc'];
				$this->cfg[$d][$e]['info']['achv_id'] = $row['achv_id'];
				$this->cfg[$d][$e]['info']['achv_criteria'] = $row['achv_criteria'];
				$this->cfg[$d][$e]['info']['achv_points'] = $row['achv_points'];
				$this->cfg[$d][$e]['info']['achv_date'] = $row['achv_date'];
				$this->cfg[$d][$e]['info']['achv_complete'] = $row['achv_complete'];
*/
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
