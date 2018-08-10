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
use xZeroMCPE\UltraFaction\Faction\Listener\FactionListener;
use xZeroMCPE\UltraFaction\Faction\Tasks\FactionHud;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class FactionManager
 * @package xZeroMCPE\UltraFaction\Faction
 */
class FactionManager
{

    public $factions = [];

    public function __construct()
    {
        $this->loadRequired();
    }

    public function loadRequired(){

        UltraFaction::getInstance()->getServer()->getPluginManager()->registerEvents(new FactionListener(), UltraFaction::getInstance());

        if(UltraFaction::getInstance()->getConfiguration()->getConfig()['Features']['Built in HUD']){
            UltraFaction::getInstance()->getScheduler()->scheduleRepeatingTask(new FactionHud(), 20);
        }

        if(count(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS]) != 0){
            foreach (UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS] as $factions){
                $this->factions[$factions['ID']] = new Faction($factions['Leader'], $factions['ID'], $factions['Name'], $factions['Description'], $factions['Members'], $factions['Claims'], $factions['Power'], $factions['Bank'], $factions['Warps'], $factions['Home']);
            }
        }
    }

    /**
     * @param Player $player
     * @return Faction
     */
    public function getFaction(Player $player) : Faction {
        return $this->factions[UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getName()]];
    }

    /**
     * @param string $id
     * @return null|Faction
     */
    public function getFactionByID(string $id) : ?Faction {

        if(isset($this->factions[$id])){
            return $this->factions[$id];
        }
        return null;
    }

    /**
     * @param Player $leader
     * @param string $name
     * @param string $description
     */
    public function createFaction(Player $leader, string $name, string $description){
        $id = uniqid('UZ-');
        $this->factions[$id] = new Faction($leader->getName(), $id, $name, $description, [], [], UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Starting power'],
            UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Starting bank balance'], [], "");

        UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$leader->getName()] = $id;
    }

    /**
     * @param Player $player
     */
    public function deleteFaction(Player $player) {

        $id = $this->getFaction($player)->getID();

        foreach ($this->getFaction($player)->getMembers() as $member){
            unset(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$member]);
        }
        unset(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getName()]);
        unset($this->factions[$id]);
    }

    /**
     * @param Player $player
     * @param string $id
     */
    public function addToFaction(Player $player, string $id){

        $this->getFactionByID($id)->members[] = $player->getName();
        UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getName()] = $id;
    }

    /**
     * @param Player $player
     * @param bool $isKicked
     */
    public function removeFromFaction(Player $player, $isKicked = false) {

        $this->getFaction($player)->broadcastMessage('LEAVE', ['Extra' => $player->getName(), 'isKicked' => $isKicked]);
        unset($this->getFaction($player)->members[array_search($player->getName(), $this->getFaction($player)->members)]);
        unset(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getName()]);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isInFaction(Player $player) : bool {
        return isset(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::FACTIONS_PLAYER][$player->getName()]);
    }

    /**
     * @return array
     */
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