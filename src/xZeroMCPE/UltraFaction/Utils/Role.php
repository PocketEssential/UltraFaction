<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/11/2018
 * Time: 12:00 PM
 */

namespace xZeroMCPE\UltraFaction\Utils;


use pocketmine\Player;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class Role
 * @package xZeroMCPE\UltraFaction\Utils
 */

/**
 * Class Role
 * @package xZeroMCPE\UltraFaction\Utils
 */
class Role
{

    const MEMBER = "MEMBER";
    const OFFICER = "OFFICER";
    const LEADER = "LEADER";

    /**
     * @param Player $player
     * @return string
     */
    /**
     * @param Player $player
     * @return string
     */
    public static function getRoleFormat(Player $player) : string {
        if(UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getRole($player) == Role::OFFICER){
            return "** " . $player->getName();
        } else {
            return "* " . $player->getName();
        }
    }
}