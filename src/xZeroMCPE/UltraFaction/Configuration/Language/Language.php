<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 4:59 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration\Language;


use xZeroMCPE\UltraFaction\UltraFaction;

class Language
{

    public $language = [];
    public $workingLanguage = false;

    public function __construct()
    {
        $this->loadLanguage();
    }

    public function loadLanguage(){

        $language = UltraFaction::getInstance()->getConfiguration()->getConfig()['Data']['Language'];

        if(!file_exists(UltraFaction::getInstance()->getConfiguration()->getDataFolder() . "Languages/". $language . ".json")){
            UltraFaction::getInstance()->getLogger()->error("[LANGUAGE] We couldn't find the language file corresponding to " . $language.". Please make sure {$language}.json exists");
        } else {
            $this->language = json_decode(file_get_contents(UltraFaction::getInstance()->getConfiguration()->getDataFolder() . "Languages/" . $language . ".json"), true);
            $this->workingLanguage = true;
        }
    }

    public function getLanguageValue(string $type): string {
        if(isset($this->language[$type])){
            return $this->language[$type];
        } else {
            return "ERROR_LANGUAGE_NOT_FOUND [{$type}]";
        }
    }
}