--[[
    PvPLog 
    Author:           Brad Morgan
    Based on Work by: Josh Estelle, Daniel S. Reichenbach, Andrzej Gorski, Matthew Musgrove
    Version:          2.4.0
    Last Modified:    2007-03-12
]]

-- Local variables
local variablesLoaded = false;
local initialized = false;

local realm = "";
local player = "";
local plevel = -1;
local mlevel = 70; -- Maximum player level
local dlevel = 11; -- Difference causing level of -1 to be returned

local softPL; -- soft PvPLog enable/disable

local bg_status;
local bg_mapName;
local bg_instanceId;
local bg_found = false;
local bg_indx = 0;

local isDuel = false;
local rank = "";
local fullrank = "";
local est_honor = 0;

local debug_indx;
local debug_simple = false; -- Overridden by PvPLogDebugFlag after VARIABLES_LOADED event.
local debug_ignore = true;  -- Overridden by PvPLogDebugIgnore after VARIABLES_LOADED event.

local lastDamagerToMe = "";
local foundDamaged = false;
local foundDamager = false;

local NUMTARGETS = 45;
local NUMRECENTS = 10;
local recentDamager = { };
local recentDamaged = { };
local ignoreList = { };
local ignoreRecords = { };

local MAXDEBUG = 2000;

local lastDing = -1000;

local RED     = "|cffbe0303";
local GREEN   = "|cff6bb700";
local BLUE    = "|cff0863c3";
local MAGENTA = "|cffa800a8";
local YELLOW  = "|cffffd505";
local CYAN    = "|cff00b1b1";
local WHITE   = "|cffdedede";
local ORANGE  = "|cffd06c01";
local PEACH   = "|cffdec962";
local FIRE    = "|cffde2413";

local dmgType = { };
local initDamage = false;
PVPLOG.VER_NUM = GetAddOnMetadata("PvPLog", "Version");
PVPLOG.VENDOR = "wowroster.net";
PVPLOG.URL = "http://www."..PVPLOG.VENDOR;

-- Called OnLoad of the add on
function PvPLogOnLoad()
    
    if (PVPLOG.VER_NUM) then
        PVPLOG.STARTUP = string.gsub( PVPLOG.STARTUP, "%%v", PVPLOG.VER_NUM );
    end
    if (PVPLOG.VENDOR) then
        PVPLOG.STARTUP = string.gsub( PVPLOG.STARTUP, "%%w", PVPLOG.VENDOR );
    end
    PvPLogChatMsgCyan(PVPLOG.STARTUP);

    -- respond to saved variable load
    this:RegisterEvent("VARIABLES_LOADED");

    -- respond to player entering the world
    this:RegisterEvent("PLAYER_ENTERING_WORLD");

    -- respond to player name update
    this:RegisterEvent("UNIT_NAME_UPDATE");

    -- respond when player dies
    this:RegisterEvent("PLAYER_DEAD"); 

    -- honor stuff
    this:RegisterEvent("CHAT_MSG_COMBAT_HONOR_GAIN");

    -- respond to when hostiles die
    this:RegisterEvent("CHAT_MSG_COMBAT_HOSTILE_DEATH"); 

    -- respond when our target changes
    this:RegisterEvent("PLAYER_TARGET_CHANGED");

    -- respond to when you change mouseovers
    this:RegisterEvent("UPDATE_MOUSEOVER_UNIT");

    -- keep track of players level
    this:RegisterEvent("PLAYER_LEVEL_UP");

    -- flags damage
    this:RegisterEvent("CHAT_MSG_COMBAT_HOSTILEPLAYER_HITS");
    this:RegisterEvent("CHAT_MSG_COMBAT_PET_HITS");
    this:RegisterEvent("CHAT_MSG_COMBAT_SELF_HITS");
    this:RegisterEvent("CHAT_MSG_SPELL_DAMAGESHIELD_ON_SELF");
    this:RegisterEvent("CHAT_MSG_SPELL_HOSTILEPLAYER_DAMAGE");
    this:RegisterEvent("CHAT_MSG_SPELL_PET_DAMAGE");
    this:RegisterEvent("CHAT_MSG_SPELL_SELF_DAMAGE");
    this:RegisterEvent("CHAT_MSG_SPELL_PERIODIC_HOSTILEPLAYER_DAMAGE");
    this:RegisterEvent("CHAT_MSG_SPELL_PERIODIC_CREATURE_DAMAGE");
    this:RegisterEvent("CHAT_MSG_SPELL_PERIODIC_SELF_DAMAGE");

    -- enters/leaves combat (for DPS)
--  this:RegisterEvent("PLAYER_REGEN_ENABLED");
    this:RegisterEvent("PLAYER_REGEN_DISABLED");
    this:RegisterEvent("UNIT_HEALTH");

    -- testing
    --this:RegisterEvent("PLAYER_PVP_KILLS_CHANGED");
end

function PvPLog_MiniMap_LeftClick()
    if (PvPLogData[realm][player].MiniMap.stats == 1) then
        PvPLogStatsHide();
    else
        PvPLogStatsShow();
    end
end
 
function PvPLog_MiniMap_RightClick()
    if (PvPLogData[realm][player].MiniMap.config == 1) then
        PvPLogConfigHide();
    else
        PvPLogConfigShow();
    end
end


function PvPLog_RegisterWithAddonManagers()
    -- Based on MobInfo2's MI_RegisterWithAddonManagers
    -- register with myAddons manager
    if ( myAddOnsFrame_Register ) then
        local PvPLogDetails = {
            name = "PvPLog",
            version = PVPLOG.VER_NUM,
            author = "Andrzej Gorski",
            website = PVPLOG.URL,
            category = MYADDONS_CATEGORY_OTHERS,
            optionsframe = "PvPLogConfigFrame"
        };
        myAddOnsFrame_Register( PvPLogDetails );
    end

    -- register with EARTH manager (mainly for Cosmos support)
    if EarthFeature_AddButton then
        EarthFeature_AddButton(
            {
                id = "PvPLog",
                name = "PvPLog",
                subtext = "v"..PVPLOG.VER_NUM,
                tooltip = PVPLOG.DESCRIPTION,
                icon = PvPLogGetFactionIcon(),
                callback = function(state) PvPLog_MiniMap_RightClick() end,
                test = nil
            }
        )
    
    -- register with KHAOS (only if EARTH not found)
    elseif Khaos then
        Khaos.registerOptionSet(
            "other",
            {
                id = "PvPLogOptionSet",
                text = "PvPLog",
                helptext = PVPLOG.DESCRIPTION,
                difficulty = 1,
                callback = function(state) end,
                default = true,
                options = {
                    {
                        id = "PvPLogOptionsHeader",
                        type = K_HEADER,
                        difficulty = 1,
                        text = "PvPLog v"..PVPLOG.VER_NUM,
                        helptext = PVPLOG.DESCRIPTION
                    },
                    {
                        id = "MobInfo2OptionsButton",
                        type = K_BUTTON,
                        difficulty = 1,
                        text = "PvPLog "..PVPLOG.UI_CONFIG,
                        helptext = "",
                        callback = function(state) PvPLog_MiniMap_RightClick() end,
                        feedback = function(state) end,
                        setup = { buttonText = PVPLOG.UI_OPEN }
                    }
                }
            }
        )
    end
end  -- PvPLog_RegisterWithAddonManagers()

function PvPLogMinimapButtonInit()
    local info = { };
    info.position = -45; -- default only. after first use, SavedVariables used
    info.drag = "CIRCLE"; -- default only. after first use, SavedVariables used
    info.tooltip = PVPLOG.UI_RIGHT_CLICK .. PVPLOG.UI_TOGGLE .."\n".. PVPLOG.UI_LEFT_CLICK .. PVPLOG.UI_TOGGLE2;
    info.enabled = 1; -- default only. after first use, SavedVariables used
    info.config = 0;
    info.stats = 0;
    info.icon = PvPLogGetFactionIcon();
    return info;
end

function PvPLogCreateMinimapButton()
    local info = PvPLogMinimapButtonInit();
    MyMinimapButton:Create("PvPLog", PvPLogData[realm][player].MiniMap, info);
    MyMinimapButton:SetRightClick("PvPLog", PvPLog_MiniMap_RightClick);
    MyMinimapButton:SetLeftClick("PvPLog", PvPLog_MiniMap_LeftClick);
end

