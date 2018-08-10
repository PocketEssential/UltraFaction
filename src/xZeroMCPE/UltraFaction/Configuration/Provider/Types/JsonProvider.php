<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 12:14 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration\Provider\Types;


use xZeroMCPE\UltraFaction\Configuration\Configuration;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class JsonProvider
 * @package xZeroMCPE\UltraFaction\Configuration\Provider\Types
 */
class JsonProvider implements FactionsProvider
{

    /**
     * @return array
     */
    public function getAllFactions() : array
    {

        $data = [];

        if(file_exists(Configuration::getDataFolderPath() . "Factions.json")){
           $data = json_decode(file_get_contents(Configuration::getDataFolderPath() . "Factions.json"), true);
        } else {
            file_put_contents(Configuration::getDataFolderPath() . "Factions.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
            file_put_contents(Configuration::getDataFolderPath() . "FactionsID.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getAllFactionsID() : array
    {

        $data = [];

        if(file_exists(Configuration::getDataFolderPath() . "FactionsID.json")){
            $data = json_decode(file_get_contents(Configuration::getDataFolderPath() . "FactionsID.json"), true);
        } else {
            file_put_contents(Configuration::getDataFolderPath() . "FactionsID.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
            file_put_contents(Configuration::getDataFolderPath() . "FactionsID.json", json_encode(
                [
                ]
            ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
        }
        return $data;
    }

    public function flushData() : void
    {
        file_put_contents(Configuration::getDataFolderPath() . "Factions.json", json_encode(UltraFaction::getInstance()->getFactionManager()->getFactionsDump(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        file_put_contents(Configuration::getDataFolderPath() . "FactionsID.json", json_encode(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    }
}