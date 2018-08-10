<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 7:40 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Tasks;


use pocketmine\scheduler\Task;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class FactionHud
 * @package xZeroMCPE\UltraFaction\Faction\Tasks
 */
class FactionHud extends Task
{

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        foreach (UltraFaction::getInstance()->getServer()->getOnlinePlayers() as $player){
            if(UltraFaction::getInstance()->getFactionManager()->isInFaction($player)){
                $faction = UltraFaction::getInstance()->getFactionManager()->getFaction($player);
                $message = str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('IN_FACTION_HUD'));
                $message = str_replace("{FACTION}", $faction->getName(), $message);
                $message = str_replace("{FACTION_POWER}", $faction->getPower(), $message);
                $message = str_replace("{FACTION_DESCRIPTION}", $faction->getDescription(), $message);
                $player->sendTip($message);
            } else {
                $message = str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION_HUD'));
                $player->sendTip($message);
            }
        }
    }
}