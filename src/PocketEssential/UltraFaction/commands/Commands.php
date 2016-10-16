<?php
namespace PocketEssential\UltraFaction\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

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

                        $sender->sendMessage("----- UltraFaction Help -----");
                        $sender->sendMessage("// Todo");
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
                        if ($args[1] == null) {
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
                        if ($args[1] == null) {
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
                        if ($args[1] == null) {
                            $sender->sendMessage(UltraFaction::PREFIX . " /f rename <Name>");
                        }
                        if (!$this->plugin->IsPlayerInFaction($player)) {
                            $sender->sendMessage(UltraFaction::PREFIX . " You need to be in a faction to do this");
                        }
                        if($this->plugin->IsPlayerInFaction($player) && $args[1] !== null){
                            //todo
                            $sender->sendMessage(UltraFaction::PRIFEX . " Faction has successfully renamed!");
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
                }
            }
        }
    }
}
