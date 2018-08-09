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
                switch(strtolower($args[0])){

                    default:
                        $this->sendHelp($sender);
                        break;

                    case "create":
                        if(UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('IN_FACTION'));
                        } else {
                            if(!isset($args[1])){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION_NAME_FORGOT'));
                            } else {
                                if(strlen($args[1]) > UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Maximum faction name']){
                                    $lag = str_replace("{MAX_CHAR}", UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Maximum faction name'], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION_MAX_NAME'));
                                    $sender->sendMessage($lag);
                                } else {
                                    $description = isset($args[2]) ? $args[2] : UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Default description'];
                                    UltraFaction::getInstance()->getFactionManager()->createFaction($sender, $args[1], $description);
                                    $lag = str_replace(['{NAME}', '{DESCRIPTION}'], [$args[1], $description], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION'));
                                    $sender->sendMessage($lag);
                                }
                            }
                        }
                        break;

                    case "description":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            $sender->sendMessage(str_replace('{DESCRIPTION}', UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getDescription(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DESCRIPTION')));
                        }
                        break;

                    case "setdescription":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(!isset($args[1])){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DESCRIPTION_SET_FORGOT'));
                            } else {
                                $sender->sendMessage(str_replace('{DESCRIPTION}', $args[1], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DESCRIPTION_SET')));
                                UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->setDescription($args[1]);
                            }
                        }
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