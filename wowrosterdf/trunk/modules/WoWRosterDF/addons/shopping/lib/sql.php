<?php
/* 
* $Date: 2006/06/28 19:40:53 $ 
* $Revision: 0.4.2 $ 
*/ 
$sql_install = "
    CREATE TABLE `".$db_prefix."addon_shopping` (
        `order_number` int(255) NOT NULL auto_increment,
	`order_item` varchar(255) NOT NULL default '',
	`order_quantity` int(10) NOT NULL default '0',
	`order_maker` varchar(100) NOT NULL default '',
	`order_requester` varchar(100) NOT NULL default '',
	`order_maxprice` int(255) NOT NULL default '0',
	`order_note` varchar(255) NOT NULL default '',
	`order_price_demand` int(255) NOT NULL default '0',
	`order_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	`order_state` enum('none','outbox','send','info') NOT NULL default 'none',
	`order_info` varchar(255) default NULL,
	PRIMARY KEY  (`order_number`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";
				
?>