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
namespace PocketEssential\UltraFaction\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;

use PocketEssential\UltraFaction\UltraFaction;

class Commands implements CommandExecutor
{
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
    public function onCommand(CommandSender $sender, Command $command, $labels, array $args)
    {
        $cmd = strtolower($command);
        if ($cmd == "f") {
            if (isset($args[0])) {
                switch ($args[0]) {
                    case "help":

                        $sender->sendMessage("-----.[ UltraFaction Help ].-----");
                        $sender->sendMessage("/f create <Name>  - Creates a faction");
                        $sender->sendMessage("/f desc <Descriotion>  - Change/set the faction description");
                        $sender->sendMessage("/f open [yes/no] - Choose if invitation is required to join");
                        $sender->sendMessage("/f invite <Player Name> - Invite a player to your faction");
                        $sender->sendMessage("/f sethome - Sets the faction home at your current position");
                        $sender->sendMessage("/f ally <Faction Name>  - Ally with another faction");
                        $sender->sendMessage("/f allyaccept <Faction Name>  - Accept a ally request");

                        // Todo: Other help things such as War, Waraccept, change name, kick et!
                        break;

                    /*
                     _____                _
                   /  __ \              | |
                   | /  \/_ __ ___  __ _| |_ ___
                   | |   | '__/ _ \/ _` | __/ _ \
                   | \__/\ | |  __/ (_| | ||  __/
                    \____/_|  \___|\__,_|\__\___|


                     */
                    case "create":
                        if ($args[1] == null && $sender instanceof Player) {
                            $sender->sendMessage("/f create <FactionName>");
                        }

                        if($args[1] != null && $sender instanceof Player){
                            $this->plugin->createFaction($sender, $args[1]);
                        }
                        break;

                    /*
                    ______                    _       _   _
                    |  _  \                  (_)     | | (_)
                    | | | |___  ___  ___ _ __ _ _ __ | |_ _  ___  _ __
                    | | | / _ \/ __|/ __| '__| | '_ \| __| |/ _ \| '_ \
                    | |/ /  __/\__ \ (__| |  | | |_) | |_| | (_) | | | |
                    |___/ \___||___/\___|_|  |_| .__/ \__|_|\___/|_| |_|
                                               | |
                                               |_|
                     */

                    case "description":
                    case "setdescription":
                        $player = $sender;
                        if (!$this->plugin->IsPlayerInFaction($player)) {
                            $sender->sendMessage(UltraFaction::PREFIX . " You need to be in a faction to do this");
                        }
                        if ($args[1] == null && $sender instanceof Player) {
                            $sender->sendMessage(UltraFaction::PREFIX . " /f setdescription <Description>");
                        }
                        if ($this->plugin->IsPlayerInFaction($player) && $args[1] != null) {

                            $sender->sendMessage(UltraFaction::PREFIX . " Faction has been created!");

                            // Todo other events
                            break;
                        }

                    /*
                    ______
                    | ___ \
                    | |_/ /___ _ __   __ _ _ __ ___   ___
                    |    // _ \ '_ \ / _` | '_ ` _ \ / _ \
                    | |\ \  __/ | | | (_| | | | | | |  __/
                    \_| \_\___|_| |_|\__,_|_| |_| |_|\___|


                     */
                    case "rename":
                    case "changename":
                        $player = $sender;
                        if ($args[1] == null && $sender instanceof Player) {
                            $sender->sendMessage(UltraFaction::PREFIX . " /f rename <Name>");
                        }
                        if (!$this->plugin->IsPlayerInFaction($player)) {
                            $sender->sendMessage(UltraFaction::PREFIX . " You need to be in a faction to do this");
                        }
                        if($this->plugin->IsPlayerInFaction($player) && $args[1] !== null){
                            //todo
                            $sender->sendMessage(UltraFaction::PREFIX . " Faction has successfully renamed!");
                        }
                        break;
                    case "war":
                        //todo
                        break;
                    case "map":
                        //todo
                        break;
                    case "ally":
                        //todo
                        break;
                    case "unally":
                        //todo
                        break;
                    case "claim":
                        //todo
                        break;
                    case "invite":
                        //todo
                        break;
                    case "kick":
                        if($this->plugin->getFactionLeader($this->plugin->getPlayerFaction($sender)) == $sender->getName()){
                            if(isset($args[1])){
                                $kicked_player = $this->plugin->getServer()->getOfflinePlayer($args[1]);
                                $this->plugin->removePlayerFromFaction($args[1], $this->plugin->getFactionName($sender));
                                foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
                                    if($p->getName() == $kicked_player->getName()){
                                       $kicked_player->sendMessage(UltraFaction::PREFIX . " You have been kicked from faction.");
                                    }
                                }
                            }
                        } else {
                            $sender->sendMessage(UltraFaction::PREFIX . " Only faction leaders can kick members.");   
                        }
                        break;
                    case "leave":
                        if($this->plugin->getFactionLeader($this->plugin->getPlayerFaction($sender)) !== $sender->getName()){
                            $this->plugin->removePlayerFromFaction($sender, $this->plugin->getFactionName($sender));
                            $sender->sendMessage(UltraFaction::PREFIX . " You have left the faction.");
                        } else {
                            $sender->sendMessage(UltraFaction::PREFIX . " You cannot leave cause you are a leader.");
                        }
                        break;
                    case "delete":
                        if($this->plugin->getFactionLeader($this->plugin->getPlayerFaction($sender)) == $sender->getName()){
                            unlink($this->getDataFolder() . "/factions/". $sender->getName() . "_" . $this->plugin->getPlayerFaction($sender) .".yml");
                            $sender->sendMessage(UltraFaction::PREFIX . " Faction has been deleted!");
                        } else {
                            $sender->sendMessage(UltraFaction::PREFIX . " Only faction leaders can delete faction.");
                        }
                        break;
                    case "deny":
                        //todo
                        break;
                }
            }
        }
    }
}
