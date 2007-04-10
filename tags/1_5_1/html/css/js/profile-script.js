function setActiveTalentWrapper (o) {
    var obj = document.getElementById('talentwrapper' + o);
    if (obj.style.display == 'block') {
	return;
    } else {
	for (var i=1; i < 4; i++) {
	    document.getElementById('talentwrapper' +i).style.display = "none";
	    document.getElementById('tlab' +i).style.fontWeight = "normal";
	    document.getElementById('tlab' + i).style.color = 'white';
	    document.getElementById('tlabbg' +i).src = 'img/itab.gif';
	}
	obj.style.display = 'block';
	document.getElementById('tlab' + o).style.fontWeight = 'bold';
	document.getElementById('tlab' + o).style.color = '#CDAD0F';
        document.getElementById('tlabbg' +o).src = 'img/atab.gif';

    }
    return;
}
