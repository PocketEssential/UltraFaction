<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 11:56 AM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\plugin\Plugin;

/**
 * Class FactionSetHomeEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionSetHomeEvent extends FactionEvent
{

    public static $handlerList;

    /**
     * FactionSetHomeEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     */
    public function __construct(Plugin $plugin, $player, $faction)
    {
        parent::__construct($plugin, $player, $faction);
    }
}