--[[

    PvPLog 
        Author:            Andrzej Gorski 
	Based on Work by:  Josh Estelle, Daniel S. Reichenbach
        Version:           0.5.1
        Last Modified:     2006-07-25
]]

-- version information
VER_VENDOR = "wowroster.net";
VER_NUM = "0.5.1";

--Everything From here on would need to be translated and put
--into if statements for each specific language.

--***********
--ENGLISH (DEFAULT)
--***********

--startup messages
PVPLOG_STARTUP = "PvP Logger "..VER_NUM.." by "..VER_VENDOR.." AddOn loaded. Type /pl for options.";

--needed phrases
TO = " to ";
ON = "on";
OFF = "off";
NONE = "none";
RESET = "reset";
CONFIRM = "confirm";
ENABLE = "enable";
DISABLE = "disable";
UNKNOWN = "Unknown";
VER = "version";
VEN = "vendor";
USAGE = "Usage";

DMG = "damage";
ST = "stats";
STATS = "Statistics";
COMP = "completely";

PARTY = "Party";
GUILD = "Guild";
RAID = "Raid";
RACE = "race";
CLASS = "class";
ENEMY = "enemy";

WIN = "win";
LOSS = "loss";
WINS = "wins";
LOSSES = "losses";

DUEL = "Duel";
TOTAL = "Total";
STATS = "Statistics";
ALD = "Avg Level Diff";

NOTIFYKILLS = "notifykills";
NOTIFYDEATH = "notifydeath";

DLKB = "Death logged, killed by: ";
KL = "Kill logged: ";
DWLA = "Duel win logged against: ";
DLLA = "Duel loss logged against: ";

--gank levels
GL0 = "Fair Fighter";
GL_25 = "I Have No Mercy";
GL_20 = "Newb Masher";
GL_15 = "No Seriously, Get a Life";
GL_12 = "Get a Life";
GL_9 = "Gankity Gank Gank";
GL_6 = "Major Ganker";
GL_3 = "Minor Ganker";
GL8 = "I Gank GankMasters";
GL5 = "PvP God";
GL4 = "PvP Legend";
GL3 = "Ungankable";
GL2 = "Just try to gank me";
GL1 = "Difficult to Gank";

--default display text for notify
DEFAULT_KILL_TEXT = "I killed %n (Level %l %r %c) at [%x,%y] in %z (%w).";
DEFAULT_DEATH_TEXT = "%n (Level %l %r %c) killed me at [%x,%y] in %z (%w).";

--***********
-- GERMAN
--***********
if (GetLocale() == "deDE") then

--startup messages
PVPLOG_STARTUP = "PvP Logger "..VER_NUM.." by "..VER_VENDOR.." AddOn loaded. Type /pl for options.";

--needed phrases
TO = " to ";
ON = "on";
OFF = "off";
NONE = "none";
RESET = "reset";
CONFIRM = "confirm";
ENABLE = "enable";
DISABLE = "disable";
UNKNOWN = "Unknown";
VER = "version";
VEN = "vendor";
USAGE = "Usage";

DMG = "damage";
ST = "stats";
STATS = "Statistics";
COMP = "completely";

PARTY = "Party";
GUILD = "Guild";
RAID = "Raid";
RACE = "race";
CLASS = "class";
ENEMY = "enemy";

WIN = "win";
LOSS = "loss";
WINS = "wins";
LOSSES = "losses";

DUEL = "Duel";
TOTAL = "Total";
STATS = "Statistics";
ALD = "Avg Level Diff";

NOTIFYKILLS = "notifykills";
NOTIFYDEATH = "notifydeath";

DLKB = "Death logged, killed by: ";
KL = "Kill logged: ";
DWLA = "Duel win logged against: ";
DLLA = "Duel loss logged against: ";

--gank levels
GL0 = "Fair Fighter";
GL_25 = "I Have No Mercy";
GL_20 = "Newb Masher";
GL_15 = "No Seriously, Get a Life";
GL_12 = "Get a Life";
GL_9 = "Gankity Gank Gank";
GL_6 = "Major Ganker";
GL_3 = "Minor Ganker";
GL8 = "I Gank GankMasters";
GL5 = "PvP God";
GL4 = "PvP Legend";
GL3 = "Ungankable";
GL2 = "Just try to gank me";
GL1 = "Difficult to Gank";

--default display text for notify
DEFAULT_KILL_TEXT = "I killed %n (Level %l %r %c) at [%x,%y] in %z (%w).";
DEFAULT_DEATH_TEXT = "%n (Level %l %r %c) killed me at [%x,%y] in %z (%w).";

elseif (GetLocale() == "frFR") then
   --startup messages
   PVPLOG_STARTUP = "PvP Logger "..VER_NUM.." by "..VER_VENDOR.." AddOn loaded. Type /pl for options.";

   --needed phrases
   TO = " to ";
   ON = "on";
   OFF = "off";
   NONE = "none";
   RESET = "reset";
   CONFIRM = "confirm";
   ENABLE = "enable";
   DISABLE = "disable";
   UNKNOWN = "Unknown";
   VER = "version";
   VEN = "vendor";
   USAGE = "Usage";

   DMG = "damage";
   ST = "stats";
   STATS = "Statistics";
   COMP = "completely";
   
   PARTY = "Party";
   GUILD = "Guild";
   RAID = "Raid";
   RACE = "race";
   CLASS = "class";
   ENEMY = "enemy";
   
   WIN = "win";
   LOSS = "loss";
   WINS = "wins";
   LOSSES = "losses";
   
   DUEL = "Duel";
   TOTAL = "Total";
   STATS = "Statistics";
   ALD = "avg.level.diff";
   
   NOTIFYKILLS = "notifykills";
   NOTIFYDEATH = "notifydeath";
   
   DLKB = "Death logged, killed by: ";
   KL = "Kill logged: ";
   DWLA = "Duel win logged against: ";
   DLLA = "Duel loss logged against: ";
   
   --gank levels
   GL0 = "Fair Fighter";
   GL_25 = "I Have No Mercy";
   GL_20 = "Newb Masher";
   GL_15 = "No Seriously, Get a Life";
   GL_12 = "Get a Life";
   GL_9 = "Gankity Gank Gank";
   GL_6 = "Major Ganker";
   GL_3 = "Minor Ganker";
   GL8 = "I Gank GankMasters";
   GL5 = "PvP God";
   GL4 = "PvP Legend";
   GL3 = "Ungankable";
   GL2 = "Just try to gank me";
   GL1 = "Difficult to Gank";

   --default display text for notify
   DEFAULT_KILL_TEXT = "I killed %n (Level %l %r %c) at [%x,%y] in %z (%w).";
   DEFAULT_DEATH_TEXT = "%n (Level %l %r %c) killed me at [%x,%y] in %z (%w).";
end
