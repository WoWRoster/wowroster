--[[
    PvPLogUI
    Author:           Atolicus
    Maintainer:       Matthew Musgrove, Brad Morgan
    Version:          2.3.0
    Last Modified:    2007-01-03
]]

local realm = "";
local player = "";
local CYAN    = "|cff00ffff";
local WHITE   = "|cffffffff";
local RED     = "|cffff0000";
local GREEN   = "|cff00ff00";
local MAGENTA = "|cffff00ff";
local FIRE    = "|cffde2413";
local ORANGE  = "|cffd06c01";
local statsValue = 1;
local statsTotal = 0;
startCount = 0;
endCount = 0;
statCount  = 0;
local pvpPlayerList = "";
local pvpGuildList = "";
local pvpWinsList = "";
local pvpLossList = "";
local pvpRealmList = "";
PVPLOG_STATS_TYPE = "";

-------------------
-- Configuration --
-------------------

function PvPLogConfig_OnLoad()
    realm = GetCVar("realmName");
    player = UnitName("player");
end

function PvPLogConfig_OnShow()
    PvPLogConfig_SetValues();
end

function PvPLogConfig_SetValues()
    txtPvPLogConfigFrame_HeaderText:SetText("PvPLog " .. VER_NUM);
    txtPvPLogConfig_NotifyKillsToggle_Header:SetText(UI_NOTIFY_KILLS);
    txtPvPLogConfig_NotifyDeathsToggle_Header:SetText(UI_NOTIFY_DEATHS);
    txtPvPLogConfig_NotifyKillsCustomChannel_Header:SetText(UI_CUSTOM);
    txtPvPLogConfig_NotifyDeathsCustomChannel_Header:SetText(UI_CUSTOM);
    cbxPvPLogConfig_MiniMapButtonToggle:SetChecked(PvPLogData[realm][player].MiniMap.enabled);
    
    cbxPvPLogConfig_EnableToggle:SetChecked(PvPLogData[realm][player].enabled);
    cbxPvPLogConfig_MouseoverToggle:SetChecked(PvPLogData[realm][player].mouseover);
    cbxPvPLogConfig_DingToggle:SetChecked(PvPLogData[realm][player].ding);
    cbxPvPLogConfig_DispToggle:SetChecked(PvPLogData[realm][player].display);
    cbxPvPLogConfig_RecordBGToggle:SetChecked(PvPLogData[realm][player].recordBG);
    cbxPvPLogConfig_RecordDuelToggle:SetChecked(PvPLogData[realm][player].recordDuel);
    cbxPvPLogConfig_NotifyBGToggle:SetChecked(PvPLogData[realm][player].notifyBG);
    cbxPvPLogConfig_NotifyDuelToggle:SetChecked(PvPLogData[realm][player].notifyDuel);

    if (PvPLogData[realm][player].recordBG) then
        cbxPvPLogConfig_NotifyBGToggle:Enable();
        cbxPvPLogConfig_NotifyBGToggleText:SetTextColor(NORMAL_FONT_COLOR.r,NORMAL_FONT_COLOR.g,NORMAL_FONT_COLOR.b);
    else
        cbxPvPLogConfig_NotifyBGToggle:Disable();
        cbxPvPLogConfig_NotifyBGToggleText:SetTextColor(GRAY_FONT_COLOR.r, GRAY_FONT_COLOR.g, GRAY_FONT_COLOR.b);
    end
    if (PvPLogData[realm][player].recordDuel) then
        cbxPvPLogConfig_NotifyDuelToggle:Enable();
        cbxPvPLogConfig_NotifyDuelToggleText:SetTextColor(NORMAL_FONT_COLOR.r,NORMAL_FONT_COLOR.g,NORMAL_FONT_COLOR.b);
    else
        cbxPvPLogConfig_NotifyDuelToggle:Disable();
        cbxPvPLogConfig_NotifyDuelToggleText:SetTextColor(GRAY_FONT_COLOR.r, GRAY_FONT_COLOR.g, GRAY_FONT_COLOR.b);
    end
    
    if (PvPLogData[realm][player].notifyKill == NIL) then
        PvPLogData[realm][player].notifyKill = NONE;
    end
    
    ebxPvPLogConfig_NotifyKillsChannel:SetText(PvPLogData[realm][player].notifyKill);
    
    if (PvPLogData[realm][player].notifyKill == NONE) then
        cbxPvPLogConfig_NotifyKillsNone:SetChecked(true);
        ebxPvPLogConfig_NotifyKillsChannel:SetText(NONE);
    else
        cbxPvPLogConfig_NotifyKillsNone:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyKill == SELF) then
        cbxPvPLogConfig_NotifyKillsSelf:SetChecked(true);
        ebxPvPLogConfig_NotifyKillsChannel:SetText(SELF);
    else
        cbxPvPLogConfig_NotifyKillsSelf:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyKill == PARTY) then
        cbxPvPLogConfig_NotifyKillsParty:SetChecked(true);
        ebxPvPLogConfig_NotifyKillsChannel:SetText(PARTY);
    else
        cbxPvPLogConfig_NotifyKillsParty:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyKill == GUILD) then
        cbxPvPLogConfig_NotifyKillsGuild:SetChecked(true);
        ebxPvPLogConfig_NotifyKillsChannel:SetText(GUILD);
    else
        cbxPvPLogConfig_NotifyKillsGuild:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyKill == RAID) then
        cbxPvPLogConfig_NotifyKillsRaid:SetChecked(true);
        ebxPvPLogConfig_NotifyKillsChannel:SetText(RAID);
    else
        cbxPvPLogConfig_NotifyKillsRaid:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyDeath == NIL) then
        PvPLogData[realm][player].notifyDeath = NONE;
    end
    
    ebxPvPLogConfig_NotifyDeathsChannel:SetText(PvPLogData[realm][player].notifyDeath);
    
    if (PvPLogData[realm][player].notifyDeath == NONE) then
        cbxPvPLogConfig_NotifyDeathsNone:SetChecked(true);
        ebxPvPLogConfig_NotifyDeathsChannel:SetText(NONE);
    else
        cbxPvPLogConfig_NotifyDeathsNone:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyDeath == SELF) then
        cbxPvPLogConfig_NotifyDeathsSelf:SetChecked(true);
        ebxPvPLogConfig_NotifyDeathsChannel:SetText(SELF);
    else
        cbxPvPLogConfig_NotifyDeathsSelf:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyDeath == PARTY) then
        cbxPvPLogConfig_NotifyDeathsParty:SetChecked(true);
        ebxPvPLogConfig_NotifyDeathsChannel:SetText(PARTY);
    else
        cbxPvPLogConfig_NotifyDeathsParty:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyDeath == GUILD) then
        cbxPvPLogConfig_NotifyDeathsGuild:SetChecked(true);
        ebxPvPLogConfig_NotifyDeathsChannel:SetText(GUILD);
    else
        cbxPvPLogConfig_NotifyDeathsGuild:SetChecked(false);
    end
    
    if (PvPLogData[realm][player].notifyDeath == RAID) then
        cbxPvPLogConfig_NotifyDeathsRaid:SetChecked(true);
        ebxPvPLogConfig_NotifyDeathsChannel:SetText(RAID);
    else
        cbxPvPLogConfig_NotifyDeathsRaid:SetChecked(false);
    end
