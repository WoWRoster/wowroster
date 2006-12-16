#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

UPDATE `renprefix_config` SET `config_value` = '2.0.0' WHERE `id` = '1010' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '2.0.0' WHERE `id` = '1020' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '0.6.1' WHERE `id` = '1030' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '0'     WHERE `id` = '3140' LIMIT 1;



# --------------------------------------------------------
### Items Table

ALTER TABLE `renprefix_items`
  ADD `item_level` INT( 11 ) NULL,
  CHANGE `item_id` `item_id` VARCHAR( 64 ) NULL;



# --------------------------------------------------------
### Players Table

ALTER TABLE `renprefix_players`
  DROP `sessionDK`,
  DROP `yesterdayDK`,
  DROP `lastweekDK`,
  DROP `lifetimeDK`,
  DROP `lastweekHK`,
  DROP `lastweekContribution`,
  DROP `lastweekRank`,
  DROP `TWContribution`,
  DROP `TWHK`;

ALTER TABLE `renprefix_players`
  ADD `sessionCP` INT( 11 ) NOT NULL DEFAULT '0' AFTER `sessionHK`,
  ADD `lifetimeCP` INT( 11 ) NOT NULL DEFAULT '0' AFTER `lifetimeHK`,
  CHANGE `Rankexp` `Rankexp` FLOAT NOT NULL DEFAULT '0';



# --------------------------------------------------------
### Recipe Table

ALTER TABLE `renprefix_recipes` ADD `item_level` INT( 11 ) NULL ;



# --------------------------------------------------------
### Reputation Table

ALTER TABLE `renprefix_reputation`
  CHANGE `Standing` `Standing` VARCHAR( 32 ) NOT NULL DEFAULT '';

UPDATE `renprefix_reputation` SET `Value` = REPLACE(`Value`,'/',':');



# --------------------------------------------------------
### PvP2 Table

ALTER TABLE `renprefix_pvp2`
  ADD `realm` VARCHAR( 96 ) NOT NULL DEFAULT '' AFTER `guild` ;



# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.7.2' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '4' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;