function PvPLogOnEvent()   
    -- PvPLogDebugAdd("Event: "..event);
    -- loads and initializes our variables
    if (event == "VARIABLES_LOADED") then
        variablesLoaded = true;
        if (PvPLogDebugFlag == nil) then
            PvPLogDebugFlag = false;
        else
            debug_simple = PvPLogDebugFlag; -- Manually set to true if you want to always debug.
        end
        if (PvPLogDebugIgnore == nil) then
            PvPLogDebugIgnore = true;
        else
            debug_ignore = PvPLogDebugIgnore; -- Manually set to false if you want to not ignore anything.
        end
        PvPLog_RegisterWithAddonManagers();
        
    -- initialize when entering world
    elseif (event == "PLAYER_ENTERING_WORLD") then
        PvPLogInitialize();
        local bg_found = false;
        local x, y = GetPlayerMapPosition("player");
        if ((x == 0) and (y == 0)) then
            SetMapToCurrentZone();
            x, y = GetPlayerMapPosition("player");
        end    
        -- Determines whether we are in an Instance or not 
        if (x == 0 and y == 0) then -- inside instance
        -- Check if the Instance is a Battleground
            if (PvPLogInBG()) then
                softPL = true;
            else
                softPL = false;
            end
        else
            softPL = true;
        end
        PvPLogCreateMinimapButton();

    -- initialize when name changes
    elseif (event == "UNIT_NAME_UPDATE") then
        player = UnitName("player");
        plevel = UnitLevel("player");

    -- keep track of players level
    elseif (event == "PLAYER_LEVEL_UP") then
        plevel = UnitLevel("player");

    elseif (event == "PLAYER_DEAD") then
        -- initialize if we're not for some reason
        if (not initialized) then
            PvPLogInitialize();
        end
      
        -- make sure we have a last damager
        -- and are enabled
        if (lastDamagerToMe == "" or
          not PvPLogData[realm][player].enabled or not softPL) then
            return;
        end
     
        -- search in player list
        local found = false;
        table.foreach(recentDamager,
            function(i,tname)
                if (tname == lastDamagerToMe) then
                    found = true;
                    return true;
                end
            end
        );
        if (found) then
            if (targetRecords[lastDamagerToMe]) then
                if (targetRecords[lastDamagerToMe].level) then
                    v = targetRecords[lastDamagerToMe];
                    PvPLogChatMsgCyan("PvP "..PVPLOG.DLKB..RED..lastDamagerToMe);
                    PvPLogRecord(lastDamagerToMe, v.level, v.race, v.class, v.guild, 1, 0, v.rank, v.realm);
                else
                    PvPLogDebugMsg("Empty targetRecords for: "..lastDamagerToMe, RED);
                end
            else
                PvPLogDebugMsg("No targetRecords for: "..lastDamagerToMe, RED);
            end
        else
            PvPLogDebugMsg("No recentDamager for: "..lastDamagerToMe, RED);
        end

        -- we are dead, clear the variables
        PvPLogDebugMsg('Recents cleared (dead).');
        -- recentDamaged = { }; -- Deferred because some of them may still die.
        recentDamager = { };
        lastDamagerToMe = "";

    -- add record to mouseover
    elseif (event == "UPDATE_MOUSEOVER_UNIT") then
        -- initialize if we're not for some reason
        if (not initialized) then
            PvPLogInitialize();
        end

        if (not PvPLogData[realm][player].enabled or not softPL) then
            return;
        end

        -- adds record to mouseover if it exists (and mouseover enabled)
        if (PvPLogData[realm][player].mouseover) then

            if (UnitExists("mouseover")) then
                local total = PvPLogGetPvPTotals(UnitName("mouseover"));
                local guildTotal = PvPLogGetGuildTotals(GetGuildInfo("mouseover"));

                if (total and (total.wins > 0 or total.loss > 0)) then
                    if (not UnitIsFriend("mouseover", "player")) then 
                        GameTooltip:AddLine(CYAN .. PVPLOG.UI_PVP .. ": " .. GREEN .. total.wins .. 
                             CYAN .. " / " .. RED .. total.loss, 
                             1.0, 1.0, 1.0, 0);
                    else
                        GameTooltip:AddLine(CYAN .. PVPLOG.DUEL .. ": " .. GREEN .. total.wins .. 
                             CYAN.." / " .. RED .. total.loss, 
                             1.0, 1.0, 1.0, 0);
                    end
                    GameTooltip:SetHeight(GameTooltip:GetHeight() + 
                              GameTooltip:GetHeight() / 
                                 GameTooltip:NumLines());
                end

                if (guildTotal and (guildTotal.wins > 0 or guildTotal.loss > 0) and
                  (not total or total.wins ~= guildTotal.wins or 
                  total.loss ~= guildTotal.loss)) then
                    if (not UnitIsFriend("mouseover", "player")) then 
                        GameTooltip:AddLine(CYAN .. PVPLOG.GUILD .. " "..PVPLOG.UI_PVP..": " .. GREEN .. 
                               guildTotal.wins .. 
                               CYAN .. " / " .. RED .. guildTotal.loss, 
                               1.0, 1.0, 1.0, 0);
                    else
                        GameTooltip:AddLine(CYAN .. PVPLOG.GUILD.." "..PVPLOG.DUEL..": " .. GREEN .. 
                               guildTotal.wins .. 
                               CYAN .. " / " ..  RED .. guildTotal.loss, 
                               1.0, 1.0, 1.0, 0);
                    end
                    GameTooltip:SetHeight(GameTooltip:GetHeight() + 
                        GameTooltip:GetHeight() / 
                        GameTooltip:NumLines());
                end

                if (lastDing <= GetTime()-PvPLogData[realm][player].dingTimeout and
                  not UnitInParty("mouseover") and UnitIsPlayer("mouseover") and
                  ((total and (total.wins > 0 or total.loss > 0)) or
                  (guildTotal and (guildTotal.wins > 0 or guildTotal.loss > 0)))) then
                    local msg = "PvP Record: ";
                    if (total and (total.wins > 0 or total.loss > 0)) then
                        msg = msg .. total.wins.. " / " .. total.loss;
                    end
                    if (guildTotal and (guildTotal.wins > 0 or guildTotal.loss > 0)) then
                        msg = msg .. "  Guild Record: "..guildTotal.wins.. " / "
                        .. guildTotal.loss;
                    end
                    PvPLogFloatMsg(msg, "fire");

                    msg = UnitName("mouseover") ..
                        " -- [" .. UnitLevel("mouseover") .. "] " .. 
                        UnitRace("mouseover") .. " " .. UnitClass("mouseover");
                    if (GetGuildInfo("mouseover")) then
                        msg = msg .. " of <" .. GetGuildInfo("mouseover") .. ">";
                    end
                    PvPLogFloatMsg(msg, "peach");
                    if (PvPLogData[realm][player].ding) then
                        PlaySound(PvPLogData[realm][player].dingSound);
                    end
                    lastDing = GetTime();
                end
            end
        end

    -- keep track of those we've targeted
    elseif (event == "PLAYER_TARGET_CHANGED") then
        -- initialize if we're not for some reason
        if (not initialized) then
            PvPLogInitialize();
        end

        local field = getglobal("PvPLogTargetText");
        field:Hide();
        field:SetText("");

        -- if we're enabled
        if (PvPLogData[realm][player].enabled and softPL) then
            PvPLogUpdateTarget(isDuel);
        end

    -- record a hostile death, if we killed them
    elseif (event == "CHAT_MSG_COMBAT_HOSTILE_DEATH") then
        -- initialize if we're not for some reason
        if (not initialized) then
            PvPLogInitialize();
        end

        -- if we're enabled
        if (PvPLogData[realm][player].enabled and softPL) then
            PvPLogDebugAdd(arg1);
            MarsMessageParser_ParseMessage("PvPLog_PlayerDeath", arg1);  
        end
    elseif (event == "CHAT_MSG_COMBAT_HONOR_GAIN") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (PvPLogData[realm][player].enabled) then
                MarsMessageParser_ParseMessage("PvPLog_HonorGain", arg1);
            end
        end
    elseif (event == "CHAT_MSG_COMBAT_HOSTILEPLAYER_HITS") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLog_damageMe(arg1);
            end
        end
    elseif (event == "CHAT_MSG_COMBAT_SELF_HITS") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_COMBAT_PET_HITS") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLogDebugAdd(arg1.." (Ignored)");
            end
        end
    elseif (event == "CHAT_MSG_SPELL_DAMAGESHIELD_ON_SELF") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_SELF_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PET_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLogDebugAdd(arg1.." (Ignored)");
            end
        end
    elseif (event == "CHAT_MSG_SPELL_HOSTILEPLAYER_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                PvPLog_damageMe(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PERIODIC_SELF_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                -- PvPLogDebugMsg("Time: "..GetTime(), ORANGE);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PERIODIC_HOSTILEPLAYER_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                -- PvPLogDebugMsg("Time: "..GetTime(), ORANGE);
                PvPLog_damageBoth(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PERIODIC_CREATURE_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg("Event: "..event, GREEN);
                -- PvPLogDebugMsg("Msg: "..arg1, FIRE);
                -- PvPLogDebugMsg("Time: "..GetTime(), ORANGE);
                PvPLog_damageBoth(arg1);
            end
        end
    elseif (event == "PLAYER_REGEN_DISABLED") then
        PvPLogStatsFrame:Hide();
        PvPLogConfigHide();
--  elseif (event == "PLAYER_REGEN_ENABLED") then
    elseif (event == "UNIT_HEALTH") then
        if (not UnitAffectingCombat("player") and UnitHealth("player") == UnitHealthMax("player")) then
            if ((recentDamager and table.getn(recentDamager) > 0) or lastDamagerToMe ~= "") then
                PvPLogDebugMsg('Recents cleared (healthy).');
                recentDamager = { };
                lastDamagerToMe = "";
            end
            if (recentDamaged and table.getn(recentDamaged) > 0) then 
                table.remove(recentDamaged,1);
            end
        end
    end
end

-- Print Functions
function PvPLogPrintDamage()
    if (not initDamage) then
        PvPLogInitDamage();
    end

    local physAvg = (dmgType.dmg_physical.norm + 
        dmgType.dmg_physical.crit)/dmgType.dmg_physical.count;
    local fireAvg = (dmgType.dmg_fire.norm + dmgType.dmg_fire.crit)/
        dmgType.dmg_fire.count;
    local frostAvg = (dmgType.dmg_frost.norm + dmgType.dmg_frost.crit)/
        dmgType.dmg_frost.count;
    local natureAvg = (dmgType.dmg_nature.norm + dmgType.dmg_nature.crit)/
        dmgType.dmg_nature.count;
    local shadowAvg = (dmgType.dmg_shadow.norm + dmgType.dmg_shadow.crit)/
        dmgType.dmg_shadow.count;
    local arcaneAvg = (dmgType.dmg_arcane.norm + dmgType.dmg_arcane.crit)/
        dmgType.dmg_arcane.count;

    PvPLogDebugMsg("Physical Avg: " .. physAvg, CYAN);
    PvPLogDebugMsg("Fire Avg: " .. fireAvg, BLUE);
    PvPLogDebugMsg("Frost Avg: " .. frostAvg, MAGENTA);
    PvPLogDebugMsg("Nature Avg: " .. natureAvg, CYAN);
    PvPLogDebugMsg("Shadow Avg: " .. shadowAvg, BLUE);
    PvPLogDebugMsg("Arcane Avg: " .. arcaneAvg, MAGENTA);
end

function PvPLogPrintStats()
    local stats = PvPLogGetStats();
--[[
    local gankLevel = PVPLOG.GL0;
    if (stats.pvpWinAvgLevelDiff <= -25) then
        gankLevel = PVPLOG.GL_25;
    elseif (stats.pvpWinAvgLevelDiff <= -20) then
        gankLevel = PVPLOG.GL_20;
    elseif (stats.pvpWinAvgLevelDiff <= -15) then
        gankLevel = PVPLOG.GL_15;
    elseif (stats.pvpWinAvgLevelDiff <= -12) then
        gankLevel = PVPLOG.GL_12;
    elseif (stats.pvpWinAvgLevelDiff <= -9) then
        gankLevel = PVPLOG.GL_9;
    elseif (stats.pvpWinAvgLevelDiff <= -6) then
        gankLevel = PVPLOG.GL_6;
    elseif (stats.pvpWinAvgLevelDiff <= -3) then
        gankLevel = PVPLOG.GL_3;
    elseif (stats.pvpWinAvgLevelDiff >= 8) then
        gankLevel = PVPLOG.GL8;
    elseif (stats.pvpWinAvgLevelDiff >= 5) then
        gankLevel = PVPLOG.GL5;
    elseif (stats.pvpWinAvgLevelDiff >= 4) then
        gankLevel = PVPLOG.GL4;
    elseif (stats.pvpWinAvgLevelDiff >= 3) then
        gankLevel = PVPLOG.GL3;
    elseif (stats.pvpWinAvgLevelDiff >= 2) then
        gankLevel = PVPLOG.GL2;
    elseif (stats.pvpWinAvgLevelDiff >= 1) then
        gankLevel = PVPLOG.GL1;
    end
]]--
    PvPLogChatMsgCyan("PvPLog " .. PVPLOG.STATS .. ":");
    PvPLogChatMsg(MAGENTA.."   "..PVPLOG.TOTAL.." "..PVPLOG.WINS..":     ".. stats.totalWins ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.totalWinAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(MAGENTA.."   "..PVPLOG.TOTAL.." "..PVPLOG.LOSSES..":  ".. stats.totalLoss ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.totalLossAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(ORANGE .. "    "..PVPLOG.UI_PVP.." "..PVPLOG.WINS..":     ".. stats.pvpWins ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.pvpWinAvgLevelDiff*100)/100)..")");
--      " ("..PVPLOG.ALD..": "..(math.floor(stats.pvpWinAvgLevelDiff*100)/100)..", " .. gankLevel .. ")");

    PvPLogChatMsg(ORANGE .. "    "..PVPLOG.UI_PVP.." "..PVPLOG.LOSSES..":  ".. stats.pvpLoss ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.pvpLossAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(GREEN .. "    "..PVPLOG.DUEL.." "..PVPLOG.WINS..":    ".. stats.duelWins ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.duelWinAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(GREEN .. "    "..PVPLOG.DUEL.." "..PVPLOG.LOSSES..": ".. stats.duelLoss ..
        " ("..PVPLOG.ALD..": "..(math.floor(stats.duelLossAvgLevelDiff*100)/100)..")");
end

function PvPLogDebugMsg(msg, color)
    if (debug_simple) then
        if (color) then
            PvPLogChatMsg('PvPLog: ' .. color .. msg);
        else
            PvPLogChatMsg('PvPLog: ' .. msg);
        end
        PvPLogDebugAdd(msg);
    end
end

function PvPLogDebugAdd(msg)
    if (debug_simple) then
        table.insert(PvPLogDebug,date()..": "..msg);
        if (table.getn(PvPLogDebug) > MAXDEBUG) then
            table.remove(PvPLogDebug,1);
        end
    end
end

--[[
function PvPLogDebugMsg(msg, color)
    -- will print to chatFrame that listens to PvPDebug Channel as the only chan
    if (debug_indx == nil) then
        local number = 1;
        for i = 2, 7 do
            local name1, zone1 = GetChatWindowChannels(i);
            if (name1 ~= nil) then
                if (string.lower(name1) == "pvpdebug") then
                   number = i;
                   PvPLogChatMsg(FIRE.."Found Debug Channel for PvPLog at: "..number);
                   break;
                end
            end
        end
        debug_indx = getglobal("ChatFrame"..number);
    end
    if (color) then
        debug_indx:AddMessage(color..msg);
    else
        debug_indx:AddMessage(msg);
    end
end
]]--

function PvPLogChatMsg(msg)
    if (DEFAULT_CHAT_FRAME) then
        DEFAULT_CHAT_FRAME:AddMessage(msg);
    end
end

function PvPLogFloatMsg(msg, color)
    -- Display overhead message.  7 basic colors available
    -- Use at most 3 lines here - the rest get lost
    if (not PvPLogData[realm][player].display) then
        return;
    end
    local r, g, b
    if (color == nil) then 
        color = "white";
    end
    if (string.lower(color) == "red") then
        r, g, b = 190/255, 3/255, 3/255;
    elseif (string.lower(color) == "green") then
        r, g, b = 107/255, 183/255, 0.0;
    elseif (string.lower(color) == "blue") then
        r, g, b = 8/255, 99/255, 195/255;
    elseif (string.lower(color) == "magenta") then
        r, g, b = 168/255, 0.0, 168/255;
    elseif (string.lower(color) == "yellow") then
        r, g, b = 1.0, 213/255, 5/255;
    elseif (string.lower(color) == "cyan") then
        r, g, b = 0.0, 177/255, 177/255;
    elseif (string.lower(color) == "orange") then
        r, g, b = 208/255, 108/255, 0.0;
    elseif (string.lower(color) == "peach") then
        r, g, b = 222/255, 201/255, 98/255;
    elseif (string.lower(color) == "fire") then
        r, g, b = 222/255, 36/255, 19/255;
    else 
        r, g, b = 1.0, 1.0, 1.0;
    end
    UIErrorsFrame:AddMessage(msg, r, g, b, 1.0, UIERRORS_HOLD_TIME);
end

function PvPLogDuelStart(seconds)
    PvPLogDebugMsg("Starting duel", ORANGE);
    isDuel = true;
end

function PvPLogDuel(parseWinner, parseLoser)
    PvPLogDebugMsg("Processing duel: "..parseWinner..", "..parseLoser, ORANGE);
    if (parseWinner and parseLoser) then
        if (UnitName("player") == parseWinner) then
            PvPLogUpdateTarget(isDuel);
            if (targetRecords[parseLoser]) then
                if (targetRecords[parseLoser].level) then
                    v = targetRecords[parseLoser];
                    PvPLogChatMsgCyan(PVPLOG.DWLA..GREEN..parseLoser);
                    PvPLogRecord(parseLoser, v.level, v.race, v.class, v.guild, 0, 1, v.rank);
                else
                    PvPLogDebugMsg("Empty targetRecords for: "..parseLoser, RED);
                end
            else
                PvPLogDebugMsg("No targetRecords for: "..parseLoser, RED);
            end
        elseif (UnitName("player") == parseLoser) then
            if (targetRecords[parseWinner]) then
                PvPLogUpdateTarget(isDuel);
                if (targetRecords[parseWinner].level) then
                    v = targetRecords[parseWinner];
                    PvPLogChatMsgCyan(PVPLOG.DLLA..GREEN..parseWinner);
                    PvPLogRecord(parseWinner, v.level, v.race, v.class, v.guild, 0, 0, v.rank);
                else
                    PvPLogDebugMsg("Empty targetRecords for: "..parseWinner, RED);
                end
            else
                PvPLogDebugMsg("No targetRecords for: "..parseWinner, RED);
            end
        end
        isDuel = false;
    end
end

function PvPLogPlayerDeath(parseName)
    -- if we have a name
    if (parseName) then
        PvPLogDebugMsg("Processing death of: "..parseName, YELLOW);
        local found = false;
        local index = 0;
        table.foreach(recentDamaged,
            function(i,tname)
                if (tname == parseName) then
                    found = true;
                    index = i;
                    return found;
                end
            end
        );
        if (found) then
            table.remove(recentDamaged,index); -- We can't take credit for their future deaths.
            if (targetRecords[parseName]) then
                if (targetRecords[parseName].level) then
                    v = targetRecords[parseName];
                    PvPLogChatMsgCyan(PVPLOG.KL  .. GREEN .. parseName);
                    PvPLogRecord(parseName, v.level, v.race, v.class, v.guild, 1, 1, v.rank, v.realm);
                    if (parseName == lastDamagerToMe) then
                        lastDamagerToMe = "";
                    end
                else
                    PvPLogDebugMsg("Empty targetRecords for: "..parseName, RED);
                end
            else
                PvPLogDebugMsg("No targetRecords for: "..parseName, RED);
            end
        else
            PvPLogDebugMsg("No recentDamaged for: "..parseName, RED);
        end
    end
end

function PvPLogSetHonor(parseKilled, parseRank, parseHonor)
    fullrank = "";
    est_honor = 0;
    if (parseKilled) then
        table.foreach(recentDamaged,
            function(i, tname)
                if (tname == parseKilled) then
                    fullrank = parseRank;
                    est_honor = parseHonor;
                    return true;
                end
            end
        );
    end
end

function PvPLogPutInTable(tab, nam)
    local exists = false;
    table.foreach(tab,
        function(i,tar)
            if (tar == nam) then
                exists = true;
                return exists;
            end
        end
    );
    if (not exists) then
        table.insert(tab, nam);
        if (table.getn(tab) > NUMRECENTS) then
           table.remove(tab,1);
        end
    end
    return exists;
end

function PvPLogMyDamage(res1, res2, res3, res4, res5, res6)
    -- PvPLogDebugMsg("PvPLogMyDamage("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..", "..tostring(res5)..", "..tostring(res6)..")");
    if (res1) then
        if ((isDuel or not ignoreRecords[res1]) and not targetRecords[res1]) then
            PvPLogDebugMsg("Damaged Targets Addition: "..res1, RED);
            targetRecords[res1] = { };
            table.insert(targetList, res1);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res1]) then
            PvPLogDebugMsg("recentDamaged(1): "..res1, ORANGE);
            if (not PvPLogPutInTable(recentDamaged, res1)) then
                PvPLogDebugMsg("recentDamaged(1): "..res1, ORANGE);
            end
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageSecond(res1, res2, res3, res4, res5, res6)
    -- PvPLogDebugMsg("PvPLogMyDamageSecond("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..", "..tostring(res5)..", "..tostring(res6)..")");
    if (res2) then
        if ((isDuel or not ignoreRecords[res2]) and not targetRecords[res2]) then
            PvPLogDebugMsg("Damaged Targets Addition: "..res2, RED);
            targetRecords[res2] = { };
            table.insert(targetList, res2);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res2]) then
            if (not PvPLogPutInTable(recentDamaged, res2)) then
                PvPLogDebugMsg("recentDamaged(2): "..res2, ORANGE);
            end
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageThird(res1, res2, res3, res4, res5, res6)
    -- PvPLogDebugMsg("PvPLogMyDamageThird("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..", "..tostring(res5)..", "..tostring(res6)..")");
    if (res3) then
        if ((isDuel or not ignoreRecords[res3]) and not targetRecords[res3]) then
            PvPLogDebugMsg("Damaged Targets Addition: "..res3, RED);
            targetRecords[res3] = { };
            table.insert(targetList, res3);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res3]) then
            if (not PvPLogPutInTable(recentDamaged, res3)) then
                PvPLogDebugMsg("recentDamaged(3): "..res3, ORANGE);
            end
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageFourth(res1, res2, res3, res4, res5, res6)
    -- PvPLogDebugMsg("PvPLogMyDamageFourth("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..", "..tostring(res5)..", "..tostring(res6)..")");
    if (res4) then
        if ((isDuel or not ignoreRecords[res4]) and not targetRecords[res4]) then
            PvPLogDebugMsg("Damaged Targets Addition: "..res4, RED);
            targetRecords[res4] = { };
            table.insert(targetList, res4);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res4]) then
            if (not PvPLogPutInTable(recentDamaged, res4)) then
                PvPLogDebugMsg("recentDamaged(4): "..res4, ORANGE);
            end
            foundDamaged = true;
        end
    end
