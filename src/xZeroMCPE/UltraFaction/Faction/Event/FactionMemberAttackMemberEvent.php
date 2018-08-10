<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:51 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class FactionMemberAttackMemberEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionMemberAttackMemberEvent extends FactionEvent
{

    public $victim;

    public static $handlerList;

    /**
     * FactionMemberAttackMemberEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     * @param $victim
     */
    public function __construct(Plugin $plugin, $player, $faction, $victim)
    {
        parent::__construct($plugin, $player, $faction);
        $this->victim = $victim;
    }

    /**
     * @return Player
     */
    public function getVictim() : Player {
        return $this->victim;
    }
}