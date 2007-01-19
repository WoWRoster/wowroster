#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

DELETE FROM `renprefix_config` WHERE `id` = '7010' LIMIT 1;

UPDATE `renprefix_config` SET `config_value` = '2.3.1' WHERE `id` = '1030' LIMIT 1;


# --------------------------------------------------------
### Players

ALTER TABLE `renprefix_players`
  DROP `Rankexp`,
  DROP `RankInfo`,
  DROP `RankName`,
  DROP `RankIcon`;


# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.7.3' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '5' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;
