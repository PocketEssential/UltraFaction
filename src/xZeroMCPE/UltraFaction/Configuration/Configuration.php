<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:23 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration;


use pocketmine\utils\TextFormat;
use xZeroMCPE\UltraFaction\Configuration\Language\Language;
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
        return UltraFaction::getInstance()->getServer()->getDataPath() . "UltraFaction/";
    }

    public function loadConfiguration(){

        if(!file_exists($this->getDataFolder() . "Config.json")){
            @mkdir($this->getDataFolder());

            file_put_contents($this->getDataFolder() . "Config.json", json_encode(
                [
                    "### Please Read ###" => "Follow the format otherwise we won't be able to load this well!",
                    "Faction" => [
                        "Maximum faction name" => 16,
                        "Maximum number of warps" => 4,
                        "Faction creation cost" => 0,
                        "Starting power" => 20,
                        "Max amount of power" => 20,
                        "Power loses per death" => 2,
                        "Starting bank balance" => 0,
                        "Default description" => "Update me with /f setdescription <..your choice>",
                        "### 0 = Disable, >= 1 (deaths until raidable, hehe)",
                        "Deaths until raidable" => 0,
                        "Damage" => [
                            "Friendly fire" => false,
                        ],
                        "Max faction claim" => 4,
                        "Disallow commands while in enemeny Territory" => [
                            '/spawn',
                            '/home',
                            '/tpa',
                            '/sethome',
                            '/homes',
                            '/tpall',
                            '/warp'
                        ]
                    ],
                    "Data" => [
                        "Data Provider" => "json",
                        "Language" => "eng"
                    ],
                    "Features" => [
                        "Built in HUD" => true,
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

        if(!file_exists($this->getDataFolder() . "Languages/")){
            @mkdir($this->getDataFolder() . "Languages");
            file_put_contents($this->getDataFolder() . "Languages/" . "eng.json", file_get_contents(__DIR__ . "/Language/Defaults/eng.json"));
        }

        $this->configurations[Configuration::CONFIG] = json_decode(file_get_contents($this->getDataFolder() . "Config.json"), true);
        $this->configurations[Configuration::FACTIONS] = json_decode(file_get_contents($this->getDataFolder() . "Factions.json"), true);
        $this->configurations[Configuration::FACTIONS_PLAYER] = json_decode(file_get_contents($this->getDataFolder() . "FactionsID.json"), true);

        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::DARK_AQUA . "-           ULTRA FACTION             ");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  ");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Language: " . $this->configurations[Configuration::CONFIG]['Data']['Language']);
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Loaded a total of: " . count($this->configurations[Configuration::FACTIONS]). " factions!");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Enjoy and stay flexing!");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
    }

    public function getConfig() {
        return $this->configurations[Configuration::CONFIG];
    }

    public function handleShutdown(){

        file_put_contents($this->getDataFolder() . "Config.json", json_encode($this->configurations[Configuration::CONFIG]), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        file_put_contents($this->getDataFolder() . "Factions.json", json_encode(UltraFaction::getInstance()->getFactionManager()->getFactionsDump()), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        file_put_contents($this->getDataFolder() . "FactionsID.json", json_encode($this->configurations[Configuration::FACTIONS_PLAYER]), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        UltraFaction::getInstance()->getLogger()->info(TextFormat::GREEN . "[DATA] Flushed and saved all data!");
    }
}