end

function PvPLogDamageMe(res1, res2, res3, res4, res5, res6, res7)
    -- PvPLogDebugMsg("PvPLogDamageMe("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..", "..tostring(res5)..", "..tostring(res6)..")");
    if (res1) then
        if ((isDuel or not ignoreRecords[res1]) and not targetRecords[res1]) then
            PvPLogDebugMsg("Recent Targets Addition: "..res1, RED);
            targetRecords[res1] = { };
            table.insert(targetList, res1);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res1]) then
            if (not PvPLogPutInTable(recentDamager, res1)) then
                PvPLogDebugMsg("recentDamager(1): "..res1, ORANGE);
            end
            lastDamagerToMe = res1;
            foundDamager = true;
        end
    end
end

-- PERIODICAURADAMAGEOTHERSELF = "You suffer %d %s damage from %s's %s."; 
-- You suffer 3 frost damage from Rabbit's Ice Nova.
function PvPLogDamageMeAura(res1, res2, res3, res4)
    -- PvPLogDebugMsg("PvPLogDamageMeAura("..tostring(res1)..", "..tostring(res2)..", "..tostring(res3)..", "..tostring(res4)..")");
    if (res3) then
        if ((isDuel or not ignoreRecords[res3]) and not targetRecords[res3]) then
            PvPLogDebugMsg("Recent Targets Addition: "..res3, RED);
            targetRecords[res3] = { };
            table.insert(targetList, res3);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res3]) then
            if (not PvPLogPutInTable(recentDamager, res3)) then
                PvPLogDebugMsg("recentDamager(3): "..res3, ORANGE);
            end
            lastDamagerToMe = res3;
            foundDamager = true;
        end
    end
