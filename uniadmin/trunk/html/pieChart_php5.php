<?php

//globals=off compatibility
$title = $_REQUEST['title'];
$slice = $_REQUEST['slice'];
$itemName = $_REQUEST['itemName'];
$action = $_REQUEST['action'];

function matchset($xx)
{
    $arrx = array_values($xx);
    $i = 0;
    while (list ($key, $val) = each ($arrx))
	{
		$xy[$i] = $val;
		$i++;
    }
    $cnt = $i;
	return $xy;
}

$last_angle = 0;

$sliced = matchset($slice);
$countqw = count($sliced);

$ItemNames = matchset($itemName);

// initialize some variables
$sum = 0;
$degrees = Array();
$diameter = 200;
$radius = $diameter/2;

// calculate sum of slices
for ($x=0; $x<$countqw ; $x++)
{
	$sum += $sliced[$x];
}

// convert each slice into corresponding percentage of 360-degree circle
$degCount = 0;
for ($y=0; $y<$countqw; $y++)
{
	if((($sliced[$y]/$sum) * 360) > '0')
	{
		$degrees[$degCount] = ($sliced[$y]/$sum) * 360;
		$degCount++;
	}
}


// set up image and colours
Header("Content-Type: image/png");

$width = 730;
$im = ImageCreate($width, 250);

$black = ImageColorAllocate($im, 0, 0, 0);
$white = ImageColorAllocate($im, 255, 255, 255);
$hexCode = array("255,153,0","0,204,153","204,255,102","255,102,102","102,204,255","204,153,255","255,0,0","51,0,255","255,51,153","204,0,255","255,255,51","51,255,51","255,102,0");
// fill image with white
ImageFill($im, 0, 0, $white);

// draw baseline
ImageLine($im, 150,150, 225, 150, $black);

for ($z=0; $z<$countqw; $z++)
{
	// calculate and draw arc corresponding to each slice
	ImageArc($im, 150, 150, $diameter, $diameter, $last_angle,
	($last_angle+$degrees[$z]), $black);
	$last_angle = $last_angle+$degrees[$z];

	// calculate coordinate of end-point of each arc by obtaining
	// length of segment and adding radius
	// remember that cos() and sin() return value in radians
	// and have to be converted back to degrees!
	$end_x = round(150 + ($radius * cos($last_angle*pi()/180)));
	$end_y = round(150 + ($radius * sin($last_angle*pi()/180)));

	// demarcate slice with another line
	ImageLine($im, 150, 150, $end_x, $end_y, $black);
}

// this section is meant to calculate the mid-point of each slice
// so that it can be filled with colour

// initialize some variables
$prev_angle = 0;
$pointer = 0;

for ($z=0; $z<$countqw; $z++)
{
	// to calculate mid-point of a slice, the procedure is to use an angle
	//bisector
	// and then obtain the mid-point of that bisector
	$pointer = $prev_angle + $degrees[$z];
	$this_angle = ($prev_angle + $pointer) / 2;
	$prev_angle = $pointer;

	// get end-point of angle bisector
	$end_x = round(150 + ($radius * cos($this_angle*pi()/180)));
	$end_y = round(150 + ($radius * sin($this_angle*pi()/180)));

	// given start point (150,150) and end-point above, mid-point can be
	// calculated with standard mid-point formula
	$mid_x = round((150+($end_x))/2);
	$mid_y = round((150+($end_y))/2);

	// depending on which slice, fill with appropriate colour
	$hexCodeSplit = explode(',',$hexCode[$z]);
	$WedgeColor = ImageColorAllocate($im, $hexCodeSplit[0],$hexCodeSplit[1],$hexCodeSplit[2]);

	ImageFillToBorder($im, $mid_x, $mid_y, $black, $WedgeColor);
}

// write string
ImageString($im,3, 10, 10, "$title", $black);

$red = ImageColorAllocate($im, 255, 153, 153);
$blue = ImageColorAllocate($im, 0, 0, 255);

// Create Color key and slice description
$adjPosition = 20;

$xPosOffset = -30;// to move the key around
$yPosOffset = -0;// to move the key around

for ($z=0; $z<$degCount; $z++)
{
	$percent = ($degrees[$z]/360)*100;
	$percent = round($percent,2);
	$adjPosition = $adjPosition + 15;
	$hexCodeSplit = explode(',',$hexCode[$z]);
	$percentLen = strlen($percent);
	if($percentLen == '4'){$percent = " "."$percent";}
	if($percentLen == '3'){$percent = "  "."$percent";}
	if($percentLen == '2'){$percent = "   "."$percent";}
	if($percentLen == '1'){$percent = "    "."$percent";}
	ImageString($im,1, 270 + $xPosOffset, ($adjPosition+1 + $yPosOffset), "$percent%", $black); // orig 270 %

	$WedgeColor = ImageColorAllocate($im, $hexCodeSplit[0],$hexCodeSplit[1],$hexCodeSplit[2]);

	ImageFilledRectangle($im, 310 + $xPosOffset, $adjPosition + $yPosOffset, 320 + $xPosOffset, ($adjPosition+10 + $yPosOffset), $black); //310/320 orig
	ImageFilledRectangle($im, 311 + $xPosOffset, ($adjPosition+1 + $yPosOffset), 319 + $xPosOffset, ($adjPosition+9 + $yPosOffset), $WedgeColor);//311/319
	if($sliced[$z] >= "1000" && $sliced[$z] < "1000000")
	{
		$sliced[$z] = $sliced[$z]/1000;
		$sliced[$z] = "$sliced[$z]"."k";
	}
	$sliceLen = strlen($sliced[$z]);
	if($sliceLen == '4'){$sliced[$z] = " "."$sliced[$z]";}
	if($sliceLen == '3'){$sliced[$z] = "  "."$sliced[$z]";}
	if($sliceLen == '2'){$sliced[$z] = "   "."$sliced[$z]";}
	if($sliceLen == '1'){$sliced[$z] = "    "."$sliced[$z]";}

	ImageString($im,1, 325 + $xPosOffset, ($adjPosition+1 + $yPosOffset), "$sliced[$z]", $black);//# of hits //325 orig
	ImageString($im,1, 360 + $xPosOffset, ($adjPosition+1 + $yPosOffset), "$ItemNames[$z]", $black);//name of  //360 orig
}

// output to browser
ImagePNG($im);
?>
