--[[
    PvPLog 
    Author:           Brad Morgan
    Based on Work by: Josh Estelle, Daniel S. Reichenbach, Andrzej Gorski, Matthew Musgrove
    Version:          2.3.6
    Last Modified:    2007-02-01
]]

--Everything From here on would need to be translated and put
--into if statements for each specific language.

--***********
--ENGLISH (DEFAULT)
--***********

    -- Startup messages
    PVPLOG_STARTUP = "PvP Logger %v by %w AddOn loaded. Type /pl for options.";

    DESCRIPTION = "Keeps track of your PvP kills and the people who kill you.";
    
    -- Commands (must be one word and string.lower)
    RESET = "reset";
    ENABLE = "enable";
    DISABLE = "disable";
    VER = "version";
    VEN = "vendor";
    DISPLAY = "display";
    DING = "ding";
    MOUSEOVER = "mouseover";
    NOSPAM = "nospam";
    DMG = "damage";
    ST = "stats";
    NOTIFYKILL = "notifykill";
    NOTIFYKILLTEXT = "killtext";
    NOTIFYDEATH = "notifydeath";
    NOTIFYDEATHTEXT = "deathtext";
    UI_CONFIG = "config";
        
    -- Other needed phrases
    TO = " to ";
    ON = "on";
    OFF = "off";
    NONE = "none";
    CONFIRM = "confirm";
    USAGE = "Usage";
    
    STATS = "Statistics";
    COMP = "completely";
    
    SELF = "Self";
    PARTY = "Party";
    GUILD = "Guild";
    RAID = "Raid";
    RACE = "race";
    CLASS = "class";
    ENEMY = "enemy";
    REALM = "Realm";
    BG = "Battleground";
        
    -- The following are not being used currently
--  AB = "Arathi Basin";
--  AV = "Alterac Valley";
--  WSG = "Warsong Gulch";
    
    WIN = "win";
    LOSS = "loss";
    WINS = "wins";
    LOSSES = "losses";
    
    PLAYER = "Player";
    RECENT = "Recent";
    DUEL = "Duel";
    TOTAL = "Total";
    STATS = "Statistics";
    ALD = "Avg Level Diff";
    
    DLKB = "Death logged, killed by: ";
    KL = "Kill logged: ";
    DWLA = "Duel win logged against: ";
    DLLA = "Duel loss logged against: ";
    
    -- Gank levels
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
    
    -- Default display text for notify
    DEFAULT_KILL_TEXT = "I killed %n (Level %l %r %c) at [%x,%y] in %z (%w).";
    DEFAULT_DEATH_TEXT = "%n (Level %l %r %c) killed me at [%x,%y] in %z (%w).";
    
    UI_OPEN = "Open";
    UI_CLOSE = "Close";
    UI_NOTIFY_KILLS = "Notify kills to:";
    UI_NOTIFY_DEATHS = "Notify deaths to:";
    UI_CUSTOM = "Custom";
    UI_ENABLE = "Enable PvPLog";
    UI_MOUSEOVER = "Mouseover effects";
    UI_DING = "Audio Ding";
    UI_DISPLAY = "Floating text messages";
    UI_NOTIFY_NONE = "None";
    UI_DING_TIP = "When you mouse-over a player you\nhave fought before a sound will play.";
    UI_PVP = "PvP";
    UI_NAME = "Name";
    UI_WINS = "Wins";
    UI_LOSSES = "Losses";
    UI_TOGGLE = "Toggles " .. UI_CONFIG;
    UI_TOGGLE2 = "Toggles " .. STATS;
    UI_RIGHT_CLICK = "Right click: ";
    UI_LEFT_CLICK = "Left click: ";
    UI_MINIMAP_BUTTON = "Minimap Button";
    UI_RECORD_BG = "Record in Battlegrounds";
    UI_RECORD_DUEL = "Record Duels";
    UI_NOTIFY_BG = "Notify in Battlegrounds";
    UI_NOTIFY_DUEL = "Notify Duels";

