<?php



/**
 *
 * 8888888b.                   888               888    8888888888                                    888    d8b          888
 * 888   Y88b                  888               888    888                                           888    Y8P          888
 * 888    888                  888               888    888                                           888                 888
 * 888   d88P .d88b.   .d8888b 888  888  .d88b.  888888 8888888   .d8888b  .d8888b   .d88b.  88888b.  888888 888  8888b.  888
 * 8888888P" d88""88b d88P"    888 .88P d8P  Y8b 888    888       88K      88K      d8P  Y8b 888 "88b 888    888     "88b 888
 * 888       888  888 888      888888K  88888888 888    888       "Y8888b. "Y8888b. 88888888 888  888 888    888 .d888888 888
 * 888       Y88..88P Y88b.    888 "88b Y8b.     Y88b.  888            X88      X88 Y8b.     888  888 Y88b.  888 888  888 888
 * 888        "Y88P"   "Y8888P 888  888  "Y8888   "Y888 8888888888 88888P'  88888P'  "Y8888  888  888  "Y888 888 "Y888888 888
 *
 * Copyright (C) 2016 PocketEssential
 *
 * This is a public software, you cannot redistribute it a and/or modify any way
 * unless otherwise given permission to do so.
 *
 * @author PocketEssential
 * @link https://github.com/PocketEssential/
 *
 */

namespace PocketEssential\UltraFaction;


use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;

class UltraFaction extends PluginBase implements Listener
{

    /*
     * Registering the PREFIX!
     */
    const PREFIX = TextFormat::YELLOW . "[" . TextFormat::AQUA . "Faction" . TextFormat::YELLOW . "]";
    public $lang = [];
    public $data = null;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new Listener($this), $this);
        $this->getCommand("f")->setExecutor(new Commands($this));

        @mkdir($this->getDataFolder());

        $this->config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);

        $this->getLogger()->info("---------------------------------------");
        $this->getLogger()->info("Using Language: " . $this->lang);
        $this->getLogger()->info("Data-Provider: " . $data);
        $this->getLogger()->info("|| Everything has been loaded ||| ");
        $this->getLogger()->info("----------------------------------------");

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

        if($this->getDataProvider() == "yaml" or "yml") {

            $f = (new Config($this->getDataFolder() . "/factions/"));

            $faction = substr($f, strpos($f, "_") + 1);
            return $faction;
        }
        if($this->getDataProvider() == "sqlite" or "lite"){
            $this->getLogger()->info(UltraFaction::PREFIX." You are trying to use ".$this->getDataProvider()." as data provider! It has not been added yet!");
            // Todo
        }
        if($this->getDataProvider() == "mysql" or "sql"){
             $this->getLogger()->info(UltraFaction::PREFIX." You are trying to use ".$this->getDataProvider()." as data provider! It has not been added yet!");
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
                $faction = (new Config($this->getDataFolder()."/factions/".$this->getFactionLeader($faction_name)."_".$faction_name.".yml", Config::YAML));
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
                $faction = (new Config($this->getDataFolder()."/factions/".$this->getFactionLeader($faction_name)."_".$faction_name.".yml", Config::YAML));
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
            $scandir = scandir($this->getDataFolder()."/factions/");
            foreach($scandir as $dirs){
                $faction_data_name = substr($dirs, strpos($dirs, "_") + 1);
                $leader = strstr($dirs, '_', true);
                if($faction_data_name == $faction_name){
                    return $leader;
                }
            }
        }
    }
    
    public function existsFaction($faction_name){
        $scandir = scandir($this->getDataFolder()."/factions/");
        foreach($scandir as $dirs){
            $faction_data_name = substr($dirs, strpos($dirs, "_") + 1);
            if($dirs == $faction_name){
                return true;
            } else {
                return false;
            }
        }
    }
    
    public function createFaction(Player $player, $faction_name){
        if(!$player->IsPlayerInFaction($player)){
            if(!$this->existsFaction($faction_name)){
                $name = $player->getName();
                $faction = (new Config($this->getDataFolder() . "/factions/". $name ."_". $faction_name .".yml", Config::YAML));
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
        $langType = $this->getonfig->get("Language");

        if (!file_exists('/Languages/')) {
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
     *  Simple API to be used by (U-S )
     */
    public function getLanguageText($string)
    {

        if ($string == null) {
            return "Please provide a string to translate!";

        }

        /*
         * Getting the type of text they want to translate!
        */

        if ($string == "Created_A_Faction") {
            return $this->lang->get("Created_A_Faction");
        }
        if ($string == "Faction_Already_Exist") {
            return $this->lang->get("Faction_Already_Exist");
        }
        if ($string == "Faction_Too_Short") {
            return $this->lang->get("Faction_Too_Short");
        }
        if ($string == "Faction_Too_Long") {
            return $this->lang->get("Faction_Too_Long");
        }
        if ($string == "Already_In_Faction") {
            return $this->lang->get("Already_In_Faction");
        }
        if ($string == "Faction_Cost_Reach") {
            return $this->lang->get("ction_Cost_Reach");
        }
        if ($string == "Faction_Set_Description") {
            return $this->lang->get("Faction_Set_Description");
        }
        if ($string == "Faction_Reset") {
            return $this->lang->get("Faction_Reset");
        }
        if ($string == "Faction_Remove") {
            return $this->lang->get("Faction_Remove");
        }
        if ($string == "Faction_Home_Teleport") {
            return $this->lang->get("Faction_Home_Teleport");
        }
        if ($string == "Faction_No_Home") {
            return $this->lang->get("Faction_No_Home");
        }
        if ($string == "Faction_No_Home_Set") {
            return $this->lang->get("Faction_No_Home_Set");
        }
        if ($string == "Faction_Rename") {
            return $this->lang->get("Faction_Rename");
        }
        if ($string == "Invited") {
            return $this->lang->get("Invited");
        }
        if ($string == "No_Permission_To_Invite") {
            return $this->lang->get("No_Permission_To_Invite");
        }
        if ($string == "No_Permission_To_Promote") {
            return $this->lang->get("No_Permission_To_Promote");
        }
        if ($string == "No_Faction") {
            return $this->lang->get("No_Faction");
        }
        if ($string == "No_Faction_To_Do") {
            return $this->lang->get("No_Faction_To_Do");
        }
        if ($string == "Faction_Description_Field_Empty") {
            return $this->lang->get("Faction_Description_Field_Empty");
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
}
