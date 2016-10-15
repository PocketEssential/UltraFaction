<?php
namespace UltraFaction\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Commands{ //i forgot what shoukd be here
    public function onCommand(CommandSender $sender, Command $command, $labels, array $args){
        $cmd = strtolower($command);
        if($cmd == "f"){
            if(isset($args[0])){
                switch($args[0]){
                    case "help":
                        //todo
                    break;
                }
            }
        }
    }
}
