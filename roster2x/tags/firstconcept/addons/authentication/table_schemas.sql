# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_applications'
# 
CREATE TABLE `roster2_addon_auth_applications` (
  `application_id` int(11) NOT NULL default '0',
  `application_define_name` char(32) NOT NULL default '',
  UNIQUE KEY `applications_application_id_idx` (`application_id`),
  UNIQUE KEY `applications_define_name_i_idx` (`application_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_applications_seq'
# 
CREATE TABLE `roster2_addon_auth_applications_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_area_admin_areas'
# 
CREATE TABLE `roster2_addon_auth_area_admin_areas` (
  `area_id` int(11) NOT NULL default '0',
  `perm_user_id` int(11) NOT NULL default '0',
  UNIQUE KEY `area_admin_areas_id_i_idx` (`area_id`,`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_areas'
# 
CREATE TABLE `roster2_addon_auth_areas` (
  `area_id` int(11) NOT NULL default '0',
  `application_id` int(11) NOT NULL default '0',
  `area_define_name` char(32) NOT NULL default '',
  UNIQUE KEY `areas_area_id_idx` (`area_id`),
  UNIQUE KEY `areas_define_name_i_idx` (`application_id`,`area_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_areas_seq'
# 
CREATE TABLE `roster2_addon_auth_areas_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_group_subgroups'
# 
CREATE TABLE `roster2_addon_auth_group_subgroups` (
  `group_id` int(11) NOT NULL default '0',
  `subgroup_id` int(11) NOT NULL default '0',
  UNIQUE KEY `group_subgroups_id_i_idx` (`group_id`,`subgroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_grouprights'
# 
CREATE TABLE `roster2_addon_auth_grouprights` (
  `group_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  `right_level` int(11) NOT NULL default '3',
  UNIQUE KEY `grouprights_id_i_idx` (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_groups'
# 
CREATE TABLE `roster2_addon_auth_groups` (
  `group_id` int(11) NOT NULL default '0',
  `group_type` int(11) NOT NULL default '0',
  `group_define_name` char(32) NOT NULL default '',
  UNIQUE KEY `groups_group_id_idx` (`group_id`),
  UNIQUE KEY `groups_define_name_i_idx` (`group_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_groups_seq'
# 
CREATE TABLE `roster2_addon_auth_groups_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_groupusers'
# 
CREATE TABLE `roster2_addon_auth_groupusers` (
  `perm_user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  UNIQUE KEY `groupusers_id_i_idx` (`perm_user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_perm_users'
# 
CREATE TABLE `roster2_addon_auth_perm_users` (
  `perm_user_id` int(11) NOT NULL default '0',
  `auth_user_id` char(32) NOT NULL default '',
  `auth_container_name` char(32) NOT NULL default '',
  `perm_type` int(11) NOT NULL default '0',
  UNIQUE KEY `perm_users_perm_user_id_idx` (`perm_user_id`),
  UNIQUE KEY `perm_users_auth_id_i_idx` (`auth_user_id`,`auth_container_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_perm_users_seq'
# 
CREATE TABLE `roster2_addon_auth_perm_users_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_right_implied'
# 
CREATE TABLE `roster2_addon_auth_right_implied` (
  `right_id` int(11) NOT NULL default '0',
  `implied_right_id` int(11) NOT NULL default '0',
  UNIQUE KEY `right_implied_id_i_idx` (`right_id`,`implied_right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_rights'
# 
CREATE TABLE `roster2_addon_auth_rights` (
  `right_id` int(11) NOT NULL default '0',
  `area_id` int(11) NOT NULL default '0',
  `right_define_name` char(32) NOT NULL default '',
  `has_implied` tinyint(1) default '1',
  UNIQUE KEY `rights_right_id_idx` (`right_id`),
  UNIQUE KEY `rights_define_name_i_idx` (`area_id`,`right_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_rights_seq'
# 
CREATE TABLE `roster2_addon_auth_rights_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_translations'
# 
CREATE TABLE `roster2_addon_auth_translations` (
  `translation_id` int(11) NOT NULL default '0',
  `section_id` int(11) NOT NULL default '0',
  `section_type` int(11) NOT NULL default '0',
  `language_id` char(32) NOT NULL default '',
  `name` char(100) NOT NULL default '',
  `description` char(255) default NULL,
  UNIQUE KEY `translations_translation_i_idx` (`section_id`,`section_type`,`language_id`),
  UNIQUE KEY `translations_translation_id_idx` (`translation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_translations_seq'
# 
CREATE TABLE `roster2_addon_auth_translations_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_user_char_linktable'
# 
CREATE TABLE `roster2_addon_auth_user_char_linktable` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `auth_user_id` int(11) NOT NULL default '0',
  `status` varchar(100) NOT NULL default 'main',
  PRIMARY KEY  (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_userrights'
# 
CREATE TABLE `roster2_addon_auth_userrights` (
  `perm_user_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  `right_level` int(11) NOT NULL default '3',
  UNIQUE KEY `userrights_id_i_idx` (`perm_user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_users'
# 
CREATE TABLE `roster2_addon_auth_users` (
  `auth_user_id` int(11) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `last_login` datetime default '1970-01-01 00:00:00',
  `is_active` tinyint(1) default '1',
  `owner_user_id` int(11) default '0',
  `owner_group_id` int(11) default '0',
  `email` varchar(100) default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

# Host: localhost
# Database: roster2
# Table: 'roster2_addon_auth_users_seq'
# 
CREATE TABLE `roster2_addon_auth_users_seq` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 