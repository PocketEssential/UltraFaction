<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:23 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration;


use pocketmine\utils\TextFormat;

use xZeroMCPE\UltraFaction\Configuration\Provider\Provider;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class Configuration
 * @package xZeroMCPE\UltraFaction\Configuration
 */
class Configuration
{

    public $configurations = [];

    public $provider;

    const CONFIG = "Config";
    const FACTIONS = "Factions";
    const FACTIONS_PLAYER = "Factions_Player";

    public function __construct()
    {
        $this->loadConfiguration();
    }

    /**
     * @return string
     */
    public function getDataFolder() : string {
        return UltraFaction::getInstance()->getServer()->getDataPath() . "UltraFaction/";
    }

    /**
     * @return string
     */
    public static function getDataFolderPath() : string {
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
                        "### 0 = Disable, >= 1 (deaths until raidable, hehe)",
                        "Deaths until raidable" => 0,
                        "Damage" => [
                            "Friendly fire" => false,
                        ],
                        "Max Claims" => 4,
                        "Claim Size" => "24",
                        "Disallow commands while in enemy Territory" => [
                            '/spawn',
                            '/home',
                            '/tpa',
                            '/sethome',
                            '/homes',
                            '/tpall',
                            '/warp'
                        ],
                        "Power System" => [
                            'Power gained per kill' => 4,
                            'Power loss per death' => 4,
                        ],
                        "Broadcast faction creation" => true,
                    ],
                    "Data" => [
                        "--------->" => "You can choose from: json, yaml",
                        "Data Provider" => "json",
                        "Language" => "eng"
                    ],
                    "Features" => [
                        "--------->" => "Set this to false if you don't want us to display the HUD",
                        "Built in HUD" => true,
                    ],
                    "Economy" => [
                        "Hook" => "# Replace this with an Economy plugin name you'd like us to hook to #"
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
        $this->provider = new Provider($this->configurations[Configuration::CONFIG]['Data']['Data Provider']);

        $this->configurations[Configuration::FACTIONS] = $this->getProvider()->getProvider()->getAllFactions();
        $this->configurations[Configuration::FACTIONS_PLAYER] = $this->getProvider()->getProvider()->getAllFactionsID();

        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::DARK_AQUA . "-           ULTRA FACTION             ");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  ");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Language: " . $this->configurations[Configuration::CONFIG]['Data']['Language']);
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Loaded a total of: " . count($this->configurations[Configuration::FACTIONS]). " factions!");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Data Provider: " . $this->getProvider()->getProviderName());
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."-  " .TextFormat::GOLD . "Enjoy and stay flexing!");
        UltraFaction::getInstance()->getLogger()->info(TextFormat::YELLOW ."--------------------------------------");
    }

    /**
     * @return mixed
     */
    public function getConfig() {
        return $this->configurations[Configuration::CONFIG];
    }

    /**
     * @return Provider
     */
    public function getProvider() : Provider {
        return $this->provider;
    }

    public function handleShutdown(){

        $this->getProvider()->getProvider()->flushData();
        UltraFaction::getInstance()->getLogger()->info(TextFormat::GREEN . "[DATA] Flushed and saved all data!");
    }
}