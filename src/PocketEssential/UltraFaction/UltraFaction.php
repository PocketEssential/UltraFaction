<?php

# _    _ _ _             ______         _   _                 
#| |  | | | |           |  ____|       | | (_)                
#| |  | | | |_ _ __ __ _| |__ __ _  ___| |_ _  ___  _ __  ___ 
#| |  | | | __| '__/ _` |  __/ _` |/ __| __| |/ _ \| '_ \/ __|
#| |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | \__ \
# \____/|_|\__|_|  \__,_|_|  \__,_|\___|\__|_|\___/|_| |_|___/
#
# Made by PocketEssential Copyright 2016 ©
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author: The PocketEssential Team
# Link: https://github.com/PocketEssential
#
#|------------------------------------------------- UltraFaction -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/UltraFaction/issues             |
#|----------------------------------------------------------------------------------------------------------------|

namespace PocketEssential\UltraFaction;


use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class UltraFaction extends PluginBase implements Listener
{

    /*
     * Registering the PREFIX!
     */
    const PREFIX = TextFormat::YELLOW . "[" . TextFormat::AQUA . "Faction" . TextFormat::YELLOW . "]";
    public $lang = [];
    public $data = null;
    public $db;
    public $economy;
    public $type;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new Listener($this), $this);
        $this->getCommand("f")->setExecutor(new Commands($this));

        @mkdir($this->getDataFolder());

        $this->config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
	
	if ($this->getConfig()->get("Economy") == "EconomyAPI") {
            $eco = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI');
            if ($eco == false) {
                $this->getLogger()->info("|| EconomyAPI cannot be found ||");
            } else {
                $this->economy = $eco;
                $this->type = "EconomyAPI";
            }
        }elseif ($this->plugin->getConfig()->get("Economy") == "MassiveEconomy") {
            $eco = $this->plugin->getServer()->getPluginManager()->getPlugin('MassiveEconomy');
            if ($eco == false) {
                $this->plugin->getLogger()->info("|| MassiveEconomy cannot be found ||");
            } else {
                $this->economy = $eco;
                $this->type = "MassiveEconomy";
            }
        }
	    
        $this->getLogger()->info("---------------------------------------");
        $this->getLogger()->info("Using Language: " . $this->lang);
        $this->getLogger()->info("Data-Provider: " . $this->data);
	$this->getLogger()->info("Economy: " . $this->type);
        $this->getLogger()->info("|| Everything has been loaded ||| ");
        $this->getLogger()->info("----------------------------------------");
        $this->dateProvider();

    }

    public function onDisable()
    {
        $this->saveFile();
    }

    /*
     *  For easy access, for saving configs / resources.
     */

    public function saveFile()
    {
        $this->getLogger()->info(TextFormat::YELLOW . "|| Saving all files ||");
        $this->getConfig()->save();
        $this->getLogger()->info(TextFormat::DARK_BLUE . "All config / files has been saved!");
    }

    public function getPlayerFaction(Player $player)
    {

        if ($this->getDataProvider() == "yaml" or "yml") {

            $f = (new Config($this->getDataFolder() . "/factions/"));

            $faction = substr($f, strpos($f, "_") + 1);
            return $faction;
        }
        if ($this->getDataProvider() == "sqlite" or "lite") {
            $this->getLogger()->info(UltraFaction::PREFIX . " You are trying to use " . $this->getDataProvider() . " as data provider! It has not been added yet!");
            // Todo
        }
        if ($this->getDataProvider() == "mysql" or "sql") {
            $this->getLogger()->info(UltraFaction::PREFIX . " You are trying to use " . $this->getDataProvider() . " as data provider! It has not been added yet!");
            //Todo
        }
    }
	
    public function isSameFaction($player1, $player2){
	return $this->getPlayerFaction($player1) == $this->getPlayerFaction($player2);
    }

    public function IsPlayerInFaction(Player $player){
	// Todo
    }

    
    public function addPlayerToFaction(Player $player, $faction_name){
        if($this->existsFaction($faction_name)){
            if($this->getServer()->getPlayer($player) !== null && $this->getServer()->getPlayer($player) instanceof Player){
                $faction = (new Config($this->getDataFolder()."/factions/".$faction_name.".yml", Config::YAML));
                $add = $faction->get("members", []);
		$add[] = $player->getName();
                $faction->set("members", $add);
                $faction->save();
            }
        }
    }
    public function removePlayerFromFaction(Player $player, $faction_name){
        if($this->existsFaction($faction_name)){
            if($this->getServer()->getPlayer($player) !== null && $this->getServer()->getPlayer($player) instanceof Player){
                $faction = (new Config($this->getDataFolder()."/factions/".$faction_name.".yml", Config::YAML));
                $members = $faction->get("members");
		foreach($members as $fac_members){
		    if($fac_members == $player->getName()){
			$toremove = array_search($player->getName(), $fac_members);
			if($toremove){
			    unset($fac_members[$toremove]);
			}
		    }
		}
                $faction->save();
            }
        }
    }
    
    public function getFactionLeader($faction_name){
        if($this->existsFaction($faction_name)){
            $dir = scandir($this->getDataFolder()."/faction/"); //todo
        }
    }
    
    public function existsFaction($faction_name){
        $scandir = scandir($this->getDataFolder()."/factions/");
        foreach($scandir as $dirs){
            return $dirs == $faction_name.".yml";
        }
    }
    
    public function createFaction(Player $player, $faction_name){
        if(!$this->IsPlayerInFaction($player)){
            if(!$this->existsFaction($faction_name)){
                $name = $player->getName();
                $faction = (new Config($this->getDataFolder() . "/factions/".$faction_name.".yml", Config::YAML));
                $faction->set("Leader", $player->getName());
                $faction->save();
            } else {
                $player->sendMessage(UltraFaction::PREFIX . " That faction name exists, Try another name.");
            }
        } else {
            $player->sendMessage(UltraFaction::PREFIX . " You are already in faction!");
        }
    }

    /*
     *  Languages, getting it the EASY way BOII
     */

    public function getLanguage()
    {
        $langType = $this->getConfig()->get("Language");

        if (!is_dir($this->getDataFolder()."/Languages/")) {
            mkdir('/Languages', 0777, true);
        }
        if (!(is_dir($this->getDataFolder() . "Languages/ $langType.yml"))) {
            $this->saveResource("Languages/$langType.yml");
            $langGet = (new Config($this->getDataFolder() . "Languages/" . $langType . ".yml", Config::YAML));
            $string = null;
            $this->getLanguageText($string);
            $this->lang = $langGet;

            $this->getLogger()->info(TextFormat::DARK_BLUE . "Using" . $langType . " as the Language!");
        }
    }

    /*
     *  Simple API to be used by (US) or other developers! )
     */
    public function getLanguageText($string)
    {

        if ($string == null) {
            return "Please provide a string to translate!";

        }

        /*
         * Getting the type of text they want to translate!
        */

       	switch($string){
		case  "Created_A_Faction":
		    return $this->lang->get("Created_A_Faction");
		break;
		case  "Faction_Already_Exist":
		    return $this->lang->get("Faction_Already_Exist");
    		break;
        	case "Faction_Too_Short" :
        	    return $this->lang->get("Faction_Too_Short");
        	break;
       		 case "Faction_Too_Long" :
       		     return $this->lang->get("Faction_Too_Long");
        	break;
        	case "Already_In_Faction" :
        	    return $this->lang->get("Already_In_Faction");
        	break;
        	case "Faction_Cost_Reach" :
        	    return $this->lang->get("ction_Cost_Reach");
        	break;
        	case "Faction_Set_Description" :
        	    return $this->lang->get("Faction_Set_Description");
        	break;
        	case "Faction_Reset" :
        	    return $this->lang->get("Faction_Reset");
        	break;
        	case "Faction_Remove" :
        	    return $this->lang->get("Faction_Remove");
        	break;
        	case "Faction_Home_Teleport" :
        	    return $this->lang->get("Faction_Home_Teleport");
        	break;
        	case "Faction_No_Home" :
        	    return $this->lang->get("Faction_No_Home");
        	break;
        	case "Faction_No_Home_Set" :
        	    return $this->lang->get("Faction_No_Home_Set");
        	break;
        	case "Faction_Rename" :
        	    return $this->lang->get("Faction_Rename");
        	break;
        	case "Invited" :
        	    return $this->lang->get("Invited");
        	break;
        	case "No_Permission_To_Invite" :
        	    return $this->lang->get("No_Permission_To_Invite");
        	break;
        	case "No_Permission_To_Promote" :
        	    return $this->lang->get("No_Permission_To_Promote");
        	break;
        	case "No_Faction" :
        	    return $this->lang->get("No_Faction");
        	break;
        	case "No_Faction_To_Do" :
        	    return $this->lang->get("No_Faction_To_Do");
        	break;
        	case "Faction_Description_Field_Empty" :
        	    return $this->lang->get("Faction_Description_Field_Empty");
        	break;
	}
    }

    public function getDataProvider()
    {
        if ($this->config->get("data-provider") == "sqlite") {
            $this->data = "sqlite";
            return $this->data;
        }
        if ($this->config->get("data-provider") == "mysql") {
            $this->data = "mysql";
            return $this->data;
        }
        if ($this->config->get("data-provider") == "yaml") {
            $this->data = "yaml";
            return $this->data;
        }
    }

    // Prefix to use in other Plugins
    public static function getPrefix(){
        return UltraFaction::PREFIX;
    }

    public function getEconomyType(){
        return $this->getConfig()->get("Economy");
    }

    public function getEconomy(){
	return Economy::getInstance();
    }
	
    public function dateProvider(){
     if($this->data == "sqlite"){
         $this->db = new \SQLite3($this->getDataFolder() . "UltraFaction.db");
         $this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, faction TEXT, rank TEXT);");
         $this->db->exec("CREATE TABLE IF NOT EXISTS confirm (player TEXT PRIMARY KEY COLLATE NOCASE, faction TEXT, invitedby TEXT, timestamp INT);");
         $this->db->exec("CREATE TABLE IF NOT EXISTS motd (faction TEXT PRIMARY KEY, message TEXT);");
         $this->db->exec("CREATE TABLE IF NOT EXISTS claim (faction TEXT PRIMARY KEY, x1 INT, z1 INT, x2 INT, z2 INT);");
         $this->db->exec("CREATE TABLE IF NOT EXISTS home (faction TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT);");
	 $this->db->exec("CREATE TABLE IF NOT EXISTS stats (faction TEXT PRIMARY KEY, kills INT, deaths INT;);");
     }
    }
}