end

function PvPLog_myDamage(msg)
    PvPLogDebugAdd(msg);
    foundDamaged = false;
    MarsMessageParser_ParseMessage("PvPLog_MyDamage", msg); 
    if (foundDamaged) then
        return true;
    end
    return false;
end

function PvPLog_damageMe(msg)
    PvPLogDebugAdd(msg);
    foundDamager = false;
    MarsMessageParser_ParseMessage("PvPLog_DamageMe", msg);
    if (foundDamager) then
        return true;
    end
    return false;
end

function PvPLog_damageBoth(msg)
    PvPLogDebugAdd(msg);
    foundDamager = false;
    foundDamaged = false;
    MarsMessageParser_ParseMessage("PvPLog_DamageBoth", msg);
    if (foundDamager or foundDamaged) then
        return true;
    end
    return false;
end

function PvPLogGetRank(parseRank, parseName)
    if (parseRank) then
        fullrank = parseRank;
    else
        fullrank = "";
    end
end

function PvPLogInitialize()   
    -- get realm and player
    realm = GetCVar("realmName");
    player = UnitName("player");
    plevel = UnitLevel("player");

    -- check for valid realm and player
    if (initialized or (not variablesLoaded) or (not realm) or 
        (not plevel) or (not player)) then
        return;
    end

    debug_indx = nil;
    isDuel = false;

    -- Hook the ChatFrame_OnEvent function
    hooksecurefunc("ChatFrame_OnEvent",PvPLog_ChatFrame_OnEvent)

    -- *** Mars Message Parser Registers ***
    -- COMBATLOG_HONORGAIN = "%s dies, honorable kill Rank: %s (Estimated Honor Points: %d)";
    MarsMessageParser_RegisterFunction("PvPLog_HonorGain", COMBATLOG_HONORGAIN,
                      PvPLogSetHonor);

    -- DUEL_COUNTDOWN = "Duel starting: %d"; -- %d is the number of seconds until the beginning of the duel.
    -- DUEL_WINNER_KNOCKOUT = "%1$s has defeated %2$s in a duel"; -- %1$s is the winner, %2$s is the loser
    -- DUEL_WINNER_RETREAT = "%2$s has fled from %1$s in a duel"; -- %1$s is the winner, %2$s is the loser
    MarsMessageParser_RegisterFunction("PvPLog_Duel", DUEL_COUNTDOWN,
                      PvPLogDuelStart);
    MarsMessageParser_RegisterFunction("PvPLog_Duel", DUEL_WINNER_KNOCKOUT,
                      PvPLogDuel);
    MarsMessageParser_RegisterFunction("PvPLog_Duel", DUEL_WINNER_RETREAT,
                      PvPLogDuel);

    -- UNITDIESOTHER = "%s dies.";
    MarsMessageParser_RegisterFunction("PvPLog_PlayerDeath", UNITDIESOTHER,
                      PvPLogPlayerDeath);

    -- UNIT_PVP_NAME = "%s %s"; 
    -- The first %s is the rank, and the second %s is the name
    MarsMessageParser_RegisterFunction("PvPLog_GetRank", UNIT_PVP_NAME,
                      PvPLogGetRank);

    -- *** Damage To Player Strings ***
    -- DO NOT CHANGE THE ORDER AS IT IS IMPORTANT --
    -- COMBATHITOTHERSELF = "%s hits you for %d.";
    -- COMBATHITCRITOTHERSELF = "%s crits you for %d.";
    -- COMBATHITSCHOOLOTHERSELF = "%s hits you for %d %s damage.";
    -- COMBATHITCRITSCHOOLOTHERSELF = "%s crits you for %d %s damage.";
    -- SPELLLOGSCHOOLOTHERSELF = "%s's %s hits you for %d %s damage.";
    -- SPELLLOGOTHERSELF = "%s's %s hits you for %d.";
    -- SPELLLOGCRITOTHERSELF = "%s's %s crits you for %d.";
    -- SPELLLOGCRITSCHOOLOTHERSELF = "%s's %s crits you for %d %s damage.";
    -- SPELLPOWERDRAINOTHERSELF = "%s's %s drains %d %s from you.";
    -- SPELLSPLITDAMAGEOTHERSELF = "%s's %s causes you %d damage.";
    -- SPELLPOWERLEECHOTHERSELF="%s's %s drains %d %s from you. %s gains %d %s.";
    -- DAMAGESHIELDOTHERSELF = "%s reflects %d %s damage to you.";
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLLOGSCHOOLOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLLOGCRITSCHOOLOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLLOGOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLLOGCRITOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLPOWERLEECHOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLPOWERDRAINOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      SPELLSPLITDAMAGEOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      DAMAGESHIELDOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      COMBATHITSCHOOLOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      COMBATHITCRITSCHOOLOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      COMBATHITOTHERSELF,
                      PvPLogDamageMe);
    MarsMessageParser_RegisterFunction("PvPLog_DamageMe", 
                      COMBATHITCRITOTHERSELF,
                      PvPLogDamageMe);

    -- *** My Damage to Enemy Strings ***
    -- DO NOT CHANGE THE ORDER AS IT IS IMPORTANT --
    -- COMBATHITSELFOTHER = "You hit %s for %d.";
    -- COMBATHITCRITSELFOTHER = "You crit %s for %d.";
    -- COMBATHITSCHOOLSELFOTHER = "You hit %s for %d %s damage.";
    -- COMBATHITCRITSCHOOLSELFOTHER = "You crit %s for %d %s damage.";
    -- SPELLLOGSCHOOLSELFOTHER = "Your %s hits %s for %d %s damage.";
    -- SPELLLOGSELFOTHER = "Your %s hits %s for %d.";
    -- SPELLLOGCRITSELFOTHER = "Your %s crits %s for %d.";
    -- SPELLLOGCRITSCHOOLSELFOTHER = "Your %s crits %s for %d %s damage.";
    -- SPELLPOWERDRAINSELFOTHER = "Your %s drains %d %s from %s.";
    -- SPELLSPLITDAMAGESELFOTHER = "Your %s causes %s %d damage.";
    -- SPELLPOWERLEECHSELFOTHER= "Your %s drains %d %s from %s. You gain %d %s.";
    -- DAMAGESHIELDSELFOTHER = "You reflect %d %s damage to %s.";
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLLOGSCHOOLSELFOTHER,
                      PvPLogMyDamageSecond);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLLOGCRITSCHOOLSELFOTHER,
                      PvPLogMyDamageSecond);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLLOGSELFOTHER,
                      PvPLogMyDamageSecond);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLLOGCRITSELFOTHER,
                      PvPLogMyDamageSecond);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLPOWERLEECHSELFOTHER,
                      PvPLogMyDamageFourth);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLPOWERDRAINSELFOTHER,
                      PvPLogMyDamageFourth);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      SPELLSPLITDAMAGESELFOTHER,
                      PvPLogMyDamageSecond);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      DAMAGESHIELDSELFOTHER,
                      PvPLogMyDamageThird);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      COMBATHITSCHOOLSELFOTHER,
                      PvPLogMyDamage);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      COMBATHITCRITSCHOOLSELFOTHER,
                      PvPLogMyDamage);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      COMBATHITSELFOTHER,
                      PvPLogMyDamage);
    MarsMessageParser_RegisterFunction("PvPLog_MyDamage",
                      COMBATHITCRITSELFOTHER,
                      PvPLogMyDamage);

    -- *** Damage to either Enemy or Player Strings ***
    -- PERIODICAURADAMAGEOTHERSELF = "You suffer %d %s damage from %s's %s."; 
                       -- You suffer 3 frost damage from Rabbit's Ice Nova.
    -- PERIODICAURADAMAGESELFOTHER = "%s suffers %d %s damage from your %s.";
                           -- Rabbit suffers 3 frost damage from your Ice Nova.
    MarsMessageParser_RegisterFunction("PvPLog_DamageBoth",
                      PERIODICAURADAMAGESELFOTHER,
                      PvPLogMyDamage);
    MarsMessageParser_RegisterFunction("PvPLog_DamageBoth", 
                      PERIODICAURADAMAGEOTHERSELF,
                      PvPLogDamageMeAura);

    -- Register command handler and new commands
    SlashCmdList["PvPLogCOMMAND"] = PvPLogSlashHandler;
    SLASH_PvPLogCOMMAND1 = "/pvplog";
    SLASH_PvPLogCOMMAND2 = "/pl";

    -- initialize character data structures
    
    PvPLogDebugMsg('Recents cleared (initialize).');
    recentDamaged = { };
    recentDamager = { };
    lastDamagerToMe = "";
    foundDamaged = false;
    foundDamager = false;

    if (targetList == nil) then
        targetList = { };
    end
    if (targetRecords == nil) then
        targetRecords = { };
    end

    if (PvPLogData == nil) then
        PvPLogData = { };
    end
    if (PvPLogData[realm] == nil) then
        PvPLogData[realm] = { };
    end
    if (PvPLogData[realm][player] == nil) then
        PvPLogInitPvP();
    end
    PvPLogData[realm][player].version = PVPLOG.VER_NUM;
    PvPLogData[realm][player].vendor = PVPLOG.VENDOR;

    if (PvPLogData[realm][player].notifyKillText == nil) then
        PvPLogData[realm][player].notifyKillText = PVPLOG.DEFAULT_KILL_TEXT;
    end

    if (PvPLogData[realm][player].notifyKill == nil) then
        PvPLogData[realm][player].notifyKill = PVPLOG.NONE;
    end

    if (PvPLogData[realm][player].notifyDeathText == nil) then
        PvPLogData[realm][player].notifyDeathText = PVPLOG.DEFAULT_DEATH_TEXT;
    end

    if (PvPLogData[realm][player].notifyDeath == nil) then
        PvPLogData[realm][player].notifyDeath = PVPLOG.NONE;
    end

    if (PvPLogData[realm][player].MiniMap == nil) then
        PvPLogData[realm][player].MiniMap = { };
    end;

    if (PvPLogData[realm][player].display == nil) then
        PvPLogData[realm][player].display = true;
    end

    if (PvPLogData[realm][player].ding == nil) then
        PvPLogData[realm][player].ding = false;
    end

    if (PvPLogData[realm][player].mouseover == nil) then
        PvPLogData[realm][player].mouseover = true;
    end

    if (PvPLogData[realm][player].recordBG == nil) then
        PvPLogData[realm][player].recordBG = true;
    end

    if (PvPLogData[realm][player].notifyBG == nil) then
        PvPLogData[realm][player].notifyBG = true;
    end

    if (PvPLogData[realm][player].recordDuel == nil) then
        PvPLogData[realm][player].recordDuel = true;
    end

    if (PvPLogData[realm][player].notifyDuel == nil) then
        PvPLogData[realm][player].notifyDuel = true;
    end

    -- output file
    if (PurgeLogData == nil) then
        PurgeLogData = { };
    end
    if (PurgeLogData[realm] == nil) then
        PurgeLogData[realm] = { };
    end
    if (PurgeLogData[realm][player] == nil) then
        PvPLogInitPurge();
    end
    PurgeLogData[realm][player].version = PVPLOG.VER_NUM;
    PurgeLogData[realm][player].vendor = PVPLOG.VENDOR;

    if (PvPLogDebug == nil) then
        PvPLogDebug = { };
        PvPLogDebugSave = { };
    end

    local stats = PvPLogGetStats();
    local allRecords = stats.totalWins + stats.totalLoss;

    -- initialize our damage structure
    if (dmgType == nil) then
        PvPLogInitDamage();
    end

    initialized = true;

    -- Report load
    PvPLogChatMsg("PvPLog variables loaded: " .. allRecords .. " records (" .. 
        stats.totalWins .. "/" .. stats.totalLoss .. ") for " .. 
        player .. " | " .. realm);
