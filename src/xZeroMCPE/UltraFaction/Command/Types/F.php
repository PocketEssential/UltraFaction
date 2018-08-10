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
use pocketmine\utils\TextFormat;
use xZeroMCPE\UltraFaction\Command\Command;
use xZeroMCPE\UltraFaction\Faction\Event\FactionCreateEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionDeleteEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionSetHomeEvent;
use xZeroMCPE\UltraFaction\Faction\Event\MemberLeaveFactionEvent;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class F
 * @package xZeroMCPE\UltraFaction\Command\Types
 */
class F extends Command
{

    public $plugin;
    public $invites = [];

    /**
     * F constructor.
     * @param UltraFaction $plugin
     */
    public function __construct(UltraFaction $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin, "f", "Main faction management command", "/f", ["f", "faction"]);
    }


    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
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
                                     UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionCreateEvent(UltraFaction::getInstance(), $sender, $args[1], $description));

                                   if(!$event->isCancelled()){
                                       UltraFaction::getInstance()->getFactionManager()->createFaction($sender, $args[1], $description);
                                       $lag = str_replace(['{NAME}', '{DESCRIPTION}'], [$args[1], $description], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION'));
                                       $sender->sendMessage($lag);
                                   }
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

                    case "delete":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)){
                                UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionDeleteEvent(UltraFaction::getInstance(), $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender)));

                                if(!$event->isCancelled()){
                                    UltraFaction::getInstance()->getFactionManager()->deleteFaction($sender);
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DELETION'));
                                }
                            } else {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DELETION_NOT_LEADER'));
                            }
                        }
                        break;

                    case "leave":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_LEAVE_LEADER'));
                            } else {
                                UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new MemberLeaveFactionEvent(UltraFaction::getInstance(), $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender)));

                                if(!$event->isCancelled()){
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_LEAVE_SUCCESSFUL'));
                                    UltraFaction::getInstance()->getFactionManager()->removeFromFaction($sender);
                                }
                            }
                        }
                        break;

                    case "invite":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_NOT_LEADER'));
                            } else {
                                if(!isset($args[1])){
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_NAME_FORGOT'));
                                } else {
                                    $player = UltraFaction::getInstance()->getServer()->getPlayer($args[1]);

                                    if($player == null){
                                        $sender->sendMessage(str_replace("{PLAYER}", $args[1], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_PLAYER_OFFLINE')));
                                    } else {
                                        if(UltraFaction::getInstance()->getFactionManager()->isInFaction($player)){
                                            $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_PLAYER_IN_FACTION')));
                                        } else {
                                            $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_SUCCESS')));
                                            $sender->sendMessage(str_replace(["{PLAYER}", "{FACTION}"], [$sender->getName(), UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getName()], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_SUCCESS_PARTY')));
                                            $this->invites[$player->getName()] = UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getID();
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "accept":
                        if(!isset($this->invites[$sender->getName()])){
                            $sender->sendMessage(str_replace("{PLAYER}", $sender->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue("You don't have any faction invites!")));
                        } else {
                            $sender->sendMessage(str_replace(["{PLAYER}", "{FACTION}"], [UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getLeader(),
                                UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getName()], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_ACCEPT')));
                            UltraFaction::getInstance()->getFactionManager()->getFactionByID($this->invites[$sender->getName()])->broadcastMessage("MEMBER_JOIN", ['Extra' => $sender->getName()]);
                            UltraFaction::getInstance()->getFactionManager()->addToFaction($sender, $this->invites[$sender->getName()]);
                            unset($this->invites[$sender->getName()]);
                        }
                        break;

                    case "power":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            $sender->sendMessage(str_replace('{POWER}', UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getPower(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_POWER')));
                        }
                        break;

                    case "kick":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_KICK_NOT_LEADER'));
                            } else {
                                if (!isset($args[1])) {
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_KICK_NAME_FORGOT'));
                                } else {
                                    $player = UltraFaction::getInstance()->getServer()->getPlayer($args[1]);

                                    if($player == null){
                                        $sender->sendMessage(str_replace("{PLAYER}", $args[1], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_KICK_PLAYER_OFFLINE')));
                                    } else {
                                        if (UltraFaction::getInstance()->getFactionManager()->isInFaction($player)) {
                                            if(UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getName() !=
                                                (UltraFaction::getInstance()->getFactionManager()->getFaction($sender))->getName()){
                                                $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_KICK_PLAYER_NOT_IN_FACTION')));
                                            } else {
                                                $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_KICK_SUCCESS')));
                                                UltraFaction::getInstance()->getFactionManager()->removeFromFaction($player, true);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "home":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(strlen(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getHome()) == 0){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_NO_HOME'));
                            } else {
                                UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->teleportToHome($sender);
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_HOME_WELCOME'));
                            }
                        }
                        break;

                    case "sethome":
                    case "homeset":
                    if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                        $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                    } else {
                        if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_HOME_SET_NOT_LEADER'));
                        } else {
                            UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionSetHomeEvent(UltraFaction::getInstance(), $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender)));

                            if(!$event->isCancelled()){
                                UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->setHome($sender->asVector3());
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_HOME_SET_SUCCESS'));
                            }
                        }
                    }
                    break;

                    case "info":
                    case "information":
                    if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                        $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                    } else {
                        $la = UltraFaction::getInstance()->getLanguage()->getLanguageValueArray("FACTION_INFORMATION");

                        foreach ($la as $i){
                            $message = str_replace("{LEADER}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getLeader(), $i);
                            $message = str_replace("{MEMBERS}", count(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers()), $message);
                            $message = str_replace("{POWER}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getPower(), $message);
                            $message = str_replace("{DESCRIPTION}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getDescription(), $message);
                            $sender->sendMessage($message);
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

    /**
     * @param Player $player
     */
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