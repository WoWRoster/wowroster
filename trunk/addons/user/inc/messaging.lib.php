<?php
/** 
 * Dev.PKComp.net user Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage user Messaging Handler 
 */

if ( !defined('ROSTER_INSTALLED') ) 
{ 
    exit('Detected invalid access to this file!'); 
}
/*
	Sample:
	$user->message = new userMessaging();
	$user->message->SendMessage('Promotion','Today we have a brand new promotion from the pack number5',10,30,3);//user 10 sends a message to user 30 and have a level of 3
	$array = $user->message->GetAllMessages(0,30);
*/

class userMessaging
{
	var $err_msg;

	/*
	 *	@desc Class constructor
	 *	@param String $tblName
	 *	@return userMessaging	returns
	 */
	function userMessaging()
	{
		global $roster, $addon, $user;

		return;
	}
	
	/**
	* @return Int 0		Message sended succesfully
	* @return Int 1		No title
	* @return Int 2		No body
	* @return Int 3		Invalid Sender
	* @return Int 4		Invalid Receiver
	* @return Int 5		Invalid Sender Level
	* @return Int 6		Error inputing in Database
	*
	* @param String $title
	* @param String $body
	* @param Int $sender
	* @param Int $receiver
	*
	* @desc Send message to $receiver
	*/
	function SendMessage($title ,$body ,$sender ,$receiver ,$senderLevel)
	{
		global $roster, $addon, $user;

		if( strlen($title) == 0 )
		{
			$this->err_msg = 1;
		}
		if( strlen($body) == 0 )
		{
			$this->err_msg = 2;
		}
		if( strlen($sender) == 0 )
		{
			$this->err_msg = 3;
		}
		if( strlen($receiver) == 0 )
		{
			$this->err_msg = 4;
		}
		if( strlen($senderLevel) == 0)
		{
			$senderLevel = 0;
		}
		$result = $roster->db->query("INSERT INTO `".$roster->db->table('messaging',$addon['basename'])."` (title,body,sender,uid,senderLevel) VALUES ('$title','$body',$sender,$receiver,$senderLevel)");
		if($result)
		{
			$this->err_msg = 0;
		}
		else
		{
			return 6;
		}
	}
	/**
	* @return int 1		 When msgId equal to 0
	* @return int 2      When no Message in database with that msgId
	* @return String 	 Returns the title of the message
	* @param int $msgId
	* @desc This function is used to return the title of a especific message
	*/
	function GetTitle($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT `title` FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		return $row->title;
	}
	
