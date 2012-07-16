<?php
function save_image($inPath,$outPath)
{
	//Download images from remote server
	$in=    fopen($inPath, "rb");
	$out=   fopen($outPath, "wb");
	while ($chunk = fread($in,8192))
	{
		fwrite($out, $chunk, 8192);
	}
	fclose($in);
	fclose($out);
	if (file_exists($outPath))
	{
		return true;
	}
	else
	{
		return false;
	}
}

	$server = $roster->data['server'];
	$name = $roster->data['guild_name'];
	
	$players = array();
	$query = "SELECT * FROM `".$roster->db->table('members')."`";			
	$result = $roster->db->query($query);
	while($char = $roster->db->fetch($result))
	{
		$players[$char['name']] = $char['member_id'];
	}
	
	
	$guild = $roster->api->Guild->getGuildInfo($server,$name,'1');
	 
	foreach ( $guild['members'] as $id => $feed )
	{

		if (isset($feed['character']['thumbnail']))
		{
			$e = $feed['character']['thumbnail'];
			$c = explode("-", $e);

			$img_url = "http://us.battle.net/static-render/us/".$c[0]."-profilemain.jpg";
			$save_path = $addon['dir'].'chars/'.$players[$feed['character']['name']].'.jpg';
			/*
			if (!save_image($img_url,$save_path))
			{
				echo ' - <font color=red>Not saves</font><br>';
			}
			else
			{
				echo ' - <font color=green>Saved '.$feed['character']['name'].'-'.$players[$feed['character']['name']].'</font><br>';
				
				$file = $char['member_id'].'.jpg';
				/*
				$dir = $addon['dir'].'chars/';
				list($width, $height) = getimagesize($dir.$file);
				$im = imagecreatetruecolortrans( 369,479 );
				$saved_image = $dir.'thumb-'.strtolower($file);
				$im_temp = imagecreatefromjpeg($dir.$file);
				//imagecopyresampled($im, $im_temp, 0, 0, 213, 45, 369, 479, $width, $height);
				@imagecopy( $im,$im_temp,0,0,213, 45, 369, 479 );
				//header('Content-type: image/png');
				imagePng( $im,$saved_image );//imagepng($im);
				imagedestroy($im);
				imagedestroy($im_temp);
				
			}*/
		}
	}
		function imagecreatetruecolortrans($x,$y)
    {
        $i = @imagecreatetruecolor($x,$y)
			or debugMode( (__LINE__),'Cannot Initialize new GD image stream','',0,'Make sure you have the latest version of GD2 installed' );

        $b = imagecreatefromstring(base64_decode(blankpng()));

        imagealphablending($i,false);
        imagesavealpha($i,true);
        imagecopyresized($i,$b,0,0,0,0,$x,$y,imagesx($b),imagesy($b));
        imagealphablending($i,true);

        return $i;
    }
    function blankpng()
	{
		$c  = "iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m".
			"dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg".
			"dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN".
			"egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ".
			"oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA".
			"DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=";
		return $c;
	}