end

function PvPLogInitPvP()
    PvPLogData[realm][player] = { };
    PvPLogData[realm][player].battles = { };
    PvPLogData[realm][player].version = PVPLOG.VER_NUM;
    PvPLogData[realm][player].vendor = PVPLOG.VENDOR;
    PvPLogData[realm][player].enabled = true;
    PvPLogData[realm][player].display = true;
    PvPLogData[realm][player].ding = false;
    PvPLogData[realm][player].mouseover = true;
    PvPLogData[realm][player].recordBG = true;
    PvPLogData[realm][player].notifyBG = true;
    PvPLogData[realm][player].recordDuel = true;
    PvPLogData[realm][player].notifyDuel = true;
    
    PvPLogData[realm][player].MiniMap = { };
    PvPLogData[realm][player].dispLocation = "overhead";
    PvPLogData[realm][player].dingSound = "AuctionWindowOpen";
    PvPLogData[realm][player].dingTimeout = 30.0;
    PvPLogData[realm][player].notifyKill = PVPLOG.NONE;
    PvPLogData[realm][player].notifyDeath = PVPLOG.NONE;
    PvPLogData[realm][player].guilds = { };
    PvPLogData[realm][player].notifyKillText = PVPLOG.DEFAULT_KILL_TEXT;
    PvPLogData[realm][player].notifyDeathText = PVPLOG.DEFAULT_DEATH_TEXT;
end

function PvPLogInitPurge()
    PurgeLogData[realm][player] = { };
    PurgeLogData[realm][player].battles = { };
    PurgeLogData[realm][player].version = PVPLOG.VER_NUM;
    PurgeLogData[realm][player].vendor = PVPLOG.VENDOR;
    PurgeLogData[realm][player].PurgeCounter = 5000;
end

function PvPLogGetFaction()
    local englishFaction;
    local localizedFaction;
    englishFaction, localizedFaction = UnitFactionGroup("player");
    return englishFaction;
end

function PvPLogGetFactionIcon()
    local faction = PvPLogGetFaction();
    local icon;
    if (faction == "Horde") then
        icon = "Interface\\Icons\\INV_BannerPvP_01";
    else
        icon = "Interface\\Icons\\INV_BannerPvP_02";
    end
    return icon;
end


function PvPLogInitDamage()
   dmgType.dmg_physical = { };
   dmgType.dmg_holy = { };
   dmgType.dmg_fire = { };
   dmgType.dmg_frost = { };
   dmgType.dmg_nature = { };
   dmgType.dmg_shadow = { };
   dmgType.dmg_arcane = { };
   dmgType.count = 0;
   dmgType.total = 0;
   dmgType.dmg_physical.count = 0;
   dmgType.dmg_physical.norm = 0;
   dmgType.dmg_physical.crit = 0;
   dmgType.dmg_holy.count = 0;
   dmgType.dmg_holy.norm = 0;
   dmgType.dmg_holy.crit = 0;
   dmgType.dmg_fire.count = 0;
   dmgType.dmg_fire.norm = 0;
   dmgType.dmg_fire.crit = 0;
   dmgType.dmg_frost.count = 0;
   dmgType.dmg_frost.norm = 0;
   dmgType.dmg_frost.crit = 0;
   dmgType.dmg_nature.count = 0;
   dmgType.dmg_nature.norm = 0;
   dmgType.dmg_nature.crit = 0;
   dmgType.dmg_shadow.count = 0;
   dmgType.dmg_shadow.norm = 0;
   dmgType.dmg_shadow.crit = 0;
   dmgType.dmg_arcane.count = 0;
   dmgType.dmg_arcane.norm = 0;
   dmgType.dmg_arcane.crit = 0;

   initDamage = true;
end

function PvPLog_ChatFrame_OnEvent(event)
    -- initialize if we're not for some reason
    if (not initialized) then
        PvPLogInitialize();
    end

    -- check to see if we're enabled
    if (PvPLogData[realm][player] and not PvPLogData[realm][player].enabled) then
        return;
    end

    -- check to see if this is a duel related message.
    if (arg1) then
        MarsMessageParser_ParseMessage("PvPLog_Duel", arg1);
    end
end

function PvPLogGetPvPTotals(name)
    if (not name) then
        return nil;
    end

    if (not PvPLogData[realm][player].battles[name]) then
        return nil;
    end

    local total = { };
    total.wins = 0 + PvPLogData[realm][player].battles[name].wins;
    total.loss = 0 + PvPLogData[realm][player].battles[name].loss;
    total.winsStr = "";
    total.lossStr = "";
    total.slashy  = true;

    if (total.wins == 1) then
        total.winsStr = "1 " .. PVPLOG.WIN;
    elseif (total.wins > 1) then
        total.winsStr = total.wins .. " " .. PVPLOG.WINS;
    else
        total.slashy = false;
    end

    if (total.loss == 1) then
        total.lossStr = "1 " .. PVPLOG.LOSS;
    elseif (total.loss > 1) then
        total.lossStr = total.loss .. " " .. PVPLOG.LOSSES;
    end

    if (total.slashy and total.loss > 0) then
        total.slashy = " / ";
    else
        total.slashy = "";
    end

    return total;
end

function PvPLogGetGuildTotals(guild)
    if (not initialized) then
        PvPLogInitialize();
    end

    local total = { };
    local gfound = false;
    if (PvPLogData[realm][player].guilds and
        table.getn(PvPLogData[realm][player].guilds) > 0) then
        table.foreach(PvPLogData[realm][player].guilds,
            function(guildname,tname)
                if(guildname == guild) then
                    total.wins = tname.wins;
                    total.loss = tname.loss;
                    gfound = true;
                    return true;
                end
            end
        );
    end
    if (not gfound) then
        total.wins = 0;
        total.loss = 0;
    end

    total.winsStr = "";
    total.lossStr = "";
    total.slashy  = true;

    if (total.wins == 1) then
        total.winsStr = "1 " .. PVPLOG.WIN;
    elseif (total.wins > 1) then
        total.winsStr = total.wins .. " " .. PVPLOG.WINS;
    else
        total.slashy = false;
    end

    if (total.loss == 1) then
        total.lossStr = "1 ".. PVPLOG.LOSS;
    elseif (total.loss > 1) then
        total.lossStr = total.loss .. " " .. PVPLOG.LOSSES;
    end

    if (total.slashy and total.loss > 0) then
        total.slashy = " / ";
    else
        total.slashy = "";
    end

    return total;
end

function PvPLogFixGuild()
    if (not initialized) then
        PvPLogInitialize();
    end
    if (PvPLogData[realm][player].guilds["Unguilded"]) then
        if(not PvPLogData[realm][player].guilds[""]) then
            PvPLogData[realm][player].guilds[""] = { };
            PvPLogData[realm][player].guilds[""].wins = 0;
            PvPLogData[realm][player].guilds[""].loss = 0;
        end
        PvPLogData[realm][player].guilds[""].wins = PvPLogData[realm][player].guilds[""].wins + PvPLogData[realm][player].guilds["Unguilded"].wins;
        PvPLogData[realm][player].guilds[""].loss = PvPLogData[realm][player].guilds[""].loss + PvPLogData[realm][player].guilds["Unguilded"].loss;
        PvPLogData[realm][player].guilds["Unguilded"] = nil;
    end
    table.foreach(PurgeLogData[realm][player].battles,
        function(target,v1)
            if (v1.guild and v1.guild == "Unguilded") then
                v1.guild = "";
            end
        end
    );
