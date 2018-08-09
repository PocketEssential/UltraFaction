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

class FactionMemberAttackMemberEvent extends FactionEvent
{

    public $victim;

    public static $handlerList;

    public function __construct(Plugin $plugin, $player, $faction, $victim)
    {
        parent::__construct($plugin, $player, $faction);
        $this->victim = $victim;
    }

    public function getVictim() : Player {
        return $this->victim;
    }
}