--***********
-- GERMAN
--***********
if (GetLocale() == "deDE") then
    -- Translated by (): yamyam

    -- Startup messages
    PVPLOG_STARTUP = "PvP Logger %v von %w AddOn geladen. Tippe /pl für Optionen.";
    
    DESCRIPTION = "Zeichnet PvP Siege und Verluste auf, sowie Duelle.";

    -- Commands (must be one word and string.lower)
    RESET = "zurücksetzen";
    ENABLE = "einschalten";    
    DISABLE = "ausschalten";  
    VER = "version";      -- version? versionsnummer?
    VEN = "vendor";       -- verkufer?
    DISPLAY = "anzeige";  
    DING = "ding";
    MOUSEOVER = "mouseover";
    NOSPAM = "nospam";
    DMG = "schaden";
    ST = "stats";
    NOTIFYKILL = "killanzeige";
    NOTIFYKILLTEXT = "killtext";
    NOTIFYDEATH = "todesanzeige";
    NOTIFYDEATHTEXT = "deathtext";
    UI_CONFIG = "konfiguration";
    
    -- Other needed phrases
    TO = " to ";
    ON = "an";
    OFF = "aus";
    NONE = "keine";        
    CONFIRM = "bestätigen";  
    USAGE = "Usage";      -- verwenden?
    
    STATS = "Statistik";
    COMP = "komplett";
    
    SELF = "Self";
    PARTY = "Gruppe";
    GUILD = "Gilde";
    RAID = "Schlachtzug";
    RACE = "Rasse";
    CLASS = "Klasse";
    ENEMY = "Feind";
    BG = "Schlachtfeld";
    
--  AB = "Arathibecken";
--  AV = "Alteractal";
--  WSG = "Warsongschlucht";

    
    WIN = "sieg";
    LOSS = "verlor";
    WINS = "siege";
    LOSSES = "verloren";
    
    PLAYER = "Player";
    RECENT = "Recent";
    DUEL = "Duell";
    TOTAL = "Summe";
    STATS = "Statistik";
    ALD = "Durchschnittlicher Levelunterschied";
        
    DLKB = "Tod geloggt, getötet von: ";
    KL = "Tod geloggt: ";
    DWLA = "Duell gewonnen gegen: ";
    DLLA = "Duell verloren gegen: ";

    -- Gank levels
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

    -- Default display text for notify
    DEFAULT_KILL_TEXT = "Ich habe %n (Level %l %r %c) bei [%x,%y] in %z (%w) getötet.";
    DEFAULT_DEATH_TEXT = "%n (Level %l %r %c) hat mich bei [%x,%y] in %z (%w) getötet.";
    
    UI_OPEN = "Öffnen";
    UI_CLOSE = "Schließen";
    UI_NOTIFY_KILLS = "Kills anzeigen in:";
    UI_NOTIFY_DEATHS = "Tode anzeigen in:";
    UI_CUSTOM = "Custom";
    UI_ENABLE = "PvPLog einschalten";
    UI_MOUSEOVER = "Mouseover Effekte";
    UI_DING = "Audio Ding-Sound";
    UI_DISPLAY = "Floating text messages";
    UI_NOTIFY_NONE = "Keine";
    UI_DING_TIP = "When you mouse-over a player you\nhave fought before a sound will play.";
    UI_PVP = "PvP";
    UI_NAME = "Name";
    UI_WINS = "Siege";
    UI_LOSSES = "Verloren";
    UI_TOGGLE = UI_CONFIG .. " anzeigen/verbergen";
    UI_RIGHT_CLICK = "Rechtsklick: ";
    UI_LEFT_CLICK = "Linksklick: ";
    UI_MINIMAP_BUTTON = "Minimap Button";
    UI_RECORD_BG = "Record in Battlegrounds";
    UI_RECORD_DUEL = "Record Duels";
    UI_NOTIFY_BG = "Notify in Battlegrounds";
    UI_NOTIFY_DUEL = "Notify Duels";

