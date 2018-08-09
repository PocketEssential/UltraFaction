<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 4:38 PM
 */

namespace xZeroMCPE\UltraFaction\Command\Types;


use pocketmine\command\CommandSender;
use pocketmine\Player;
use xZeroMCPE\UltraFaction\Command\Command;
use xZeroMCPE\UltraFaction\UltraFaction;

class F extends Command
{
    public $plugin;


    public function __construct(UltraFaction $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin, "f", "Main faction management command", "/f", ["f", "faction"]);
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {

        if($sender instanceof Player){
            if(!isset($args[0])){
                $this->sendHelp($sender);
            } else {
                switch($args[0]){
                    default:
                        $this->sendHelp($sender);
                        break;
                }
            }
        } else {
            $sender->sendMessage("Such command can only be used in-game");
        }
        return true;
    }

    public function sendHelp(Player $player){

        $help = [
            '-- List of commands:',
            '',
            '- /f help - List of help commands',
            '- /f create <name> <description> - Create a faction!',
            '- /f members - Get a list of faction members',
            '- /f setdescription - Set your faction description',
        ];
        foreach ($help as $he){
            $player->sendMessage($he);
        }
    }
}