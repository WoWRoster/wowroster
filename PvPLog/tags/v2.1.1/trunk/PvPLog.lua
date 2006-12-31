--[[
    PvPLog 
    Author:           Andrzej Gorski, 
    Maintainer:       Matthew Musgrove, Brad Morgan
    Based on Work by: Josh Estelle, Daniel S. Reichenbach
    Version:          2.1.1
    Last Modified:    2006-12-30
]]

-- Local variables
local variablesLoaded = false;
local initialized = false;

local realm = "";
local player = "";
local plevel = -1;

local softPL; -- soft PvPLog enable/disable

local bg_status;
local bg_mapName;
local bg_instanceId;
local isDuel = false;
local rank = "";
local fullrank = "";
local est_honor = 0;

local debug_indx;
local debug_simple = false;

local lastDamagerToMe = "";
local foundDamaged = false;
local foundDamager = false;

local NUMTARGETS = 45;
local recentDamager = { };
local recentDamaged = { };
local ignoreList = { };
local ignoreRecords = { };

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

-- Called OnLoad of the add on
function PvPLogOnLoad()
    PvPLogChatMsgCyan(PVPLOG_STARTUP);

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
    this:RegisterEvent("CHAT_MSG_SPELL_PERIODIC_SELF_DAMAGE");

    -- enters/leaves combat (for DPS)
    --this:RegisterEvent("PLAYER_REGEN_ENABLED");
    this:RegisterEvent("PLAYER_REGEN_DISABLED");

    -- testing
    --this:RegisterEvent("PLAYER_PVP_KILLS_CHANGED");
end

-- function PvPLog_MiniMap_LeftClick()