elseif (GetLocale() == "frFR") then
    -- Translated by (): Exerladan

    -- Startup messages
    PVPLOG_STARTUP = "PvP Logger %v par %w chargé. Tapez /pl pour les options.";
    
    DESCRIPTION = "Enregistre les victoires et les défaites JcJ.";

    -- Commands (must be one word and string.lower)
    RESET = "raz";      -- 
    ENABLE = "activer";    -- permettre?
    DISABLE = "desactiver";  -- 
    VER = "version";      -- version?
    VEN = "fournisseur";       -- fournisseur?
    DISPLAY = "montre";  -- montrer?
    DING = "ding";
    MOUSEOVER = "sourisaudessus";
    NOSPAM = "sansspam";
    DMG = "degats";
    ST = "stats";
    NOTIFYKILL = "notifiervictoires";
    NOTIFYKILLTEXT = "textedevictoire";
    NOTIFYDEATH = "notifierdefaites";
    NOTIFYDEATHTEXT = "textededefaite";
    UI_CONFIG = "configuration";

    -- Other needed phrases
    TO = " à ";          -- ?
    ON = "actif";            -- sur?
    OFF = "inactif";          -- 
    NONE = "aucun";        -- aucun?
    CONFIRM = "confirmer";  -- confirmer?
    USAGE = "Utilisation";      -- utilisation?
    
    STATS = "Statistiques";
    COMP = "completement";
    
    SELF = "Soi";
    PARTY = "Groupe";
    GUILD = "Guilde";
    RAID = "Raid";
    RACE = "race";
    CLASS = "classe";
    ENEMY = "ennemi";
    BG = "Champ de bataille";

--  AB = "Bassin d'Arathi";
--  AV = "Vallée d'Alterac";
--  WSG = "Goulet de Chanteguerre";
    
    WIN = "victoire";
    LOSS = "défaite";
    WINS = "victoires";
    LOSSES = "défaites";
    
    WIN = "win";
    LOSS = "loss";
    WINS = "wins";
    LOSSES = "losses";
    
    PLAYER = "Player";
    RECENT = "Recent";
    DUEL = "Duel";
    TOTAL = "Total";
    STATS = "Statistiques";
    ALD = "différence moyenne de niveaux";
    
    DLKB = "Mort enregistrée, tué par : ";
    KL = "Victoire enregistrée : ";
    DWLA = "Duel gagnant logué contre : ";
    DLLA = "Duel perdant logué contre : ";
    
    -- Gank levels
    GL0 = "Combattant fair-play";
    GL_25 = "Je n'ai aucune pitié";
    GL_20 = "Ecrabouilleur de newbie";
    GL_15 = "Non sérieusement, sort un peu";
    GL_12 = "Sort un peu";
    GL_9 = "C'est en gankant, qu'on gank, qu'on gank";
    GL_6 = "Grand ganker";
    GL_3 = "Petit ganker";
    GL8 = "Je gank les maïtres du gank";
    GL5 = "Dieu du JcJ";
    GL4 = "Légende du JcJ";
    GL3 = "Ingankable";
    GL2 = "Essaie donc de me ganker";
    GL1 = "Dur à ganker";
    
    -- Default display text for notify
    DEFAULT_KILL_TEXT = "J'ai tué %n (Niveau %l %r %c) à [%x,%y] en %z (%w).";
    DEFAULT_DEATH_TEXT = "%n (Niveau %l %r %c) m'a tué à [%x,%y] en %z (%w).";

    UI_OPEN = "Ouvrir";
    UI_CLOSE = "Fermer";
    UI_NOTIFY_KILLS = "Notifier victoires :";
    UI_NOTIFY_DEATHS = "Notifier morts :";
    UI_CUSTOM = "Personnalisé";
    UI_ENABLE = "Activer PvPLog";
    UI_MOUSEOVER = "Effets passage de la souris";
    UI_DING = "Avertissement audio";
    UI_DISPLAY = "Messages texte flotant";
    UI_NOTIFY_NONE = "Aucun";
    UI_DING_TIP = "Quand vous passez avec la souris au dessus d'un joueur\nque vous avez combattu, vous entendrez un son.";
    UI_PVP = "JcJ";
    UI_NAME = "Nom";
    UI_WINS = "Victoires";
    UI_LOSSES = "Défaites";   
    UI_TOGGLE = "Montre " .. UI_CONFIG;
    UI_RIGHT_CLICK = "Clic droit : ";
    UI_LEFT_CLICK = "Clic gauche : ";
    UI_MINIMAP_BUTTON = "Bouton sur la mini-carte";
    UI_RECORD_BG = "Enregistrer sur les champs de bataille";
    UI_RECORD_DUEL = "Enregistrer les duels";
    UI_NOTIFY_BG = "Notifier sur les champs de bataille";
    UI_NOTIFY_DUEL = "Notifier les duels";
    
