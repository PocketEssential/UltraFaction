<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:42 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\plugin\Plugin;

class MemberLeaveFactionEvent extends FactionEvent
{

    public static $handlerList;

    public function __construct(Plugin $plugin, $player, $faction)
    {
        parent::__construct($plugin, $player, $faction);
    }
}