end

function PvPLogGetStats()
    local stats = { };
    stats.totalWins = 0;
    stats.totalWinAvgLevelDiff = 0;
    stats.totalLoss = 0;
    stats.totalLossAvgLevelDiff = 0;
    stats.pvpWins = 0;
    stats.pvpWinAvgLevelDiff = 0;
    stats.pvpLoss = 0;
    stats.pvpLossAvgLevelDiff = 0;
    stats.duelWins = 0;
    stats.duelWinAvgLevelDiff = 0;
    stats.duelLoss = 0;
    stats.duelLossAvgLevelDiff = 0;

    table.foreach(PurgeLogData[realm][player].battles,
        function(target,v1)
            if (not v1.lvlDiff) then
                v1.lvlDiff = 0;
            end
            if (v1.enemy == 1) then
                if (v1.win == 1) then
                    stats.pvpWinAvgLevelDiff = 
                        stats.pvpWinAvgLevelDiff + v1.lvlDiff;
                    stats.pvpWins = stats.pvpWins + 1;
                    stats.totalWins = stats.totalWins + 1;
                    stats.totalWinAvgLevelDiff = 
                        stats.totalWinAvgLevelDiff + v1.lvlDiff;
                else
                    stats.pvpLossAvgLevelDiff = 
                        stats.pvpLossAvgLevelDiff + v1.lvlDiff;
                    stats.pvpLoss = stats.pvpLoss + 1;
                    stats.totalLoss = stats.totalLoss + 1;
                    stats.totalLossAvgLevelDiff = 
                        stats.totalLossAvgLevelDiff + v1.lvlDiff;
                end
            else
                if (v1.win == 1) then
                    stats.duelWinAvgLevelDiff = 
                        stats.duelWinAvgLevelDiff + v1.lvlDiff;
                    stats.duelWins = stats.duelWins + 1;
                    stats.totalWins = stats.totalWins + 1;
                    stats.totalWinAvgLevelDiff = 
                        stats.totalWinAvgLevelDiff + v1.lvlDiff;
                else
                    stats.duelLossAvgLevelDiff = 
                        stats.duelLossAvgLevelDiff + v1.lvlDiff;
                    stats.duelLoss = stats.duelLoss + 1;
                    stats.totalLoss = stats.totalLoss + 1;
                    stats.totalLossAvgLevelDiff = 
                        stats.totalLossAvgLevelDiff + v1.lvlDiff;
                end
            end
        end
    );

    if (stats.totalWins > 0) then
        stats.totalWinAvgLevelDiff = stats.totalWinAvgLevelDiff / stats.totalWins;
    end
    if (stats.totalLoss > 0) then
        stats.totalLossAvgLevelDiff = stats.totalLossAvgLevelDiff / stats.totalLoss;
    end
    if (stats.pvpWins > 0) then
        stats.pvpWinAvgLevelDiff = stats.pvpWinAvgLevelDiff / stats.pvpWins;
    end
    if (stats.pvpLoss > 0) then
        stats.pvpLossAvgLevelDiff = stats.pvpLossAvgLevelDiff / stats.pvpLoss;
    end
    if (stats.duelWins > 0) then
        stats.duelWinAvgLevelDiff = stats.duelWinAvgLevelDiff / stats.duelWins;
    end
    if (stats.duelLoss > 0) then
        stats.duelLossAvgLevelDiff = stats.duelLossAvgLevelDiff / stats.duelLoss;
    end

    return stats;
end

function PvPLogInBG()
    bg_found = false;
    bg_indx = 0;
    for i=1, MAX_BATTLEFIELD_QUEUES do
        bg_status, bg_mapName, bg_instanceId = GetBattlefieldStatus(i);
        if (bg_status == "active" ) then
            bg_found = true;
            bg_indx = i;
            return true;
        end
    end
    return false;
end

function PvPLogRecord(vname, vlevel, vrace, vclass, vguild, venemy, win, vrank, vrealm)
    local ZoneName = GetZoneText();
    local SubZone = GetSubZoneText();
    -- Check Battlefield status
    PvPLogInBG();

    -- Check for conditions under which we do not record data
--    PvPLogDebugMsg('bg_found = '..tostring(bg_found)..', recordBG = '..tostring(PvPLogData[realm][player].recordBG));
--    PvPLogDebugMsg('venemy = '..tostring(venemy)..', recordDuel = '..tostring(PvPLogData[realm][player].recordDuel));
    if ((bg_found and not PvPLogData[realm][player].recordBG) or
      (venemy == 0 and not PvPLogData[realm][player].recordDuel)) then
        PvPLogDebugMsg('Do not record conditions met');
        return;
    end

    -- deal with vlevel being negative 1 when 
    -- they are dlevel levels or more greater
    local level = 0;
    local leveltext = "";
    if (vlevel == -1) then
        level = plevel + dlevel; 
        leveltext = "+";
        if (level >= mlevel) then
            level = mlevel;
            leveltext = "";
        end
    elseif (vlevel) then
        level = vlevel; 
    end
    leveltext = tostring(level)..leveltext;

    -- check to see if we've encountered this person before
    if(not PvPLogData[realm][player].battles[vname]) then
        PvPLogData[realm][player].battles[vname] = { };
        PvPLogData[realm][player].battles[vname].wins = 0;
        PvPLogData[realm][player].battles[vname].loss = 0;
        PvPLogData[realm][player].battles[vname].class = vclass;
        PvPLogData[realm][player].battles[vname].enemy = venemy;
        PvPLogData[realm][player].battles[vname].realm = vrealm;
    end
    -- update zone as it could change
    PvPLogData[realm][player].battles[vname].zone = ZoneName;

    if (not vguild) then
        vguild = "";
    end

    if (PvPLogData[realm][player].guilds == nil) then
        PvPLogData[realm][player].guilds = { };
    end

    if(table.getn(PvPLogData[realm][player].guilds) == 0 or
      not PvPLogData[realm][player].guilds[vguild]) then
        PvPLogData[realm][player].guilds[vguild] = { };
        PvPLogData[realm][player].guilds[vguild].wins = 0;
        PvPLogData[realm][player].guilds[vguild].loss = 0;
    end

    -- prepare data for printing out
    if (PurgeLogData[realm][player].PurgeCounter == nil) then
        PurgeLogData[realm][player].PurgeCounter = 5000;
    end

    local PurgeCounter = PurgeLogData[realm][player].PurgeCounter;
    if (PurgeLogData[realm][player].battles[PurgeCounter] == nil) then
        PurgeLogData[realm][player].battles[PurgeCounter] = { };
        PurgeLogData[realm][player].battles[PurgeCounter].name = vname;
        PurgeLogData[realm][player].battles[PurgeCounter].race = vrace;
        PurgeLogData[realm][player].battles[PurgeCounter].class = vclass;
        PurgeLogData[realm][player].battles[PurgeCounter].enemy = venemy;
        PurgeLogData[realm][player].battles[PurgeCounter].realm = vrealm;
    end 
    PurgeLogData[realm][player].battles[PurgeCounter].guild = vguild;
    PurgeLogData[realm][player].battles[PurgeCounter].win = win;
    PurgeLogData[realm][player].battles[PurgeCounter].lvlDiff = level - UnitLevel("player");
    PurgeLogData[realm][player].battles[PurgeCounter].zone = ZoneName;
    PurgeLogData[realm][player].battles[PurgeCounter].subzone = SubZone;
    PurgeLogData[realm][player].battles[PurgeCounter].rank = vrank;
    if (enemy == 0 or win == 0) then
        PurgeLogData[realm][player].battles[PurgeCounter].honor = 0;
    else
        PurgeLogData[realm][player].battles[PurgeCounter].honor = tonumber(est_honor);
    end
    if (bg_found) then
        PurgeLogData[realm][player].battles[PurgeCounter].bg = bg_indx;
    else
        PurgeLogData[realm][player].battles[PurgeCounter].bg = 0;
    end
    PurgeLogData[realm][player].battles[PurgeCounter].date = date();
    PurgeLogData[realm][player].battles[PurgeCounter].time = time();
    PurgeCounter = PurgeCounter + 1;
    PurgeLogData[realm][player].PurgeCounter = PurgeCounter;

    local x, y = GetPlayerMapPosition("player");
    if ((x == 0) and (y == 0)) then
        SetMapToCurrentZone();
        x, y = GetPlayerMapPosition("player");
    end    
    x = math.floor(x*100);
    y = math.floor(y*100);

    local notifyMsg = "";
    local notifySystem = nil;
    if (win == 1) then
        PvPLogData[realm][player].battles[vname].wins = 
            PvPLogData[realm][player].battles[vname].wins + 1; 
        PvPLogData[realm][player].guilds[vguild].wins = 
            PvPLogData[realm][player].guilds[vguild].wins + 1;

        notifyMsg = PvPLogData[realm][player].notifyKillText;
        notifySystem = PvPLogData[realm][player].notifyKill;
    else
        PvPLogData[realm][player].battles[vname].loss = 
            PvPLogData[realm][player].battles[vname].loss + 1;
        PvPLogData[realm][player].guilds[vguild].loss = 
            PvPLogData[realm][player].guilds[vguild].loss + 1;

        notifyMsg = PvPLogData[realm][player].notifyDeathText;
        notifySystem = PvPLogData[realm][player].notifyDeath;
    end

    -- Check for conditions under which we do not notify