	/**
	* @return int 1		When msgId equal to 0
	* @return int 2		When no Message in database with that msgId
	* @return String 	Returns the body of the message
	* @param int $msgId
	* @desc This function is used to return the body of a especific message
	*/
	function GetBody($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT `body` FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		return $row->body;
	}
	/**
	* @return int 1				When msgId equal to 0
	* @return int 2				When no Message in database with that msgId
	* @return int Other int 	Return the userId that sent this message
	* @param int $msgId
	* @desc This function is used to return the userId from the sender of a especific message
	*/
	function GetSenderID($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT `sender` FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		return $row->sender;
	}
	/**
	* @return int 1				When msgId equal to 0
	* @return int 2				When no Message in database with that msgId
	* @return int Other int 	Return the userId that is the receiver this message
	* @param int $msgId
	* @desc This function is used to return the userId from the receiver of a especific message
	*/
	function GetReceiverID($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT `uid` FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		return $row->receiver;
	}
	/**
	* @return int 1				When msgId equal to 0
	* @return int 2				When no Message in database with that msgId
	* @return int Other int 	Return the date when the message was sent
	* @param int $msgId
	* @desc This function is used to return the send date of a especific message
	*/
	function GetSendDate( $msgId )
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT `date` FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		return $row->date;
	}
	/**
	* @return int 1		When msgId equal to 0
	* @return int 2		When no Message in database with that msgId
	* @return array 	Returns the an array with all the fields of the table were messages are stored
	* @param int $msgId
	* @desc This function is used to return the message, and all is specifications
	*/
	function GetMessage($msgId)
	{
		global $roster, $addon, $user;

		$message = array();
		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("SELECT * FROM `".$user->db['message']."` WHERE `msgid`=$msgId");
		if($roster->db->num_rows($result) == 0)
		{
			$this->err_msg = 2;
		}
		$row = $roster->db->fetch($result);
		$message['receiver'] = $row['uid'];
		$message['sender'] = $row['sender'];
		$message['title'] = $row['title'];
		$message['body'] = $row['body'];
		$message['senderLevel'] = $row['senderLevel'];
		$message['read'] = $row['read'];
		$message['date'] = $row['date'];
		return $message;
	}
	
	/**
	* @return Int 0		Marked Readed succesfully
	* @return Int 1		When msgId equal to 0
	* @return Int 2		Error while updating database
	* @param unknown $msgId
	* @desc Marks the message has readed
	*/
	function markAsRead($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("UPDATE `".$roster->db->table('messaging','user')."` SET `read`='1' WHERE `msgid`='$msgId'");
		if($result)
		{
			return;
		}
		else 
		{
			$this->err_msg = 2;
		}
	}
	
	/**
	* @return Int 1			Error while collectiing info on database
	* @return Int 2			No messages with this settings
	* @return Array			Returns one array with all messages
	* 
	* @param Int $order		Can take values from 0 to 3(0-> Order By senderLevel Ascendent,
	*													1-> Order By senderLevel Descendent,
	*													2-> Order By readed message Ascendent,
	*													3-> Order By senderLevel Descendent)
	* @param Int $receiver
	* @param Int $sender
	*
	* @desc This function outputs all messages ordered by $order field and filtered by sender and/or receiver or none to display all messages
	*/
	function GetAllMessages($order = 5, $receiver = '', $sender = '')
	{
		global $roster, $addon, $user;

		switch( $order )
		{
			case 0:
				$order = '`senderLevel` ASC';
			case 1:
				$order = '`senderLevel` DESC';
			case 2:
				$order = '`read` ASC';
			case 3:
				$order = '`read` DESC';
			case 4:
				$order = '`date` ASC';
			case 5:
				$order = '`date` DESC';
		}
		$where = '';
		if(strlen($receiver) > 0 && strlen($sender) > 0)
		{
			$where = ' AND ';
		}
		
		$where = ((strlen($receiver) > 0)?'`uid`=' . $receiver:'') . $where . ((strlen($sender) > 0)?'`sender`=' . $sender:'');
		
		$result = $roster->db->query("SELECT * FROM `".$user->db['message']."` WHERE $where ORDER BY $order");
		
		if( !$result )
		{
			return 1;
		}
		$num = $roster->db->num_rows($result);
		$message = '';
		for($i = 0 ; $i < $num ; $i++ )
		{
			$row = $roster->db->fetch($result);
			$message[$i]['id'] = $row['msgid'];
			$message[$i]['reciever'] = $row['uid'];
			$message[$i]['sender'] = $row['sender'];
			$message[$i]['title'] = $row['title'];
			$message[$i]['senderLevel'] = $row['senderLevel'];
			$message[$i]['read'] = $row['read'];
			$message[$i]['date'] = $row['date'];
		}
		if( !is_array($message) )
		{
			$this->err_msg = 2;
		}
		return $message;	

	}


	/**
	* @return Int 0		Deleted succesfully
	* @return Int 1		When msgId equal to 0
	* @return Int 2		Error while deleting from database
	* @param Int $msgId
	* @desc Delete message
	*/
	function DeleteMessage($msgId)
	{
		global $roster, $addon, $user;

		if(strlen($msgId) == 0)
		{
			$this->err_msg = 1;
		}
		$result = $roster->db->query("DELETE FROM `".$user->db['message']."` WHERE `idMsg`=$msgId");
		if($result)
		{
			$this->err_msg = 0;
		}
		else
		{
			$this->err_msg = 2;
		}
	}

}
