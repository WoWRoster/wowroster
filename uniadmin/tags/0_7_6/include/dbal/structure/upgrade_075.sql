#
# MySQL UniAdmin Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Alter uniadmin_config

UPDATE `uniadmin_config` SET `config_value` = '0.7.6' WHERE `config_name` = 'UAVer' LIMIT 1;

INSERT INTO `uniadmin_config` ( `config_name` , `config_value` , `form_type` )
  VALUES ('remote_timeout', '24', 'text{10|10');

ALTER TABLE `uniadmin_config`  ORDER BY `config_name`;


# --------------------------------------------------------
### Alter uniadmin_settings

UPDATE `uniadmin_settings` SET `form_type` = 'password{250|50' WHERE `set_name` = 'ADDVARVAL2' LIMIT 1;


# --------------------------------------------------------
### Alter uniadmin_addons

ALTER TABLE `uniadmin_addons`
  ADD `full_path` TINYINT(1) NOT NULL DEFAULT '0';


# --------------------------------------------------------
### Alter uniadmin_logos

ALTER TABLE `uniadmin_logos` DROP `download_url`;