--    PvPLogDebugMsg('bg_found = '..tostring(bg_found)..', notifyBG = '..tostring(PvPLogData[realm][player].notifyBG));
--    PvPLogDebugMsg('venemy = '..tostring(venemy)..', notifyDuel = '..tostring(PvPLogData[realm][player].notifyDuel));
    if ((bg_found and not PvPLogData[realm][player].notifyBG) or
      (venemy == 0 and not PvPLogData[realm][player].notifyDuel)) then
        PvPLogDebugMsg('Do not notify conditions met');
        return;
    end

    notifyMsg = string.gsub( notifyMsg, "%%n", vname );
    notifyMsg = string.gsub( notifyMsg, "%%l", leveltext );
    notifyMsg = string.gsub( notifyMsg, "%%r", vrace );
    notifyMsg = string.gsub( notifyMsg, "%%c", vclass );
    if( vguild ) then
        notifyMsg = string.gsub( notifyMsg, "%%g", vguild );
    else
        notifyMsg = string.gsub( notifyMsg, "%%g", "" );
    end
    if( vrank ) then
        notifyMsg = string.gsub( notifyMsg, "%%t", vrank );
    else
        notifyMsg = string.gsub( notifyMsg, "%%t", "" );
    end
    notifyMsg = string.gsub( notifyMsg, "%%x", x );
    notifyMsg = string.gsub( notifyMsg, "%%y", y );
    notifyMsg = string.gsub( notifyMsg, "%%z", ZoneName );
    notifyMsg = string.gsub( notifyMsg, "%%w", SubZone );
    notifyMsg = string.gsub( notifyMsg, " %(%)", '' );

    PvPLogDebugAdd(notifyMsg);
    if (notifySystem) then
        for notifyChan in string.gmatch(notifySystem, "%w+") do
            if( venemy and notifyChan == PVPLOG.SELF) then
                PvPLogChatMsg(notifyMsg);
            elseif( venemy and
              ((notifyChan == PVPLOG.PARTY and GetNumPartyMembers() > 0) or 
              (notifyChan == PVPLOG.GUILD and GetGuildInfo("player") )  or 
              (notifyChan == PVPLOG.RAID  and GetNumRaidMembers() > 0)) ) then
                if (notifyChan == PVPLOG.RAID and bg_found) then
                    notifyChan = PVPLOG.BG;
                end
                PvPLogSendChatMessage(notifyMsg, notifyChan);
            elseif( venemy and notifyChan ~= PVPLOG.NONE and notifyChan ~= PVPLOG.SELF and
              notifyChan ~= PVPLOG.PARTY and notifyChan ~= PVPLOG.GUILD
              and notifyChan ~= PVPLOG.RAID and notifyChan ~= PVPLOG.BG) then
                PvPLogSendMessageOnChannel(notifyMsg, notifyChan);
            end
        end
    end
end

-- This function is called whenever the player's target has changed.
-- In WoW V2, this is about the only place where we can be sure of capturing
-- information about our target.
function PvPLogUpdateTarget(dueling)
    local targetName, targetRealm = UnitName("target"); 
    local targetLevel = UnitLevel("target");
    local targetRace = UnitRace("target");
    local targetClass = UnitClass("target");
    local targetGuild = GetGuildInfo("target");
    local targetRank = UnitPVPName("target");
    local targetIsPlayer = UnitIsPlayer("target");
    local targetIsEnemy = UnitIsEnemy("player", "target");
    local targetName2 = UnitName("target");
    if (targetName and targetName2 and targetName ~= targetName2) then
        PvPLogDebugMsg('Target changed from "'.. targetName ..'" to "' .. targetName2 .. '"');
        return;
    end
    if (targetName) then
        -- We have a valid target
        if (dueling or (targetIsPlayer and targetIsEnemy)) then
            -- Its a player and its an enemy
            if (not targetRecords[targetName]) then
                PvPLogDebugMsg('Target added: "' .. targetName .. '"');
                targetRecords[targetName] = { };
                table.insert(targetList, targetName);
                if (table.getn(targetList) > NUMTARGETS) then
                    PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                    targetRecords[targetList[1]] = nil;
                    table.remove(targetList,1);
                end
            end
            if (not targetRecords[targetName].level) then
                PvPLogDebugMsg('Target populated: "' .. targetName .. '"');
                targetRecords[targetName].realm = targetRealm;
                targetRecords[targetName].level = targetLevel;
                targetRecords[targetName].race = targetRace;
                targetRecords[targetName].class = targetClass;
                targetRecords[targetName].guild = targetGuild;
                targetRecords[targetName].rank = "";
                MarsMessageParser_ParseMessage("PvPLog_GetRank", 
                    targetRank);
                targetRecords[targetName].rank = fullrank;
                fullrank = "";
            else
                if (targetLevel > targetRecords[targetName].level) then
                    PvPLogDebugMsg('Target updated: "' .. targetName .. '"');
                    targetRecords[targetName].level = targetLevel;
                end
            end
        elseif (debug_ignore) then
            -- Its not a player or its not an enemy
            if (not ignoreRecords[targetName]) then
                PvPLogDebugMsg('Ignore added: "' .. targetName .. '"');
                ignoreRecords[targetName] = true;
                table.insert(ignoreList, targetName);
                if (table.getn(ignoreList) > NUMTARGETS) then
                    PvPLogDebugMsg('Ignore removed: "' .. ignoreList[1] .. '"');
                    ignoreRecords[ignoreList[1]] = nil;
                    table.remove(ignoreList,1);
                end
            end
            if (targetRecords[targetName]) then
                -- It got into targetRecords, remove it.
                index = -1;
                table.foreach(targetList,
                    function(i,t)
                        if(t == targetName) then
                            index = i;
                            return;
                        end
                    end
                );
                if (index ~= -1) then
                    PvPLogDebugMsg('Target removed: "' .. targetList[index] .. '"');
                    targetRecords[targetName] = nil;
                    table.remove(targetList,index);
                else
                    PvPLogDebugMsg('TargetRecord not found in TargetList for: '..targetName);
                end
            end
        end

        local total = PvPLogGetPvPTotals(targetName);
        local guildTotal = PvPLogGetGuildTotals(targetGuild);
        local msg = "";
        local show = false;
        if (total and (total.wins > 0 or total.loss > 0)) then
            msg = msg .. CYAN .. PVPLOG.UI_PVP .. ": " .. GREEN .. total.wins.. CYAN .. 
            " / " .. RED .. total.loss;
            show = true;
        end
        if (guildTotal and (guildTotal.wins > 0 or guildTotal.loss > 0)) then
            if (show) then
                msg = msg .. CYAN .. " - ";
            end
            msg = msg .. CYAN .. PVPLOG.GUILD .. ": ";
            msg = msg .. GREEN .. guildTotal.wins.. CYAN .. " / ".. RED .. 
            guildTotal.loss;
            show = true;
        end
        local field = getglobal("PvPLogTargetText");
        if (show and PvPLogData[realm][player].display) then
            field:SetText(msg);
            field:Show();
        end
    end
end

