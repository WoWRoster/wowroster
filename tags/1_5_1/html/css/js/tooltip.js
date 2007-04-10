<!--

var curTip = "";

function displayToolTip(id) {
  if( curTip != "" ) {
    hideToolTip( curTip );
  }
  tip = document.getElementById( id );
  tip.style.display="block";
  tip.style.zIndex=1000;
  curTip = id;
}

function hideToolTip(id) {
  tip = document.getElementById( id );
  tip.style.display="none";
  curTip = "";
}

-->