elseif (GetLocale() == "esES") then
-- Translated by (traducido por): NeKRoMaNT

    -- Startup messages
    PVPLOG_STARTUP = "PvP Logger %v por %w AddOn cargado. Mecanografiar /pl para las opciones.";
    
    DESCRIPTION = "Hace un seguimiento de tus asesinatos JcJ y de la gente que te ha asesinado.";

    -- Commands (must be one word and string.lower)
    RESET = "resetear";
    ENABLE = "activar";
    DISABLE = "desactivar";
    DISPLAY = "mostrar";
    DING = "ding";
    MOUSEOVER = "mouseover";
    NOSPAM = "nospam";
    DMG = "da\195\177o";
    ST = "estad\195\173sticas";
    NOTIFYKILL = "notificar asesinato"; -- "Aviso de Asesinatos"
    NOTIFYKILLTEXT = "texto asesinato";
    NOTIFYDEATH = "notificar muerte"; -- "Aviso de Muertes"
    NOTIFYDEATHTEXT = "texto muerte";
    UI_CONFIG = "configuraci\195\179n";

    -- Other needed phrases
    TO = " a ";
    ON = "Encendido";
    OFF = "Apagado";
    NONE = "Ninguno";
    CONFIRM = "Confirmar";
    VER = "Versi\195\179n";
    VEN = "Vendedor";
    USAGE = "Uso";
    
    STATS = "Estad\195\173sticas";
    COMP = "Completamente";
    
    SELF = "M\195\173";
    PARTY = "Grupo";
    GUILD = "Hermandad";
    RAID = "Banda";
    RACE = "Raza";
    CLASS = "Clase";
    ENEMY = "Enemigo";
    BG = "Campo de Batalla";
    
--  AB = "Cuenca de Arathi";
--  AV = "Valle de Alterac";
--  WSG = "Garganta Grito de Guerra";
    
    WIN = "gana";
    LOSS = "pierde";
    WINS = "Victorias";
    LOSSES = "Derrotas";
    
    PLAYER = "Jugador";
    RECENT = "Reciente";
    DUEL = "Duelo";
    TOTAL = "Total";
    STATS = "Estad\195\173sticas";
    ALD = "Diferencia de Nivel";
    
    DLKB = "Muerte grabada, asesinado por: ";
    KL = "Asesinato grabado: ";
    DWLA = "Victoria en duelo grabada contra: ";
    DLLA = "Derrota en duelo grabada contra: ";
    
    -- Gank levels
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
    
    -- Default display text for notify
    DEFAULT_KILL_TEXT = "He asesinado a %n (Nivel %l %r %c) en [%x,%y] en %z (%w).";
    DEFAULT_DEATH_TEXT = "%n (Nivel %l %r %c) me ha asesinado en [%x,%y] en %z (%w).";
       
    UI_OPEN = "Abrir";
    UI_CLOSE = "Cerrar";
    UI_NOTIFY_KILLS = "Notificar asesinatos a:";
    UI_NOTIFY_DEATHS = "Notificar muertes a:";
    UI_CUSTOM = "Personalizar";
    UI_ENABLE = "Activar PvPLog";
    UI_MOUSEOVER = "Efectos Mouseover";
    UI_DING = "Utilizar Audio";
    UI_DISPLAY = "Mensajes Emergentes";
    UI_NOTIFY_NONE = "Nadie";
    UI_DING_TIP = "Cuando pases el rat\195\179n sobre un jugador contra \nquien hayas luchado sonar\195\161 una se\195\177al.";
    UI_PVP = "JcJ";
    UI_NAME = "Nombre";
    UI_WINS = "Victorias";
    UI_LOSSES = "Derrotas";
    UI_RIGHT_CLICK = "Clic derecho: ";
    UI_LEFT_CLICK = "Clic izquierdo: ";
    UI_TOGGLE = "Muestra/oculta " .. UI_CONFIG;
    UI_MINIMAP_BUTTON = "Bot\195\179n de Minimapa";
    UI_RECORD_BG = "Historial en Campos de Batalla";
    UI_RECORD_DUEL = "Historial de Duelos";
    UI_NOTIFY_BG = "Notificar en Campos de Batalla";
    UI_NOTIFY_DUEL = "Notificar Duelos";

end
