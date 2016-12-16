<?php
# _    _ _ _             ______         _   _
#| |  | | | |           |  ____|       | | (_)
#| |  | | | |_ _ __ __ _| |__ __ _  ___| |_ _  ___  _ __  ___
#| |  | | | __| '__/ _` |  __/ _` |/ __| __| |/ _ \| '_ \/ __|
#| |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | \__ \
# \____/|_|\__|_|  \__,_|_|  \__,_|\___|\__|_|\___/|_| |_|___/
#
# Made by PocketEssential Copyright 2016 ©
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author: The PocketEssential Team
# Link: https://github.com/PocketEssential
#
#|------------------------------------------------- UltraFaction -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/UltraFaction/issues             |
#|----------------------------------------------------------------------------------------------------------------|
namespace PocketEssential\UltraFaction\Economy;

use pocketmine\plugin\Plugin;
use pocketmine\Player;

use PocketEssential\UltraFaction\UltraFaction;

class Economy
{

    const ECONOMY_TYPE = null;
    public $economy = null;
    public $type = null;

    public function __construct(UltraFaction $plugin)
    {
        $this->plugin = $plugin;
    }

    public function Economy()
    {
        if ($this->plugin->getConfig()->get("Economy") == "EconomyAPI") {
            $eco = $this->plugin->getServer()->getPluginManager()->getPlugin('EconomyAPI');
            if ($eco == false) {
                $this->plugin->getLogger()->info("|| EconomyAPI cannot be found ||");
            } else {
                $this->economy = $eco;
                $this->type = "EconomyAPI";
            }
        }
        if ($this->plugin->getConfig()->get("Economy") == "MassiveEconomy") {
            $eco = $this->plugin->getServer()->getPluginManager()->getPlugin('MassiveEconomy');
            if ($eco == false) {
                $this->plugin->getLogger()->info("|| MassiveEconomy cannot be found ||");
            } else {
                $this->economy = $eco;
                $this->type = "MassiveEconomy";
            }
        }
    }

    public function addMoney($player, $amount)
    {
        switch ($this->type) {
            case 'EconomyAPI':
                $this->economy->addMoney($player, $amount, true);
                return true;
                break;

            case 'MassiveEconomy':
                $this->economy->payPlayer($player->getName(), $amount);
                return true;
                break;

        }
    }

    public function takeMoney($player, $amount)
    {
        switch ($this->type) {
            case 'EconomyAPI':
                $this->economy->reduceMoney($player, $amount, true);
                return true;
                break;

            case 'MassiveEconomy':
                $this->economy->takeMoney($player->getName(), $amount);
                return true;
                break;

        }
    }
}
