<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 4:29 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\plugin\Plugin;

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
    public function __construct(Plugin $plugin, $player, $faction, $message)
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