end

function PvPLogConfig_OnHide()
    if (MYADDONS_ACTIVE_OPTIONSFRAME == this) then
        ShowUIPanel(myAddOnsFrame);
    end
end

function PvPLogConfig_OnMouseDown(arg1)
    if (arg1 == "LeftButton") then
        PvPLogConfigFrame:StartMoving();
    end
end

function PvPLogConfig_OnMouseUp(arg1)
    if (arg1 == "LeftButton") then
        PvPLogConfigFrame:StopMovingOrSizing();
    end
end

function PvPLogEnabled_Toggle_OnClick()
    if (PvPLogData[realm][player].enabled) then
        PvPLogSetEnabled("off");
    else
        PvPLogSetEnabled("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogMouseover_Toggle_OnClick()
    if (PvPLogData[realm][player].mouseover) then
        PvPLogSetMouseover("off");
    else
        PvPLogSetMouseover("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogDing_Toggle_OnClick()
    if (PvPLogData[realm][player].ding) then
        PvPLogSetDing("off");
    else
        PvPLogSetDing("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogDing_Toggle_OnEnter(button)
    GameTooltip:SetOwner(button, "ANCHOR_NONE");
    GameTooltip:SetPoint("TOPLEFT", button:GetName(), "BOTTOMLEFT", -10, -4);
    GameTooltip:SetText(UI_DING_TIP);
    GameTooltip:Show();
end;

function PvPLogDing_Toggle_OnLeave()
    GameTooltip:Hide();
end;

function PvPLogDisp_Toggle_OnClick()
    if (PvPLogData[realm][player].display) then
        PvPLogSetDisplay("off");
    else
        PvPLogSetDisplay("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogRecordBG_Toggle_OnClick()
    if (PvPLogData[realm][player].recordBG) then
        PvPLogSetRecordBG("off");
    else
        PvPLogSetRecordBG("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogRecordDuel_Toggle_OnClick()
    if (PvPLogData[realm][player].recordDuel) then
        PvPLogSetRecordDuel("off");
    else
        PvPLogSetRecordDuel("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogNotifyBG_Toggle_OnClick()
    if (PvPLogData[realm][player].notifyBG) then
        PvPLogSetNotifyBG("off");
    else
        PvPLogSetNotifyBG("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogNotifyDuel_Toggle_OnClick()
    if (PvPLogData[realm][player].notifyDuel) then
        PvPLogSetNotifyDuel("off");
    else
        PvPLogSetNotifyDuel("on");
    end
    PvPLogConfig_SetValues();
end

function PvPLogNotifyKills_Toggle_OnClick(value)
    PvPLogSlashHandler(NOTIFYKILL.." "..value);
    PvPLogConfig_SetValues();
end

function PvPLogNotifyDeaths_Toggle_OnClick(value)
    PvPLogSlashHandler(NOTIFYDEATH.." "..value);
    PvPLogConfig_SetValues();
end

function PvPLogConfigShow()
    if (not initialized) then
        PvPLogInitialize();
    end
    PvPLogData[realm][player].MiniMap.config = 1;
    PvPLogConfigFrame:Show();
end

function PvPLogConfigHide()
    if (not initialized) then
        PvPLogInitialize();
    end
    PvPLogData[realm][player].MiniMap.config = 0;
    PvPLogConfigFrame:Hide();
end

function PvPLogConfig_btnClose_OnClick()
    PvPLogConfigHide();
end

function PvPLogConfig_NotifyKillsCustomChannel_UpdateString()
    PvPLogData[realm][player].notifyKill = ebxPvPLogConfig_NotifyKillsChannel:GetText();
    PvPLogConfig_SetValues();
end

function PvPLogConfig_NotifyKillsCustomChannel_Message()
    local value = ebxPvPLogConfig_NotifyKillsChannel:GetText();
    PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. NOTIFYKILL .. CYAN .. TO .. FIRE .. value);  
end

function PvPLogConfig_NotifyDeathsCustomChannel_UpdateString()
    PvPLogData[realm][player].notifyDeath = ebxPvPLogConfig_NotifyDeathsChannel:GetText();
    PvPLogConfig_SetValues();
end

function PvPLogConfig_NotifyDeathsCustomChannel_Message()
    local value = ebxPvPLogConfig_NotifyDeathsChannel:GetText();
    PvPLogFloatMsg(CYAN .. "PvPLog: " .. WHITE .. NOTIFYDEATH .. CYAN .. TO .. FIRE .. value);
end

------------------------
-- PvP and Duel Stats --
------------------------

function PvPLogStatsShow()
    if (not initialized) then
        PvPLogInitialize();
    end
    PvPLogData[realm][player].MiniMap.stats = 1;
    PvPLogStatsFrame:Show();
end

function PvPLogStatsHide()
    if (not initialized) then
        PvPLogInitialize();
    end
    PvPLogData[realm][player].MiniMap.stats = 0;
    PvPLogStatsFrame:Hide();
end

function PvPLogStats_ShowTab(name)
    if (name == "PvP") then
        PVPLOG_STATS_TYPE = UI_PVP;
    elseif (name == "Recent") then
        PVPLOG_STATS_TYPE = RECENT;
    elseif (name == "Duel") then
        PVPLOG_STATS_TYPE = DUEL;
    end
    statsValue = 1;
    PvPLogStats_SetValues(statsValue);
end

function PvPLog_PvPLogStats_OnLoad()
    realm = GetCVar("realmName");
    player = UnitName("player");
end

function PvPLog_PvPLogStats_OnShow(statsType)
    statsValue = 1;
    PvPLogStats_SetValues(statsValue);
end

function PvPLogStats_SetValues(statsValue)
    local isEnemy;
    if (PVPLOG_STATS_TYPE == UI_PVP) then
        txtPvPLogStatsFrame_HeaderText:SetText("PvPLog " .. VER_NUM .. "  -  " .. UI_PVP .. " " ..STATS);
        isEnemy = 1;
    elseif (PVPLOG_STATS_TYPE == DUEL) then
        txtPvPLogStatsFrame_HeaderText:SetText("PvPLog " .. VER_NUM .. "  -  " .. DUEL .. " " ..STATS);
        isEnemy = 0;
    elseif (PVPLOG_STATS_TYPE == RECENT) then
        txtPvPLogStatsFrame_HeaderText:SetText("PvPLog " .. VER_NUM .. "  -  " .. RECENT .. " " ..STATS);
        isEnemy = 1;
    else
        return;
    end
    txtPvPLogStats_PlayersHeader:SetText(CYAN .. UI_NAME);
    txtPvPLogStats_GuildsHeader:SetText(MAGENTA .. GUILD);
    if (PVPLOG_STATS_TYPE == RECENT) then
        txtPvPLogStats_RealmsHeader:SetText(ORANGE .. PLAYER);
    else
        txtPvPLogStats_RealmsHeader:SetText(ORANGE .. REALM);
    end
    txtPvPLogStats_WinsHeader:SetText(GREEN .. UI_WINS);
    txtPvPLogStats_LossesHeader:SetText(RED .. UI_LOSSES);
    txtPvPLogStats_PlayerList:SetText("");
    txtPvPLogStats_GuildList:SetText("");
    txtPvPLogStats_RealmsList:SetText("");
    txtPvPLogStats_WinsList:SetText("");
    txtPvPLogStats_LossesList:SetText("");
    pvpPlayerList = "";
    pvpGuildList = "";
    pvpRealmList = "";
    pvpWinsList = "";
    pvpLossList = "";
    startCount = ((statsValue*30+1)-30);
    endCount = (statsValue*30);
    statCount  = 1;
    statsTotal = 0;

    if (PVPLOG_STATS_TYPE == RECENT) then
        local now = time();
        local dayago = now - 24 * 60 * 60;
        PvPLogDebugMsg('now = '..tostring(now)..', dayago = '..tostring(dayago));
        table.foreach(PurgeLogData[realm], function( character, v1 )
            PvPLogDebugMsg('character = '..tostring(character));
            table.foreach(PurgeLogData[realm][character].battles, function( counter, v2 )
                if (v2.bg and v2.bg == 0 and v2.time and v2.time > dayago and v2.enemy == isEnemy) then
                    if (statCount >= startCount and statCount <= endCount) then
                        pvpPlayerList = pvpPlayerList..v2.name.."\n";
                        if (v2.guild) then
                            pvpGuildList = pvpGuildList..v2.guild.."\n";
                        else
                            pvpGuildList = pvpGuildList.." ".."\n";
                        end
                        pvpRealmList = pvpRealmList..character.."\n";
                        pvpWinsList = pvpWinsList..v2.win.."\n";
                        pvpLossList = pvpLossList..1-v2.win.."\n";
                    end
                    statCount = statCount + 1;
                    statsTotal = statsTotal + 1;
                end
            end);
        end);
    else
        table.foreach( PvPLogData[realm][player].battles, function( name, v1 )
            if ((statCount >= startCount) and (statCount <= endCount) and (v1.enemy == isEnemy)) then
                pvpPlayerList = pvpPlayerList..name.."\n";

                local GuildNotFound = true;
                local RealmNotFound = true;
                table.foreach(PurgeLogData[realm][player].battles, function( counter, v2 )
                    if (name == v2.name) then
                        if (GuildNotFound and v2.guild) then
                            pvpGuildList = pvpGuildList..v2.guild.."\n";
                            GuildNotFound = false;
                        end
                        if (RealmNotFound and v2.realm) then
                            pvpRealmList = pvpRealmList..v2.realm.."\n";
                            RealmNotFound = false;
                        end
                    end
                end);
                if (GuildNotFound) then
                    pvpGuildList = pvpGuildList.." ".."\n";
                end
                if (RealmNotFound) then
                    pvpRealmList = pvpRealmList.." ".."\n";
                end
                pvpWinsList = pvpWinsList..v1.wins.."\n";
                pvpLossList = pvpLossList..v1.loss.."\n";
            end
            statCount = statCount + 1;
            statsTotal = statsTotal + 1;
        end);
    end
    txtPvPLogStats_PlayerList:SetText(CYAN .. pvpPlayerList);
    txtPvPLogStats_GuildList:SetText(MAGENTA .. pvpGuildList);
    txtPvPLogStats_RealmsList:SetText(ORANGE .. pvpRealmList);
    txtPvPLogStats_WinsList:SetText(GREEN .. pvpWinsList);
    txtPvPLogStats_LossesList:SetText(RED .. pvpLossList);
end

function PvPLog_PvPLogStats_OnHide()
    if (MYADDONS_ACTIVE_OPTIONSFRAME == this) then
        ShowUIPanel(myAddOnsFrame);
    end
end

function PvPLog_PvPLogStats_OnMouseDown(arg1)
    if (arg1 == "LeftButton") then
        PvPLogStatsFrame:StartMoving();
    end
end

function PvPLog_PvPLogStats_OnMouseUp(arg1)
    if (arg1 == "LeftButton") then
        PvPLogStatsFrame:StopMovingOrSizing();
    end
end

function PvPLog_btnPvPLogStats_Close_OnClick()
    PvPLogStatsHide();
end

function PvPLog_btnPvPLogStats_Previous_OnClick()
    if (statsValue > 1) then
        statsValue = statsValue - 1;
    end
    PvPLogStats_SetValues(statsValue);
end

function PvPLog_btnPvPLogStats_Next_OnClick()
    if (statsValue < (statsTotal/5)) then
        statsValue = statsValue + 1;
    end
    PvPLogStats_SetValues(statsValue);
end

function MiniMapButton_Toggle_OnClick()
    if(PvPLogData[realm][player].MiniMap.enabled == 1) then
        MyMinimapButton:SetEnable("PvPLog", 0)
    else
        PvPLogData[realm][player].MiniMap = PvPLogMinimapButtonInit();
        PvPLogCreateMinimapButton();
        MyMinimapButton:SetEnable("PvPLog", 1)
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

