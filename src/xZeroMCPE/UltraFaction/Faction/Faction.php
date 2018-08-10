<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:37 PM
 */

namespace xZeroMCPE\UltraFaction\Faction;


use pocketmine\math\Vector3;
use pocketmine\Player;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class Faction
 * @package xZeroMCPE\UltraFaction\Faction
 */
class Faction
{

    public $leader;
    public $id;
    public $name;
    public $description;
    public $members;
    public $claims;
    public $power;
    public $bank;
    public $warps;
    public $home;
    public $isOpen;

    /**
     * Faction constructor.
     * @param string $leader
     * @param string $id
     * @param string $name
     * @param string $description
     * @param array $members
     * @param array $claims
     * @param int $power
     * @param int $bank
     * @param array $warps
     * @param string $home
     * @param bool $isOpen
     */
    public function __construct(string $leader, string $id, string $name, string $description, array $members, array $claims, int $power, int $bank, array $warps, string $home, bool $isOpen)
    {
        $this->leader = $leader;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->members = $members;
        $this->claims = $claims;
        $this->power = $power;
        $this->bank = $bank;
        $this->warps = $warps;
        $this->home = $home;
        $this->isOpen = $isOpen;
    }

    /**
     * @return string
     */
    public function getLeader() : string {
        return $this->leader;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isLeader(Player $player) : bool {
        return $this->leader == $player->getName() ? true : false;
    }

    /**
     * @return string
     */
    public function getID() : string {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription() : string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description) : void {
        $this->description = $description;
    }

    /**
     * @param bool $includeLeader
     * @return array
     */
    public function getMembers($includeLeader = false) : array {
        if(!$includeLeader){
            return $this->members;
        } else {
            return array_merge([$this->getLeader()], $this->members);
        }
    }

    /**
     * @return array
     */
    public function getClaims() : array {
        return $this->claims;
    }

    /**
     * @return int
     */
    public function getPower() : int {
        return $this->power;
    }

    /**
     * @return int
     */
    public function getBank() : int {
        return $this->bank;
    }

    /**
     * @return array
     */
    public function getWarps() : array {
        return $this->warps;
    }

    /**
     * @return string
     */
    public function getHome() : string {
        return $this->home;
    }

    /**
     * @param Vector3 $vector3
     */
    public function setHome(Vector3 $vector3) : void {
        $this->home = $vector3->getX() . ":" . $vector3->getY() . ":" . $vector3->getZ();
    }

    /**
     * @param Player $player
     */
    public function teleportToHome(Player $player) : void {
        $home = explode(":", $this->getHome());
        $player->teleport(new Vector3((int) $home[0], (int) $home[1], (int) $home[2]));
    }

    /**
     * @return bool
     */
    public function isOpen() : bool {
        return $this->isOpen;
    }

    /**
     * @return bool
     */
    public function setOpen() : bool {
        $this->isOpen = !$this->isOpen;
        return $this->isOpen();
    }

    /**
     * @param $type
     * @param array $extra
     */
    public function broadcastMessage($type, $extra = []) : void{

        switch($type){

            case "LEAVE":
                foreach ($this->getMembers(true) as $m) {
                    if ($m != $extra['Extra']) {
                        $player = UltraFaction::getInstance()->getServer()->getPlayerExact($m);
                        if($player != null){
                           if($extra['isKicked']){
                               $message = str_replace("{PLAYER}", $extra['Extra'], UltraFaction::getInstance()->getLanguage()->getLanguageValue("FACTION_KICKED_MEMBER_BROADCAST"));
                           } else {
                               $message = str_replace("{PLAYER}", $extra['Extra'], UltraFaction::getInstance()->getLanguage()->getLanguageValue("FACTION_LEAVE_SUCCESSFUL"));
                           }
                            $player->sendMessage($message);
                        }
                    }
                }
                break;

            case "MEMBER_JOIN":
                foreach ($this->getMembers(true) as $m) {
                    if ($m != $extra['Extra']) {
                        $player = UltraFaction::getInstance()->getServer()->getPlayerExact($m);
                        if($player != null){
                            $message = str_replace("{PLAYER}", $extra['Extra'], UltraFaction::getInstance()->getLanguage()->getLanguageValue("FACTION_INVITE_ACCEPT_BROADCAST"));
                            $player->sendMessage($message);
                        }
                    }
                }
                break;
        }
    }

    /**
     * @return array
     */
    public function getFlushData() : array {
        return [
            "Leader" => $this->leader,
            "ID" => $this->id,
            "Name" => $this->name,
            "Description" => $this->description,
            "Members" => $this->members,
            "Claims" => $this->claims,
            "Power" => $this->power,
            "Bank" => $this->bank,
            "Warps" => $this->warps,
            "Home" => $this->home,
            "isOpen" => $this->isOpen
        ];
    }
}