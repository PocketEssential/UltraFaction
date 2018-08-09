<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:31 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\Faction\Faction;

class FactionEvent extends PluginEvent implements Cancellable
{

    public $player;
    public $faction;

    public static $handlerList;

    public function __construct(Plugin $plugin, $player, $faction)
    {
        parent::__construct($plugin);
        $this->player = $player;
        $this->faction = $faction;
    }

    public function getPlayer() : Player {
        return $this->player;
    }

    public function getFaction() : Faction{
        return $this->faction;
    }
}