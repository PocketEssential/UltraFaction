<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/11/2018
 * Time: 12:13 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\Faction\Faction;

/**
 * Class FactionPromoteEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */

/**
 * Class FactionPromoteEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionPromoteEvent extends FactionEvent
{

    public $role;
    public $oldRole;

    /**
     * FactionPromoteEvent constructor.
     * @param Plugin $plugin
     * @param Player $player
     * @param Faction $faction
     * @param string $old
     * @param string $oldRole
     */
    /**
     * FactionPromoteEvent constructor.
     * @param Plugin $plugin
     * @param Player $player
     * @param Faction $faction
     * @param string $old
     * @param string $oldRole
     */
    public function __construct(Plugin $plugin, Player $player, Faction $faction, string $old, string $oldRole)
    {
        parent::__construct($plugin, $player, $faction);
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getRole() : string {
        return $this->role;
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getFromRole() : string {
        return $this->oldRole;
    }
}