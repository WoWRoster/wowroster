#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

DELETE FROM `renprefix_config` WHERE `id` = '7010' LIMIT 1;

UPDATE `renprefix_config` SET `config_value` = '2.3.1' WHERE `id` = '1030' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html|European French^http://www.wow-europe.com/fr/serverstatus/index.html|European Spanish^http://www.wow-europe.com/es/serverstatus/index.html' WHERE `id` = '8000' LIMIT 1;


# --------------------------------------------------------
### Players

ALTER TABLE `renprefix_players`
  DROP `Rankexp`,
  DROP `RankInfo`,
  DROP `RankName`,
  DROP `RankIcon`;


# --------------------------------------------------------
### Buffs

ALTER TABLE `renprefix_buffs`
  ADD PRIMARY KEY (`member_id`,`name`);


# --------------------------------------------------------
### Spell trees

ALTER TABLE `renprefix_spellbooktree`
  ADD PRIMARY KEY (`member_id`,`spell_type`);


# --------------------------------------------------------
### Spellbook

ALTER TABLE `renprefix_spellbook`
  ADD PRIMARY KEY (`member_id`,`spell_name`,`spell_rank`);


# --------------------------------------------------------
### Talent trees

ALTER TABLE `renprefix_talenttree`
  ADD PRIMARY KEY (`member_id`,`tree`);


# --------------------------------------------------------
### Talents

ALTER TABLE `renprefix_talents`
  ADD PRIMARY KEY (`member_id`,`tree`,`row`,`column`);


# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.7.3' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '5' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;
