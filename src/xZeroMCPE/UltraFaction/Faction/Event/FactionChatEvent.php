<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 4:29 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\Faction\Faction;

/**
 * Class FactionChatEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionChatEvent extends FactionEvent
{

    public $message;

    /**
     * FactionChatEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     * @param $message
     */
    public function __construct(Plugin $plugin, Player $player, Faction $faction, string $message)
    {
        parent::__construct($plugin, $player, $faction);
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage() : string {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message) : void {
        $this->message = $message;
    }
}