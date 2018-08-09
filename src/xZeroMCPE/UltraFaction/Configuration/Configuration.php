<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:23 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration;


use pocketmine\utils\TextFormat;
use xZeroMCPE\UltraFaction\UltraFaction;

class Configuration
{

    public $configurations = [];

    const CONFIG = "Config";
    const FACTIONS = "Factions";
    const FACTIONS_PLAYER = "Factions_Player";

    public function __construct()
    {
        $this->loadConfiguration();
    }

    public function getDataFolder() : string {
        return UltraFaction::getInstance()->getDataFolder();
    }
    public function loadConfiguration(){

        if(!file_exists($this->getDataFolder() . "Config.json")){
            file_put_contents($this->getDataFolder() . "Config.json", json_encode(
                [
                    "### Please Read ###" => "Follow the format otherwise we won't be able to load this well!",
                    "Faction" => [
                        "Maximum faction name" => 16,
                        "Maximum number of warps" => 4,
                        "Faction creation cost" => 0,
                        "Starting power" => 20,
                        "Power loses per death" => 2,
                        "Starting bank balance" => 0
                    ],
                    "Data" => [
                        "Data Provider" => "json",
                        "Language" => "eng"
                    ]
            ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
            file_put_contents($this->getDataFolder() . "Factions.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
            file_put_contents($this->getDataFolder() . "FactionsID.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
        }

        $this->configurations[Configuration::CONFIG] = json_decode(file_get_contents($this->getDataFolder() . "Config.json"), true);
        $this->configurations[Configuration::FACTIONS] = json_decode(file_get_contents($this->getDataFolder() . "Factions.json"), true);
        $this->configurations[Configuration::FACTIONS_PLAYER] = json_decode(file_get_contents($this->getDataFolder() . "FactionsID.json"), true);
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
        UltraFaction::getInstance()->getLogger()->info("-           ULTRA FACTION             ");
        UltraFaction::getInstance()->getLogger()->info("-  ");
        UltraFaction::getInstance()->getLogger()->info("-  Language: " . $this->configurations[Configuration::CONFIG]['Data']['Language']);
        UltraFaction::getInstance()->getLogger()->info("-  Loaded a total of: " . count($this->configurations[Configuration::FACTIONS]). " factions!");
        UltraFaction::getInstance()->getLogger()->info("-  Enjoy and stay flexing!");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
    }

    public function getConfig() {
        return $this->configurations[Configuration::CONFIG];
    }

    public function handleShutdown(){

        file_put_contents($this->getDataFolder() . "Config.json", json_encode($this->configurations[Configuration::CONFIG]), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        file_put_contents($this->getDataFolder() . "Factions.json", json_encode(UltraFaction::getInstance()->getFactionManager()->getFactionsDump()), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        file_put_contents($this->getDataFolder() . "FactionsID.json", json_encode($this->configurations[Configuration::FACTIONS_PLAYER]), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}