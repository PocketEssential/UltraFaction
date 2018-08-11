<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 6:48 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Event;


use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\Faction\Faction;

/**
 * Class FactionUnclaimEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */

/**
 * Class FactionUnclaimEvent
 * @package xZeroMCPE\UltraFaction\Faction\Event
 */
class FactionUnclaimEvent extends FactionEvent
{

    public $claim;

    /**
     * FactionUnclaimEvent constructor.
     * @param Plugin $plugin
     * @param Player $player
     * @param Faction $faction
     * @param Vector3 $claim
     */
    /**
     * FactionUnclaimEvent constructor.
     * @param Plugin $plugin
     * @param Player $player
     * @param Faction $faction
     * @param Vector3 $claim
     */
    public function __construct(Plugin $plugin, Player $player, Faction $faction, Vector3 $claim)
    {
        parent::__construct($plugin, $player, $faction);
        $this->claim = $claim;
    }

    /**
     * @return Vector3
     */
    /**
     * @return Vector3
     */
    public function getClaim() : Vector3 {
        return $this->claim;
    }

    /**
     * @param Vector3 $claim
     */
    /**
     * @param Vector3 $claim
     */
    public function setClaim(Vector3 $claim) : void {
        $this->claim = $claim;
    }
}