-- end
 
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
            version = VER_NUM,
            author = "Andrzej Gorski",
            website = "http://"..VER_VENDOR,
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
                subtext = "v"..VER_NUM,
                tooltip = DESCRIPTION,
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
                helptext = DESCRIPTION,
                difficulty = 1,
                callback = function(state) end,
                default = true,
                options = {
                    {
                        id = "PvPLogOptionsHeader",
                        type = K_HEADER,
                        difficulty = 1,
                        text = "PvPLog v"..VER_NUM,
                        helptext = DESCRIPTION
                    },
                    {
                        id = "MobInfo2OptionsButton",
                        type = K_BUTTON,
                        difficulty = 1,
                        text = "PvPLog "..UI_CONFIG,
                        helptext = "",
                        callback = function(state) PvPLog_MiniMap_RightClick() end,
                        feedback = function(state) end,
                        setup = { buttonText = UI_OPEN }
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
    info.tooltip = UI_RIGHT_CLICK .. UI_TOGGLE;
    info.enabled = 1; -- default only. after first use, SavedVariables used
    info.config = 0;
    info.icon = PvPLogGetFactionIcon();
    return info;
end

function PvPLogCreateMinimapButton()
    local info = PvPLogMinimapButtonInit();
    MyMinimapButton:Create("PvPLog", PvPLogData[realm][player].MiniMap, info);
    MyMinimapButton:SetRightClick("PvPLog", PvPLog_MiniMap_RightClick);
end

function PvPLogOnEvent()   
    -- loads and initializes our variables
    if (event == "VARIABLES_LOADED") then
        variablesLoaded = true;
        PvPLog_RegisterWithAddonManagers();
        
    -- initialize when entering world
    elseif (event == "PLAYER_ENTERING_WORLD") then
        PvPLogInitialize();
        local bg_found = false;
        local PosX, PosY = GetPlayerMapPosition("player");
        -- Determines whether we are in an Instance or not 
        if (PosX == 0 and PosY == 0) then -- inside instance
        -- Check if the Instance is a Battleground
            for i=1, MAX_BATTLEFIELD_QUEUES do
                bg_status, bg_mapName, bg_instanceId = GetBattlefieldStatus(i);
                if (bg_status == "active") then
                    bg_found = true;
                    bg_indx = i;
                end
            end
            if (bg_found) then
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
                    PvPLogChatMsgCyan("PvP "..DLKB..RED..lastDamagerToMe);
                    PvPLogRecord(lastDamagerToMe, v.level, v.race, v.class, v.guild, 1, 0, v.rank, v.realm);
                else
                    PvPLogDebugMsg(RED.."Empty targetRecords for: "..lastDamagerToMe);
                end
            else
                PvPLogDebugMsg(RED.."No targetRecords for: "..lastDamagerToMe);
            end
        else
            PvPLogDebugMsg(RED.."No recentDamager for: "..lastDamagerToMe);
        end

        -- we are dead, clear the variables
        PvPLogDebugMsg('Recents cleared (dead).');
        recentDamaged = { };
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
                        GameTooltip:AddLine(CYAN .. "PvP: " .. GREEN .. total.wins .. 
                             CYAN .. " / " .. RED .. total.loss, 
                             1.0, 1.0, 1.0, 0);
                    else
                        GameTooltip:AddLine(CYAN..DUEL..": " .. GREEN .. total.wins .. 
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
                        GameTooltip:AddLine(CYAN .. GUILD .. " PvP: " .. GREEN .. 
                               guildTotal.wins .. 
                               CYAN .. " / " .. RED .. guildTotal.loss, 
                               1.0, 1.0, 1.0, 0);
                    else
                        GameTooltip:AddLine(CYAN .. GUILD.." "..DUEL..": " .. GREEN .. 
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
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                PvPLog_damageMe(arg1);
            end
        end
    elseif (event == "CHAT_MSG_COMBAT_SELF_HITS") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_COMBAT_PET_HITS") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_DAMAGESHIELD_ON_SELF") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_SELF_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                PvPLog_myDamage(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PET_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_HOSTILEPLAYER_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                PvPLog_damageMe(arg1);
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PERIODIC_SELF_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                -- PvPLogDebugMsg(ORANGE.."Time: "..GetTime());
            end
        end
    elseif (event == "CHAT_MSG_SPELL_PERIODIC_HOSTILEPLAYER_DAMAGE") then
        if (PvPLogData[realm][player].enabled and softPL) then
            if (arg1) then
                -- PvPLogDebugMsg(GREEN.."Event: "..event);
                -- PvPLogDebugMsg(FIRE.."Msg: "..arg1);
                -- PvPLogDebugMsg(ORANGE.."Time: "..GetTime());
            end
        end
    elseif (event == "PLAYER_REGEN_DISABLED") then
        PvPLogStatsFrame:Hide();
        PvPLogConfigHide();
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

    PvPLogDebugMsg(CYAN .. "Physical Avg: " .. physAvg);
    PvPLogDebugMsg(BLUE .. "Fire Avg: " .. fireAvg);
    PvPLogDebugMsg(MAGENTA .. "Frost Avg: " .. frostAvg);
    PvPLogDebugMsg(CYAN .. "Nature Avg: " .. natureAvg);
    PvPLogDebugMsg(BLUE .. "Shadow Avg: " .. shadowAvg);
    PvPLogDebugMsg(MAGENTA .. "Arcane Avg: " .. arcaneAvg);
end

function PvPLogPrintStats()
    local stats = PvPLogGetStats();
--[[
    local gankLevel = GL0;
    if (stats.pvpWinAvgLevelDiff <= -25) then
        gankLevel = GL_25;
    elseif (stats.pvpWinAvgLevelDiff <= -20) then
        gankLevel = GL_20;
    elseif (stats.pvpWinAvgLevelDiff <= -15) then
        gankLevel = GL_15;
    elseif (stats.pvpWinAvgLevelDiff <= -12) then
        gankLevel = GL_12;
    elseif (stats.pvpWinAvgLevelDiff <= -9) then
        gankLevel = GL_9;
    elseif (stats.pvpWinAvgLevelDiff <= -6) then
        gankLevel = GL_6;
    elseif (stats.pvpWinAvgLevelDiff <= -3) then
        gankLevel = GL_3;
    elseif (stats.pvpWinAvgLevelDiff >= 8) then
        gankLevel = GL8;
    elseif (stats.pvpWinAvgLevelDiff >= 5) then
        gankLevel = GL5;
    elseif (stats.pvpWinAvgLevelDiff >= 4) then
        gankLevel = GL4;
    elseif (stats.pvpWinAvgLevelDiff >= 3) then
        gankLevel = GL3;
    elseif (stats.pvpWinAvgLevelDiff >= 2) then
        gankLevel = GL2;
    elseif (stats.pvpWinAvgLevelDiff >= 1) then
        gankLevel = GL1;
    end
]]--
    PvPLogChatMsgCyan("PvPLog " .. STATS .. ":");
    PvPLogChatMsg(MAGENTA.."   "..TOTAL.." "..WINS..":     ".. stats.totalWins ..
        " ("..ALD..": "..(math.floor(stats.totalWinAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(MAGENTA.."   "..TOTAL.." "..LOSSES..":  ".. stats.totalLoss ..
        " ("..ALD..": "..(math.floor(stats.totalLossAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(ORANGE .. "    PvP "..WINS..":     ".. stats.pvpWins ..
        " ("..ALD..": "..(math.floor(stats.pvpWinAvgLevelDiff*100)/100)..")");
--      " ("..ALD..": "..(math.floor(stats.pvpWinAvgLevelDiff*100)/100)..", " .. gankLevel .. ")");

    PvPLogChatMsg(ORANGE .. "    PvP "..LOSSES..":  ".. stats.pvpLoss ..
        " ("..ALD..": "..(math.floor(stats.pvpLossAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(GREEN .. "    "..DUEL.." "..WINS..":    ".. stats.duelWins ..
        " ("..ALD..": "..(math.floor(stats.duelWinAvgLevelDiff*100)/100)..")");

    PvPLogChatMsg(GREEN .. "    "..DUEL.." "..LOSSES..": ".. stats.duelLoss ..
        " ("..ALD..": "..(math.floor(stats.duelLossAvgLevelDiff*100)/100)..")");
end

function PvPLogDebugMsg(msg)
    if (debug_simple) then
        PvPLogChatMsg('debug: ' .. msg);
    end
end

--[[
function PvPLogDebugMsg(msg)
    -- will print to chatFrame that listens to PvPDebug Channel as the only chan
    if (debug_indx == nil) then
        local number = 1;
        local chatFrame;
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
        chatFrame = getglobal("ChatFrame"..number);
        debug_indx = chatFrame;
    end
    debug_indx:AddMessage(msg);
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
    PvPLogDebugMsg(ORANGE.."Starting duel");
    isDuel = true;
end

function PvPLogDuel(parseWinner, parseLoser)
    PvPLogDebugMsg(ORANGE.."Processing duel: "..parseWinner..", "..parseLoser);
    if (parseWinner and parseLoser) then
        if (UnitName("player") == parseWinner) then
            PvPLogUpdateTarget(isDuel);
            if (targetRecords[parseLoser]) then
                if (targetRecords[parseLoser].level) then
                    v = targetRecords[parseLoser];
                    PvPLogChatMsgCyan(DWLA..GREEN..parseLoser);
                    PvPLogRecord(parseLoser, v.level, v.race, v.class, v.guild, 0, 1, v.rank);
                else
                    PvPLogDebugMsg(RED.."Empty targetRecords for: "..parseLoser);
                end
            else
                PvPLogDebugMsg(RED.."No targetRecords for: "..parseLoser);
            end
        elseif (UnitName("player") == parseLoser) then
            if (targetRecords[parseWinner]) then
                PvPLogUpdateTarget(isDuel);
                if (targetRecords[parseWinner].level) then
                    v = targetRecords[parseWinner];
                    PvPLogChatMsgCyan(DLLA..GREEN..parseWinner);
                    PvPLogRecord(parseWinner, v.level, v.race, v.class, v.guild, 0, 0, v.rank);
                else
                    PvPLogDebugMsg(RED.."Empty targetRecords for: "..parseWinner);
                end
            else
                PvPLogDebugMsg(RED.."No targetRecords for: "..parseWinner);
            end
        end
        isDuel = false;
    end
end

function PvPLogPlayerDeath(parseName)
    -- if we have a name
    if (parseName) then
        PvPLogDebugMsg(YELLOW.."Processing death of: "..parseName);
        local found = false;
        table.foreach(recentDamaged,
            function(i,tname)
                if (tname == parseName) then
                    found = true;
                    return true;
                end
            end
        );
        if (found) then
            if (targetRecords[parseName]) then
                if (targetRecords[parseName].level) then
                    v = targetRecords[parseName];
                    PvPLogChatMsgCyan(KL  .. GREEN .. parseName);
                    PvPLogRecord(parseName, v.level, v.race, v.class, v.guild, 1, 1, v.rank, v.realm);
                else
                    PvPLogDebugMsg(RED.."Empty targetRecords for: "..parseName);
                end
            else
                PvPLogDebugMsg(RED.."No targetRecords for: "..parseName);
            end
        else
            PvPLogDebugMsg(RED.."No recentDamaged for: "..parseName);
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
        if (table.getn(tab)>NUMTARGETS) then
           table.remove(tab,1);
        end
    end
end

function PvPLogMyDamage(res1, res2, res3, res4, res5)
    if (res1 and res1 ~= nil) then
        if ((isDuel or not ignoreRecords[res1]) and not targetRecords[res1]) then
            PvPLogDebugMsg(RED.."Damaged Targets Addition: "..res1);
            targetRecords[res1] = { };
            table.insert(targetList, res1);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res1]) then
            PvPLogPutInTable(recentDamaged, res1);
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageSecond(res1, res2, res3, res4, res5, res6)
    if (res2 and res2 ~= nil) then
        if ((isDuel or not ignoreRecords[res2]) and not targetRecords[res2]) then
            PvPLogDebugMsg(RED.."Damaged Targets Addition: "..res2);
            targetRecords[res2] = { };
            table.insert(targetList, res2);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res2]) then
            PvPLogPutInTable(recentDamaged, res2);
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageThird(res1, res2, res3, res4, res5, res6)
    if (res3 and res3 ~= nil) then
        if ((isDuel or not ignoreRecords[res3]) and not targetRecords[res3]) then
            PvPLogDebugMsg(RED.."Damaged Targets Addition: "..res3);
            targetRecords[res3] = { };
            table.insert(targetList, res3);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res3]) then
            PvPLogPutInTable(recentDamaged, res3);
            foundDamaged = true;
        end
    end
end

function PvPLogMyDamageFourth(res1, res2, res3, res4, res5, res6)
    if (res4 and res4 ~= nil) then
        if ((isDuel or not ignoreRecords[res4]) and not targetRecords[res4]) then
            PvPLogDebugMsg(RED.."Damaged Targets Addition: "..res4);
            targetRecords[res4] = { };
            table.insert(targetList, res4);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res4]) then
            PvPLogPutInTable(recentDamaged, res3);
            foundDamaged = true;
        end
    end
end

function PvPLogDamageMe(res1, res2, res3, res4, res5, res6, res7)
    if (res1 and res1 ~= nil) then
        
        if ((isDuel or not ignoreRecords[res1]) and not targetRecords[res1]) then
            PvPLogDebugMsg(RED.."Recent Targets Addition: "..res1);
            targetRecords[res1] = { };
            table.insert(targetList, res1);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res1]) then
            PvPLogPutInTable(recentDamager, res1);
            lastDamagerToMe = res1;
            foundDamager = true;
        end
    end
end

-- PERIODICAURADAMAGEOTHERSELF = "You suffer %d %s damage from %s's %s."; 
-- You suffer 3 frost damage from Rabbit's Ice Nova.
function PvPLogDamageMeAura(res1, res2, res3, res4)
    if (res3 and res3 ~= nil) then
        if ((isDuel or not ignoreRecords[res3]) and not targetRecords[res3]) then
            PvPLogDebugMsg(RED.."Recent Targets Addition: "..res3);
            targetRecords[res3] = { };
            table.insert(targetList, res3);
            if (table.getn(targetList) > NUMTARGETS) then
                PvPLogDebugMsg('Target removed: "' .. targetList[1] .. '"');
                targetRecords[targetList[1]] = nil;
                table.remove(targetList,1);
            end
        end
        if (isDuel or not ignoreRecords[res3]) then
            PvPLogPutInTable(recentDamager, res3);
            lastDamagerToMe = res3;
            foundDamager = true;
        end
    end
end

function PvPLog_myDamage(msg)
    foundDamaged = false;
    MarsMessageParser_ParseMessage("PvPLog_MyDamage", msg); 
    if (foundDamaged) then
        return true;
    end
    return false;
end

function PvPLog_damageMe(msg)
    foundDamager = false;
    MarsMessageParser_ParseMessage("PvPLog_DamageMe", msg);
    if (foundDamager) then
        return true;
    end
    return false;
end

function PvPLogGetRank(parseRank, parseName)
    if (parseRank and parseRank ~= nil) then
        fullrank = parseRank;
    end
end

function PvPLogInitialize()   
    -- get realm and player
    realm = GetCVar("realmName");
    player = UnitName("player");
    plevel = UnitLevel("player");

    -- check for valid realm and player
    if (initialized or (not variablesLoaded) or (not realm) or 
        (not plevel) or (not player) or (player == PVPLOG_UNKNOWN_ENTITY)) then
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
    -- PERIODICAURADAMAGEOTHERSELF = "You suffer %d %s damage from %s's %s."; 
                       -- You suffer 3 frost damage from Rabbit's Ice Nova.
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
                      PERIODICAURADAMAGEOTHERSELF,
                      PvPLogDamageMeAura);
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
    -- PERIODICAURADAMAGESELFOTHER = "%s suffers %d %s damage from your %s.";
                           -- Rabbit suffers 3 frost damage from your Ice Nova.
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
                      PERIODICAURADAMAGESELFOTHER,
                      PvPLogMyDamage);
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
    PvPLogData[realm][player].version = VER_NUM;
    PvPLogData[realm][player].vendor = VER_VENDOR;
    PvPLogData[realm][player].notifyKillText = DEFAULT_KILL_TEXT;
    PvPLogData[realm][player].notifyDeathText = DEFAULT_DEATH_TEXT;

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
    PurgeLogData[realm][player].version = VER_NUM;
    PurgeLogData[realm][player].vendor = VER_VENDOR;

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
    PvPLogData[realm][player].version = VER_NUM;
    PvPLogData[realm][player].vendor = VER_VENDOR;
    PvPLogData[realm][player].enabled = true;
    PvPLogData[realm][player].display = true;
    PvPLogData[realm][player].ding = false;
    PvPLogData[realm][player].mouseover = true;
    PvPLogData[realm][player].MiniMap = { };
    PvPLogData[realm][player].dispLocation = "overhead";
    PvPLogData[realm][player].dingSound = "AuctionWindowOpen";
    PvPLogData[realm][player].dingTimeout = 30.0;
    PvPLogData[realm][player].notifyKill = NONE;
    PvPLogData[realm][player].notifyDeath = NONE;
    PvPLogData[realm][player].guilds = { };
    PvPLogData[realm][player].notifyKillText = DEFAULT_KILL_TEXT;
    PvPLogData[realm][player].notifyDeathText = DEFAULT_DEATH_TEXT;
end

function PvPLogInitPurge()
    PurgeLogData[realm][player] = { };
    PurgeLogData[realm][player].battles = { };
    PurgeLogData[realm][player].version = VER_NUM;
    PurgeLogData[realm][player].vendor = VER_VENDOR;
    PurgeLogData[realm][player].enabled = true;
    PurgeLogData[realm][player].display = true;
    PurgeLogData[realm][player].ding = false;
    PurgeLogData[realm][player].mouseover = true;
    PurgeLogData[realm][player].MiniMap = { };
    PurgeLogData[realm][player].showzone = "on";
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

    -- occasionally I was getting a nil value on the
    -- if statement after this one so this is a check
    -- to make sure that doesn't happen
    if (not PvPLogData[realm][player]) then
        return;
    end

    -- check to see if we're enabled
    if (not PvPLogData[realm][player].enabled) then
        return;
    end

--[[
    if (isDuel) then
        if (arg1) then
            local starti, endi = string.find(arg1, tostring(UnitName("player")));
            if (starti) then
                PvPLogDebugMsg(ORANGE.."Duel Msg: "..arg1);
                MarsMessageParser_ParseMessage("PvPLog_Duel", arg1);
            end
        end
    end
]]--
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
        total.winsStr = "1 "..WIN;
    elseif (total.wins > 1) then
        total.winsStr = total.wins .. " " .. WINS;
    else
        total.slashy = false;
    end

    if (total.loss == 1) then
        total.lossStr = "1 "..LOSS;
    elseif (total.loss > 1) then
        total.lossStr = total.loss .. " " .. LOSSES;
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

    if (guild == "") then
        return nil;
    end

    local total = { };
    local gfound = false;
    if (PvPLogData[realm][player].guilds ~= nil and
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
        total.winsStr = "1 "..WIN;
    elseif (total.wins > 1) then
        total.winsStr = total.wins .." "..WINS;
    else
        total.slashy = false;
    end

    if (total.loss == 1) then
        total.lossStr = "1 "..LOSS;
    elseif (total.loss > 1) then
        total.lossStr = total.loss .. " " .. LOSSES;
    end

    if (total.slashy and total.loss > 0) then
        total.slashy = " / ";
    else
        total.slashy = "";
    end

    return total;
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

function PvPLogRecord(vname, vlevel, vrace, vclass, vguild, venemy, win, vrank, vrealm)
    -- deal with vlevel being negative 1 when they're 10 levels
    -- or more greater
    local ZoneName = GetZoneText();
    local SubZone = GetSubZoneText();
    local level = 0;
    if (vlevel == -1) then
        level = plevel + 11; 
    elseif (vlevel) then
        level = vlevel; 
    end

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

    -- check to see if we've encountered this guild before
    if (not vguild or vguild == nil or vguild == "") then
        vguild = "Unguilded";
    end

    if (PvPLogData[realm][player].guilds == nil) then
        PvPLogData[realm][player].guilds = { };
    end

    if(table.getn(PvPLogData[realm][player].guilds) == 0 or
      PvPLogData[realm][player].guilds[vguild] == nil or
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
        PurgeLogData[realm][player].battles[PurgeCounter].name = { };
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
        PurgeLogData[realm][player].battles[PurgeCounter].honor = est_honor;
    end

    local bg_found = false;
    local bg_indx = 0;
    for i=1, MAX_BATTLEFIELD_QUEUES do
        bg_status, bg_mapName, bg_instanceId = GetBattlefieldStatus(i);
        if (bg_status == "active") then
            bg_found = true;
            bg_indx = i;
        end
    end
    if (bg_found) then
        PurgeLogData[realm][player].battles[PurgeCounter].bg = bg_indx;
    else
        PurgeLogData[realm][player].battles[PurgeCounter].bg = 0;
    end
    PurgeLogData[realm][player].battles[PurgeCounter].date = date();

    PurgeCounter = PurgeCounter + 1;
    PurgeLogData[realm][player].PurgeCounter = PurgeCounter;

    local x, y = GetPlayerMapPosition("player");
    x = math.floor(x*100);
    y = math.floor(y*100);
    local z = ZoneName;
    local notifyMsg = "";
    local notifySystem = nil;

    local vleveltext = vlevel or "";
    if( vlevel and vlevel < 0 ) then
        vleveltext = (-vlevel) .. "+";
    end

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

        -- clear the damager list as I lost/died
--        PvPLogDebugMsg('Recents cleared (recorded).');
--        recentDamaged = { };
--        recentDamager = { };
--        lastDamagerToMe = "";
    end

    notifyMsg = string.gsub( notifyMsg, "%%n", vname );
    notifyMsg = string.gsub( notifyMsg, "%%l", vleveltext );
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

    for notifyChan in string.gmatch(notifySystem, "%w+") do
        if( venemy and notifyChan == SELF) then
            PvPLogChatMsg(notifyMsg);
        elseif( venemy and
          ((notifyChan == PARTY and GetNumPartyMembers() > 0) or 
          (notifyChan == GUILD and GetGuildInfo("player") )  or 
          (notifyChan == RAID  and GetNumRaidMembers() > 0)) ) then
            if (notifyChan == RAID and bg_found) then
                notifyChan = BG;
            end
            PvPLogSendChatMessage(notifyMsg, notifyChan);
        elseif( venemy and notifyChan ~= NONE and notifyChan ~= SELF and
          notifyChan ~= PARTY and notifyChan ~= GUILD
          and notifyChan ~= RAID and notifyChan ~= BG) then
            PvPLogSendMessageOnChannel(notifyMsg, notifyChan);
        end
    end
end

-- This function is called whenever the player's target has changed.
-- In WoW V2, this is the only place where we can be sure of capturing
-- information about our target.
function PvPLogUpdateTarget(dueling)
    targetName, targetRealm = UnitName("target") 
    if (targetName) then
        -- We have a valid target
        if (dueling or (UnitIsPlayer("target") and UnitIsEnemy("player", "target"))) then
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
                targetRecords[targetName].level = UnitLevel("target");
                targetRecords[targetName].race = UnitRace("target");
                targetRecords[targetName].class = UnitClass("target");
                targetRecords[targetName].guild = GetGuildInfo("target");
                targetRecords[targetName].rank = UNKNOWN;
                MarsMessageParser_ParseMessage("PvPLog_GetRank", 
                    UnitPVPName("target"));
                targetRecords[targetName].rank = fullrank;
            end
        else
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
                index = table.foreach(targetList,
                    function(i,t)
                        if(t == targetName) then
                            return i;
                        end
                    end
                );
                PvPLogDebugMsg('Target removed: "' .. targetList[index] .. '"');
                targetRecords[targetName] = nil;
                table.remove(targetList,index);
            end
        end

        local total = PvPLogGetPvPTotals(targetName);
        local guildTotal = PvPLogGetGuildTotals(GetGuildInfo("target"));
        local msg = "";
        local show = false;
        if (total and (total.wins > 0 or total.loss > 0)) then
            msg = msg .. CYAN .. "PvP: " .. GREEN .. total.wins.. CYAN .. 
            " / " .. RED .. total.loss;
            show = true;
        end
        if (guildTotal and (guildTotal.wins > 0 or guildTotal.loss > 0)) then
            if (show) then
                msg = msg .. CYAN .. " - ";
            end
            msg = msg .. CYAN .. GUILD .. ": ";
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
        PvPLogChatMsgCyan("PvPLog " .. ORANGE .. OFF);
    else
        PvPLogData[realm][player].enabled = true;
        PvPLogChatMsgCyan("PvPLog " .. ORANGE .. ON);
    end        
end

function PvPLogSetDisplay(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].display = false;
        PvPLogChatMsgCyan("PvPLog Floating Display " .. ORANGE .. OFF);
    else
        PvPLogData[realm][player].display = true;
        PvPLogChatMsgCyan("PvPLog Floating Display " .. ORANGE .. ON);
    end        
end

function PvPLogSetDing(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].ding = false;
        PvPLogChatMsgCyan("PvPLog Ding Sound " .. ORANGE .. OFF);
    else
        PvPLogData[realm][player].ding = true;
        PvPLogChatMsgCyan("PvPLog Ding Sound " .. ORANGE .. ON);
    end        
end

function PvPLogSetMouseover(toggle)
    toggle = string.lower(toggle);
    if (toggle == "off") then
        PvPLogData[realm][player].mouseover = false;
        PvPLogChatMsgCyan("PvPLog Mouseover Effects " .. ORANGE .. OFF);
    else
        PvPLogData[realm][player].mouseover = true;
        PvPLogChatMsgCyan("PvPLog Mouseover Effects " .. ORANGE .. ON);
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
        else
            debug_indx = nil;
        end
        return;
    elseif (command == "vars") then
        if (softPL) then
            PvPLogDebugMsg(RED.."softPL: _"..PEACH.."TRUE"..RED.."_");
        else
            PvPLogDebugMsg(RED.."softPL: _"..PEACH.."FALSE"..RED.."_");
        end

        PvPLogDebugMsg("targetList = {"..table.concat(targetList,",").."}");
        s = "";
        for i in pairs(targetRecords) do
            s = s..","..i;
        end
        s = string.sub(s,2);
        PvPLogDebugMsg("targetRecords = {"..s.."}");

        PvPLogDebugMsg("ignoreList = {"..table.concat(ignoreList,",").."}");
        s = "";
        for i in pairs(ignoreRecords) do
            s = s..","..i;
        end
        s = string.sub(s,2);
        PvPLogDebugMsg("ignoreRecords = {"..s.."}");

        PvPLogDebugMsg("recentDamager = {"..table.concat(recentDamager,",").."}");
        PvPLogDebugMsg("recentDamaged = {"..table.concat(recentDamaged,",").."}");

        if (isDuel) then
            PvPLogDebugMsg("isDuel = TRUE");
        else
            PvPLogDebugMsg("isDuel = FALSE");
        end

        return;
    elseif (command == RESET) then
        if (value == CONFIRM) then
            PvPLogInitPvP();
            PvPLogInitPurge();
            PvPLogChatMsgCyan("PvPLog " .. MAGENTA .. RESET .. " " .. CYAN .. COMP);
        end
    elseif (command == NOTIFYKILLS) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyKill = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. NOTIFYKILLS .. 
                CYAN .. TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == NOTIFYDEATH) then
        if (value ~= nil) then
            PvPLogData[realm][player].notifyDeath = value;
            PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. NOTIFYDEATH .. 
                CYAN .. TO .. FIRE .. value);
        else
            PvPLogDisplayUsage();
        end
    elseif (command == ENABLE) then
        PvPLogSetEnabled("on");
    elseif (command == DISABLE) then
        PvPLogSetEnabled("off");
    elseif (command == VER) then
        PvPLogChatMsgCyan("PvPLog "..VER..": " .. WHITE .. VER_NUM);
    elseif (command == VEN) then
        PvPLogChatMsgCyan("PvPLog "..VEN..": " .. WHITE .. VER_VENDOR);
    elseif (command == DMG) then
        PvPLogPrintDamage();
    elseif (command == ST) then
        PvPLogPrintStats();
    elseif (command == DISPLAY) then
        if (value == nil) then
            PvPLogShowDisplayStatus();
        elseif (string.lower(value) == ENABLE) then
            PvPLogSetDisplay("on");
        elseif (string.lower(value) == DISABLE) then
            PvPLogSetDisplay("off");
        else
            PvPLogDisplayUsage();
        end
    elseif (command == DING) then
        if (value == nil) then
            PvPLogShowDingStatus();
        elseif (string.lower(value) == ENABLE) then
            PvPLogSetDing("on");
        elseif (string.lower(value) == DISABLE) then
            PvPLogSetDing("off");
        else
            PvPLogDisplayUsage();
        end
    elseif (command == MOUSEOVER) then
        if (value == nil) then
            PvPLogShowMouseoverStatus();
        elseif (string.lower(value) == ENABLE) then
            PvPLogSetMouseover("on");
         elseif (string.lower(value) == DISABLE) then
            PvPLogSetMouseover("off");
        else
            PvPLogDisplayUsage();
        end
    elseif (command == NOSPAM) then
        PvPLogSetDisplay("off");
        PvPLogSetDing("off");
        PvPLogSetMouseover("off");
    elseif (command == string.lower(UI_PVP)) then
        PvPLogStatsFrame:Hide();
        PVPLOG_STATS_TYPE = UI_PVP;
        PvPLogStatsFrame:Show();
    elseif (command == string.lower(DUEL)) then
        PvPLogStatsFrame:Hide();
        PVPLOG_STATS_TYPE = DUEL;
        PvPLogStatsFrame:Show();
    elseif (command == UI_CONFIG) then
        PvPLogConfigShow();
    else
        PvPLogDisplayUsage();
    end
end

function PvPLogShowDisplayStatus()
    local text;

    text = DISPLAY.." <";
    if (PvPLogData[realm][player].display) then
        text = text .. WHITE .. ENABLE .. CYAN .. " | "..DISABLE..">\n";
    else
        text = text .. ENABLE.." | " .. WHITE .. DISABLE .. CYAN .. ">\n";
    end
    PvPLogChatMsgPl(text);
end

function PvPLogShowDingStatus()
    local text;
    text = DING.." <";
    if (PvPLogData[realm][player].ding) then
        text = text .. WHITE .. ENABLE .. CYAN .. " | "..DISABLE..">\n";
    else
       text = text .. ENABLE.." | " .. WHITE .. DISABLE .. CYAN .. ">\n";
    end
    PvPLogChatMsgPl(text);
end

function PvPLogShowMouseoverStatus()
    local text;
    text = MOUSEOVER.." <";
    if (PvPLogData[realm][player].mouseover) then
        text = text .. WHITE .. ENABLE .. CYAN .. " | "..DISABLE..">\n";
    else
        text = text .. ENABLE.." | " .. WHITE .. DISABLE .. CYAN .. ">\n";
    end
    PvPLogChatMsgPl(text);
end

function PvPLogDisplayUsage()
    local text;

    text = CYAN .. USAGE .. ":\n  /pl <";
    if (PvPLogData[realm][player].enabled) then
        text = text .. WHITE .. ENABLE .. CYAN .. " | "..DISABLE..">\n";
    else
        text = text .. ENABLE.." | " .. WHITE .. DISABLE .. CYAN .. ">\n";
    end
    PvPLogChatMsg(text);

    PvPLogChatMsgPl(RESET.." "..CONFIRM.."\n");
    PvPLogChatMsgPl(ST.."\n");

    text = NOTIFYKILLS.." <";
    if (PvPLogData[realm][player].notifyKill == NONE) then
        text = text .. WHITE .. NONE .. CYAN;
    else
        text = text .. NONE;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == SELF) then
        text = text .. WHITE .. SELF .. CYAN;
    else
        text = text .. SELF;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == PARTY) then
        text = text .. WHITE .. PARTY .. CYAN;
    else
        text = text .. PARTY;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == GUILD) then
        text = text .. WHITE .. GUILD .. CYAN;
    else
        text = text .. GUILD;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyKill == RAID) then
        text = text .. WHITE .. RAID .. CYAN;
    else
        text = text .. RAID;
    end
    if (PvPLogData[realm][player].notifyKill ~= NONE and
        PvPLogData[realm][player].notifyKill ~= SELF and
        PvPLogData[realm][player].notifyKill ~= PARTY and
        PvPLogData[realm][player].notifyKill ~= GUILD and
        PvPLogData[realm][player].notifyKill ~= RAID) then
        text = text .." | " .. WHITE .. PvPLogData[realm][player].notifyKill .. CYAN .. ">\n";
    else
        text = text .. ">\n";
    end
    PvPLogChatMsgPl(text);

    text = NOTIFYDEATH.." <";
    if (PvPLogData[realm][player].notifyDeath == NONE) then
        text = text .. WHITE .. NONE .. CYAN;
    else
        text = text .. NONE;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == SELF) then
        text = text .. WHITE .. SELF .. CYAN;
    else
        text = text .. SELF;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == PARTY) then
        text = text .. WHITE .. PARTY .. CYAN;
    else
        text = text .. PARTY;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == GUILD) then
        text = text .. WHITE .. GUILD .. CYAN;
    else
        text = text .. GUILD;
    end
    text = text .." | ";
    if (PvPLogData[realm][player].notifyDeath == RAID) then
        text = text .. WHITE .. RAID .. CYAN;
    else
        text = text .. RAID;
    end
    if (PvPLogData[realm][player].notifyDeath ~= NONE and
        PvPLogData[realm][player].notifyDeath ~= SELF and
        PvPLogData[realm][player].notifyDeath ~= PARTY and
        PvPLogData[realm][player].notifyDeath ~= GUILD and
        PvPLogData[realm][player].notifyDeath ~= RAID) then
        text = text .. " | " .. WHITE .. PvPLogData[realm][player].notifyDeath .. CYAN .. ">\n";
    else
        text = text .. ">\n";
    end
    PvPLogChatMsgPl(text);

    PvPLogShowDisplayStatus();
    PvPLogShowDingStatus();
    PvPLogShowMouseoverStatus();
    PvPLogChatMsgPl(NOSPAM.."\n");
    PvPLogChatMsgPl(VER.."\n");
    PvPLogChatMsgPl(VEN.."\n");
    PvPLogChatMsgPl(string.lower(UI_PVP).."\n");
    PvPLogChatMsgPl(string.lower(DUEL).."\n");
    PvPLogChatMsgPl(UI_CONFIG.."\n");
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

function PvPLog_OnMouseDown(arg1)
    if (arg1 == "LeftButton") then
        PvPLogTargetFrame:StartMoving();
    end
end

function PvPLog_OnMouseUp(arg1)
    if (arg1 == "LeftButton") then
        PvPLogTargetFrame:StopMovingOrSizing();
    end
end