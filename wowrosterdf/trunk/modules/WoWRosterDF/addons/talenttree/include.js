var q = '?';

if(typeof mt_width != 'undefined')
{
	if(isNaN(mt_width) || mt_width < 1 || mt_width > 2000)
		mt_width = 500;
}
else
	var mt_width = 500;


if(typeof mt_class != 'undefined')
{
	if(mt_class >= 1 && mt_class <= 9)
		q += parseInt(mt_class);
}

if(typeof mt_css != 'undefined')
{
	q += ';' + mt_css;
}
	
if(q == '?')
	q = '';

document.write('<iframe src="http://www.wowhead.com/talent/include.html' + q + '" frameborder="0" width="' + mt_width + '" height="666" allowtransparency="true"></iframe>');