function PvPLogSetEnabled(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].enabled = false;
        PvPLogChatMsgCyan("PvPLog " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].enabled = true;
        PvPLogChatMsgCyan("PvPLog " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetDisplay(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].display = false;
        PvPLogChatMsgCyan("PvPLog Floating Display " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].display = true;
        PvPLogChatMsgCyan("PvPLog Floating Display " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetDing(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].ding = false;
        PvPLogChatMsgCyan("PvPLog Ding Sound " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].ding = true;
        PvPLogChatMsgCyan("PvPLog Ding Sound " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetMouseover(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].mouseover = false;
        PvPLogChatMsgCyan("PvPLog Mouseover Effects " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].mouseover = true;
        PvPLogChatMsgCyan("PvPLog Mouseover Effects " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetRecordBG(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].recordBG = false;
        PvPLogChatMsgCyan("PvPLog Record in Battlegrounds " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].recordBG = true;
        PvPLogChatMsgCyan("PvPLog Record in Battlegrounds " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetNotifyBG(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].notifyBG = false;
        PvPLogChatMsgCyan("PvPLog Notify in Battlegrounds " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].notifyBG = true;
        PvPLogChatMsgCyan("PvPLog Notify in Battlegrounds " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetRecordDuel(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].recordDuel = false;
        PvPLogChatMsgCyan("PvPLog Record Duels " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].recordDuel = true;
        PvPLogChatMsgCyan("PvPLog Record Duels " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSetNotifyDuel(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].notifyDuel = false;
        PvPLogChatMsgCyan("PvPLog Notify Duels " .. ORANGE .. PVPLOG.OFF);
    else
        PvPLogData[realm][player].notifyDuel = true;
        PvPLogChatMsgCyan("PvPLog Notify Duels " .. ORANGE .. PVPLOG.ON);
    end        
end

function PvPLogSlashHandler(msg)
    -- initialize if we're not for some reason
    if (not initialized) then
      PvPLogInitialize();
    end

    local firsti, lasti, command, value = string.find (msg, "(%w+) \"(.*)\"");
    if (command == nil) then
        firsti, lasti, command, value = string.find (msg, "(%w+) (%w+)");
    end
    if (command == nil) then
        firsti, lasti, command = string.find(msg, "(%w+)");
    end    
    if (command ~= nil) then
        command = string.lower(command);
    end

    -- respond to commands
    if (command == nil) then
        PvPLogDisplayUsage();
    elseif (command == "debug") then
        if (value == "on") then
            debug_simple = true;
        elseif (value == "off") then
            debug_simple = false;
        elseif (value == "save") then
            PvPLogDebugSave = { };
            for i,v in ipairs(PvPLogDebug) do
                table.insert(PvPLogDebugSave,v);
            end
        else
            debug_indx = nil;
        end
    elseif (command == "ignore") then
        if (value == "on") then
            debug_ignore = true;
        elseif (value == "off") then
            debug_ignore = false;
            ignoreList = { };
            ignoreRecords = { };
        end
    elseif (command == "vars") then
        if (softPL) then
            PvPLogDebugMsg("softPL = TRUE");
        else
            PvPLogDebugMsg("softPL = FALSE");
        end
        PvPLogDebugMsg("targetList = {"..table.concat(targetList,", ").."}");
        s = "";
        for i in pairs(targetRecords) do
            s = s..", "..i;
        end
        s = string.sub(s,3);
        PvPLogDebugMsg("targetRecords = {"..s.."}");
        PvPLogDebugMsg("ignoreList = {"..table.concat(ignoreList,", ").."}");
        s = "";
        for i in pairs(ignoreRecords) do
            s = s..", "..i;
        end
        s = string.sub(s,3);
        PvPLogDebugMsg("ignoreRecords = {"..s.."}");
        PvPLogDebugMsg("recentDamager = {"..table.concat(recentDamager,", ").."}");
        PvPLogDebugMsg("recentDamaged = {"..table.concat(recentDamaged,", ").."}");
        if (isDuel) then
            PvPLogDebugMsg("isDuel = TRUE");
        else
            PvPLogDebugMsg("isDuel = FALSE");
        end
    elseif (command == "fixguild") then
            PvPLogFixGuild();
    elseif (command == PVPLOG.RESET) then
        if (value == PVPLOG.CONFIRM) then
            PvPLogInitPvP();
            PvPLogInitPurge();
            PvPLogChatMsgCyan("PvPLog " .. MAGENTA .. PVPLOG.RESET .. " " .. CYAN .. PVPLOG.COMP);
        end
    elseif (command == PVPLOG.NOTIFYKILL) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyKill = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. PVPLOG.NOTIFYKILL .. 
                CYAN .. PVPLOG.TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == PVPLOG.NOTIFYKILLTEXT) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyKillText = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. PVPLOG.NOTIFYKILLTEXT .. 
                CYAN .. PVPLOG.TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == PVPLOG.NOTIFYDEATH) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyDeath = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. PVPLOG.NOTIFYDEATH .. 
                CYAN .. PVPLOG.TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == PVPLOG.NOTIFYDEATHTEXT) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyDeathText = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. PVPLOG.NOTIFYDEATHTEXT .. 
                CYAN .. PVPLOG.TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == PVPLOG.ENABLE) then
        PvPLogSetEnabled("on");
    elseif (command == PVPLOG.DISABLE) then
        PvPLogSetEnabled("off");
    elseif (command == PVPLOG.VER) then
        PvPLogChatMsgCyan("PvPLog "..VER..": " .. WHITE .. PVPLOG.VER_NUM);
    elseif (command == PVPLOG.VEN) then
        PvPLogChatMsgCyan("PvPLog "..VEN..": " .. WHITE .. PVPLOG.VENDOR);
    elseif (command == PVPLOG.DMG) then
        PvPLogPrintDamage();
    elseif (command == PVPLOG.ST) then
        PvPLogPrintStats();
    elseif (command == PVPLOG.NOSPAM) then
        PvPLogSetDisplay("off");
        PvPLogSetDing("off");
        PvPLogSetMouseover("off");
    elseif (command == string.lower(PVPLOG.UI_PVP)) then
        PvPLogStatsFrame:Hide();
        PVPLOG.STATS_TYPE = PVPLOG.UI_PVP;
        PvPLogStatsFrame:Show();
    elseif (command == string.lower(PVPLOG.DUEL)) then
        PvPLogStatsFrame:Hide();
        PVPLOG.STATS_TYPE = PVPLOG.DUEL;
        PvPLogStatsFrame:Show();
    elseif (command == string.lower(PVPLOG.RECENT)) then
        PvPLogStatsFrame:Hide();
        PVPLOG.STATS_TYPE = PVPLOG.RECENT;
        PvPLogStatsFrame:Show();
    elseif (command == PVPLOG.UI_CONFIG) then
        PvPLogConfigShow();
    else
        PvPLogDisplayUsage();
    end
end

function PvPLogDisplayUsage()
    local text;

    text = CYAN .. PVPLOG.USAGE .. ":\n  /pl <";
    if (PvPLogData[realm][player].enabled) then
        text = text .. WHITE .. PVPLOG.ENABLE .. CYAN .. " | " .. PVPLOG.DISABLE .. ">\n";
    else
        text = text .. PVPLOG.ENABLE.." | " .. WHITE .. PVPLOG.DISABLE .. CYAN .. ">\n";
    end
    PvPLogChatMsg(text);

    PvPLogChatMsgPl(PVPLOG.RESET .. " " .. PVPLOG.CONFIRM .. "\n");
    PvPLogChatMsgPl(PVPLOG.ST .. "\n");
    PvPLogChatMsgPl(PVPLOG.DMG .. "\n");

    text = PVPLOG.NOTIFYKILL.." <";
    if (PvPLogData[realm][player].notifyKill == PVPLOG.NONE) then
        text = text .. WHITE .. PVPLOG.NONE .. CYAN;
    else
        text = text .. PVPLOG.NONE;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == PVPLOG.SELF) then
        text = text .. WHITE .. PVPLOG.SELF .. CYAN;
    else
        text = text .. PVPLOG.SELF;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == PVPLOG.PARTY) then
        text = text .. WHITE .. PVPLOG.PARTY .. CYAN;
    else
        text = text .. PVPLOG.PARTY;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == PVPLOG.GUILD) then
        text = text .. WHITE .. PVPLOG.GUILD .. CYAN;
    else
        text = text .. PVPLOG.GUILD;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == PVPLOG.RAID) then
        text = text .. WHITE .. PVPLOG.RAID .. CYAN;
    else
        text = text .. PVPLOG.RAID;
    end
    if (PvPLogData[realm][player].notifyKill ~= PVPLOG.NONE and
        PvPLogData[realm][player].notifyKill ~= PVPLOG.SELF and
        PvPLogData[realm][player].notifyKill ~= PVPLOG.PARTY and
        PvPLogData[realm][player].notifyKill ~= PVPLOG.GUILD and
        PvPLogData[realm][player].notifyKill ~= PVPLOG.RAID) then
        text = text .." | " .. WHITE .. PvPLogData[realm][player].notifyKill .. CYAN .. ">\n";
    else
        text = text .. ">\n";
    end
    PvPLogChatMsgPl(text);

    text = PVPLOG.NOTIFYKILLTEXT.." <";
    text = text .. WHITE .. PvPLogData[realm][player].notifyKillText .. CYAN .. ">\n";
    PvPLogChatMsgPl(text);

    text = PVPLOG.NOTIFYDEATH.." <";
    if (PvPLogData[realm][player].notifyDeath == PVPLOG.NONE) then
        text = text .. WHITE .. PVPLOG.NONE .. CYAN;
    else
        text = text .. PVPLOG.NONE;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == PVPLOG.SELF) then
        text = text .. WHITE .. PVPLOG.SELF .. CYAN;
    else
        text = text .. PVPLOG.SELF;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == PVPLOG.PARTY) then
        text = text .. WHITE .. PVPLOG.PARTY .. CYAN;
    else
        text = text .. PVPLOG.PARTY;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == PVPLOG.GUILD) then
        text = text .. WHITE .. PVPLOG.GUILD .. CYAN;
    else
        text = text .. PVPLOG.GUILD;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == PVPLOG.RAID) then
        text = text .. WHITE .. PVPLOG.RAID .. CYAN;
    else
        text = text .. PVPLOG.RAID;
    end
    if (PvPLogData[realm][player].notifyDeath ~= PVPLOG.NONE and
        PvPLogData[realm][player].notifyDeath ~= PVPLOG.SELF and
        PvPLogData[realm][player].notifyDeath ~= PVPLOG.PARTY and
        PvPLogData[realm][player].notifyDeath ~= PVPLOG.GUILD and
        PvPLogData[realm][player].notifyDeath ~= PVPLOG.RAID) then
        text = text .. " | " .. WHITE .. PvPLogData[realm][player].notifyDeath .. CYAN .. ">\n";
    else
        text = text .. ">\n";
    end
    PvPLogChatMsgPl(text);

    text = PVPLOG.NOTIFYDEATHTEXT.." <";
    text = text .. WHITE .. PvPLogData[realm][player].notifyDeathText .. CYAN .. ">\n";
    PvPLogChatMsgPl(text);

    PvPLogChatMsgPl(PVPLOG.NOSPAM.."\n");
    PvPLogChatMsgPl(PVPLOG.VER.."\n");
    PvPLogChatMsgPl(PVPLOG.VEN.."\n");

    PvPLogChatMsgPl(string.lower(PVPLOG.UI_PVP).."\n");
    PvPLogChatMsgPl(string.lower(PVPLOG.DUEL).."\n");
    PvPLogChatMsgPl(PVPLOG.UI_CONFIG.."\n");
end

function PvPLogChatMsgPl(msg)
    PvPLogChatMsgCyan("  /pl " .. msg);
end

function PvPLogChatMsgCyan(msg)
    PvPLogChatMsg(CYAN .. msg);
end

function PvPLogSendChatMessage(msg, chan, lang, num)
    chan = string.upper(chan);

    if (num ~= nil or num == '') then
-- PvPLogDebugMsg('4 PvPLogSendChatMessage("' .. msg .. '", "' .. chan .. '", "' .. num .. '")');   
        SendChatMessage(msg, chan, lang, num);
    else
-- PvPLogDebugMsg('2 PvPLogSendChatMessage("' .. msg .. '", ' .. chan .. ')');
        SendChatMessage(msg, chan);
    end
end

function PvPLogSendMessageOnChannel(message, channelName)
--  PvPLogDebugMsg('PvPLogSendMessageOnChannel("' .. message .. '", ' .. channelName .. ')');
    local channelNum = PvPLogGetChannelNumber(channelName);

    if (not channelNum or channelNum == 0) then
        PvPLogJoinChannel(channelName);
        channelNum = PvPLogGetChannelNumber(channelName);
    end

    if (not channelNum or channelNum == 0) then
        PvPLogChatMsg(MAGENTA.."PvPLog Error: Not in notification channel \""..channelName.."\".");
        return;
    end

    PvPLogSendChatMessage(message, "CHANNEL", GetLanguageByIndex(0), channelNum);
end


function PvPLogGetChannelNumber(channel)
--  PvPLogDebugMsg('PvPLogGetChannelNumber(' .. channel .. ')');
    local num = 0;
    if (string.len(channel) == 1 and channel >= "1" and channel <= "9") then
        num = channel;
        return num;
    end
    for i = 1, 200, 1 do
        local channelNum, channelName = GetChannelName(i);

        if ((channelNum > 0) and channelName and (string.lower(channelName) == string.lower(channel))) then
            num = channelNum;
            break;
        end
    end
--  PvPLogDebugMsg('channelNum: "' .. num .. '"');
    return num;
end

function PvPLogJoinChannel(channelName)
--  PvPLogDebugMsg('PvPLogJoinChannel(' .. channelName .. ')');
    
    local channelNumber = PvPLogGetChannelNumber(channelName);
    local needToJoin = (channelNumber ~= nil and channelNumber <= 0);

    if( needToJoin ) then
        local i = 1;
        while ( DEFAULT_CHAT_FRAME.channelList[i] ) do
            local zoneValue = "nil";
            if (DEFAULT_CHAT_FRAME.zoneChannelList[i]) then
                zoneValue = DEFAULT_CHAT_FRAME.zoneChannelList[i];
            end
            if ( string.lower(DEFAULT_CHAT_FRAME.channelList[i]) == string.lower(channelName) and 
                DEFAULT_CHAT_FRAME.zoneChannelList[i] and DEFAULT_CHAT_FRAME.zoneChannelList[i] > 0) then
                needToJoin = false;
                break;
            end
            i = i + 1;
        end
        JoinChannelByName(channelName, "", DEFAULT_CHAT_FRAME:GetID());
        DEFAULT_CHAT_FRAME.channelList[i] = channelName;
        DEFAULT_CHAT_FRAME.zoneChannelList[i] = 0;
    end
end
