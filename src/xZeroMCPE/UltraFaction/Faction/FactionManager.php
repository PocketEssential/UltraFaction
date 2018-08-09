<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:46 PM
 */

namespace xZeroMCPE\UltraFaction\Faction;


use pocketmine\Player;
use xZeroMCPE\UltraFaction\Configuration\Configuration;
use xZeroMCPE\UltraFaction\UltraFaction;

class FactionManager
{

    public $factions = [];

    public function __construct()
    {
        $this->loadRequired();
    }

    public function loadRequired(){
        if(count(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS]) != 0){
            foreach (UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS] as $factions){
                $this->factions[$factions['ID']] = new Faction($factions['Leader'], $factions['ID'], $factions['Members'], $factions['Claims'], $factions['Power'], $factions['Bank'], $factions['Warps']);
            }
        }
    }

    public function getFaction(Player $player) : Faction {
        return $this->factions[UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getXuid()]];
    }

    public function isInFaction(Player $player) : bool {
        return isset($this->factions[UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getXuid()]]);
    }

    public function getFactionsDump() : array {

        $afterMath = [];

        foreach ($this->factions as $faction){
            if($faction instanceof Faction){
                $afterMath[$faction->getID()] = $faction->getFlushData();
            }
        }
        return $afterMath;
    }
}