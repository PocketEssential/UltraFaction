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

/**
 * Class FactionEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionEvent extends PluginEvent implements Cancellable
{

    public $player;
    public $faction;

    public static $handlerList;

    /**
     * FactionEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     */
    public function __construct(Plugin $plugin, Player $player, Faction $faction)
    {
        parent::__construct($plugin);
        $this->player = $player;
        $this->faction = $faction;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * @return Faction
     */
    public function getFaction() : Faction{
        return $this->faction;
    }
}