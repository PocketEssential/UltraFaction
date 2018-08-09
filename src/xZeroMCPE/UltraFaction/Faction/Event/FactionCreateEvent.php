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

class FactionCreateEvent extends PluginEvent implements Cancellable
{

    public $player;
    public $name;
    public $description;

    public static $handlerList;

    public function __construct(Plugin $plugin, $player, $name, $description)
    {
        parent::__construct($plugin);
        $this->player = $player;
        $this->name = $name;
        $this->description = $description;
    }

    public function getPlayer() : Player {
        return $this->player;
    }

    public function getFactionName() : string {
        return $this->name;
    }

    public function getFactionDescription() : string {
        return $this->description;
    }
}