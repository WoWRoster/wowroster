<?
//This is a test script Web <=> WoW for Shopping-Addon (WORKX:)

$request = '';

if (!isset ($_REQUEST)){
    echo "Kein REQUEST gesetzt!";
    }
else {
    $request = $_REQUEST;
     }
     
if ($_REQUEST['OPERATION']=="GETDATA"){
    sendData();
    }

function sendData(){
    //Later i´ll get the data from DB and format it uppon request
    echo "shoppinglist = {
	{ \"Hersteller\", 1, \"Was soll hergestellt werden?\", \"Wer soll´s bekommen\",},
	}";
    echo "Request1:".$_REQUEST['name1']."<br>".
	"Request2:".$_REQUEST['name2'];
}