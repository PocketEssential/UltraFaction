<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 3:37 PM
 */

namespace xZeroMCPE\UltraFaction\Faction;


use pocketmine\Player;

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

    public function __construct(string $leader, string $id, string $name, string $description, array $members, array $claims, int $power, int $bank, array $warps)
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
    }

    public function getLeader() : string {
        return $this->leader;
    }

    public function isLeader(Player $player) : bool {
        return $this->leader == $player->getName() ? true : false;
    }

    public function getID() : string {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getMembers() : array {
        return $this->members;
    }

    public function getClaims() : array {
        return $this->claims;
    }

    public function getPower() : int {
        return $this->power;
    }

    public function getBank() : int {
        return $this->bank;
    }

    public function getWarps() : array {
        return $this->warps;
    }

    public function getFlushData() : array {
        return [
            "Leader" => $this->leader,
            "ID" => $this->id,
            "Name" => $this->name,
            "Members" => $this->members,
            "Power" => $this->power,
            "Bank" => $this->bank,
            "Warps" => $this->warps
        ];
    }
}