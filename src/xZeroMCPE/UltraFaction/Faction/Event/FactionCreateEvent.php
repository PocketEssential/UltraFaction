<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:30 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class FactionCreateEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionCreateEvent extends PluginEvent implements Cancellable
{

    public $player;
    public $name;
    public $description;

    public static $handlerList;

    /**
     * FactionCreateEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $name
     * @param $description
     */
    public function __construct(Plugin $plugin, Player $player, string $name, string $description)
    {
        parent::__construct($plugin);
        $this->player = $player;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getFactionName() : string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFactionDescription() : string {
        return $this->description;
    }
}