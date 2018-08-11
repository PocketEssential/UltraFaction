<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:39 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\Faction\Faction;

/**
 * Class FactionDeleteEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionDeleteEvent extends FactionEvent
{

    public static $handlerList;

    /**
     * FactionDeleteEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     */
    public function __construct(Plugin $plugin, Player $player, Faction $faction)
    {
        parent::__construct($plugin, $player, $faction);
    }
}