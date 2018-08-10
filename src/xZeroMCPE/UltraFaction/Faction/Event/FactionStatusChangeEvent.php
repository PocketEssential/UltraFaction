<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 5:17 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\plugin\Plugin;

/**
 * Class FactionStatusChangeEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionStatusChangeEvent extends FactionEvent
{

    public $status;

    const STATUS_FACTION_OPEN = 0;
    const STATUS_FACTION_CLOSE = 1;

    /**
     * FactionStatusChangeEvent constructor.
     * @param Plugin $plugin
     * @param $player
     * @param $faction
     * @param int $status
     */
    public function __construct(Plugin $plugin, $player, $faction, int $status)
    {
        parent::__construct($plugin, $player, $faction);
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus() : int {
        return $this->status;
    }
}