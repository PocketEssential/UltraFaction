<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 4:05 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration\Provider\Types;


use pocketmine\utils\Config;
use xZeroMCPE\UltraFaction\Configuration\Configuration;
use xZeroMCPE\UltraFaction\Configuration\Provider\Provider;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class YamlProvider
 * @package xZeroMCPE\UltraFaction\Configuration\Provider\Types
 */
class YamlProvider implements FactionsProvider
{


    /**
     * @return array
     */
    public function getAllFactions(): array
    {
        $data = new Config(Provider::getProviderDataFolder() . "Factions.yml");
        if ($data->exists("Data")) {
            return json_decode($data->get("Data"), true);
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getAllFactionsID(): array
    {
        $data = new Config(Provider::getProviderDataFolder() . "FactionsID.yml");
        if ($data->exists("Data")) {
            return json_decode($data->get("Data"), true);
        } else {
            return [];
        }
    }

    public function flushData(): void
    {
        $factions = new Config(Provider::getProviderDataFolder() . "Factions.yml");
        $factions->set("Data", json_encode(UltraFaction::getInstance()->getFactionManager()->getFactionsDump(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        $factions->save();

        $factionsID = new Config(Provider::getProviderDataFolder() . "FactionsID.yml");
        $factionsID->set("Data", json_encode(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        $factionsID->save();
    }
}