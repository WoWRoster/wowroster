// JavaScript Document
/******************************
 * Gear Browser
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (9/2/2006 2:15 PM)
 * Compatible with Roster 1.70
 ******************************/


function setGBActiveTalentWrapper (o,imgurl,pos)
{
	var obj = document.getElementById(pos+ 'talentwrapper' + o);

	if (obj.style.display == 'block')
	{
		return;
	}
	else
	{
		for (var i=1; i < 4; i++)
		{
			document.getElementById(pos + 'talentwrapper' +i).style.display = 'none';
			document.getElementById(pos + 'tlab' +i).style.fontWeight = 'normal';
			document.getElementById(pos + 'tlab' + i).style.color = 'white';
			document.getElementById(pos + 'tlabbg' +i).src = imgurl + '/itab.gif';
		}
		obj.style.display = 'block';
		document.getElementById(pos + 'tlab' + o).style.fontWeight = 'bold';
		document.getElementById(pos + 'tlab' + o).style.color = '#CDAD0F';
		document.getElementById(pos + 'tlabbg' +o).src = imgurl + '/atab.gif';
	}
	return;
}

function showGBPet(cNum,pos)
{
	var ids = new Array();
	ids[0] = 'pet_name';
	ids[1] = 'pet_title';
	ids[2] = 'pet_loyalty';
	ids[3] = 'pet_top_icon';
	ids[4] = 'pet_resistances';
	ids[5] = 'pet_stats_table';
	ids[6] = 'pet_xp_bar';
	ids[7] = 'pet_training_pts';
	ids[8] = 'pet_hpmana';
	ids[9] = 'pet_training_nm';

	for(a = 0; a < 15; a++)
	{
		for(i = 0; i < 15; i++)
		{
			if (cNum != i)
			{
				var oName= document.getElementById(ids[a]+pos+String(i));
				if (oName != null)
				{
					hideElem(oName);
				}
			}
			else
			{
				var oName= document.getElementById(ids[a]+pos+String(i));
				if (oName != null)
				{
					if(oName.style.display == 'none' || oName.style.display == '')
					{
						showElem(oName);
					}
				}
			}
		}
	}
}

var Lefttabs = new Array();
var Righttabs = new Array();

function addGBTab( name, pos, side )
{
	if (side == 'Left') {
		Lefttabs[pos] = name;
	}
	else
	{
		Righttabs[pos] = name;
	}
}

function doGBSynchTab( placement )
{
	backupLeft = document.getElementById(Lefttabs[1]);
	backupLeftFont = document.getElementById('tabfont'+Lefttabs[1]);
	backupRight = document.getElementById(Righttabs[1]);
	backupRightFont = document.getElementById('tabfont'+Righttabs[1]);
	newLeft = 0;
	newRight = 0;
	for( i=1 ; i<7 ; i++ )
	{
		// Get the left and right tab #
		leftobj = document.getElementById(Lefttabs[i]);
		leftfont = document.getElementById('tabfont'+Lefttabs[i]);
		rightobj = document.getElementById(Righttabs[i]);
		rightfont = document.getElementById('tabfont'+Righttabs[i]);
		
		if (i == placement) {
			if(leftobj) {
				showElem(leftobj);
				newLeft = i;
				leftfont.className='white';
			}
			if(rightobj) {
				showElem(rightobj);
				newRight = i;
				rightfont.className='white';
			}
		}
		else {
			// if either is null (pet vs no pet), skip that side.
			if(leftobj) {
				hideElem(leftobj);
				leftfont.className='yellow';
			}
			if(rightobj) {
				hideElem(rightobj);
				rightfont.className='yellow';
			}
		}
	}
	if (newLeft <= 0) {
		//if we get here, NO tabs are now showing.
		showElem(backupLeft);
		backupLeftFont.className='white';
	}
	if (newRight <= 0) {
		showElem(backupRight);
		backupRightFont.className='white';
	}
}