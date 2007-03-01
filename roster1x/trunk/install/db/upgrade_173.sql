#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### The roster version and db version MUST be last

DELETE FROM `renprefix_config` WHERE `id` = 1080 LIMIT 1;

INSERT INTO `renprefix_config` ( `id` , `config_name` , `config_value` , `form_type` , `config_type` )
  VALUES ('1050', 'default_page', 'members', 'function{pageNames', 'main_conf');


INSERT INTO `renprefix_config` ( `id` , `config_name` , `config_value` , `form_type` , `config_type` )
  VALUES ('4030', 'menu_member_page', '1', 'radio{on^1|off^0', 'menu_conf');

UPDATE `renprefix_config` SET `config_value` = '1.7.5' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '5' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;