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
use xZeroMCPE\UltraFaction\Configuration\Configuration;
use xZeroMCPE\UltraFaction\Faction\Event\FactionChatEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionClaimEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionCreateEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionDeleteEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionPromoteEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionSetHomeEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionStatusChangeEvent;
use xZeroMCPE\UltraFaction\Faction\Event\FactionUnclaimEvent;
use xZeroMCPE\UltraFaction\Faction\Event\MemberLeaveFactionEvent;
use xZeroMCPE\UltraFaction\UltraFaction;
use xZeroMCPE\UltraFaction\Utils\Role;

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

            if(!empty(array_filter(UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::CONFIG]['Faction']['Enabled Worlds']))){
                if(!in_array($sender->getLevel()->getName(), UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::CONFIG]['Faction']['Enabled Worlds'])){
                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValueArray('ULTRA_FACTION')['UF_NOT_AVAILABLE_HERE']);
                    return false;
                }
            }

            if(!isset($args[0])){
                $this->sendHelp($sender);
            } else {
                switch(strtolower($args[0])){

                    default:
                        $this->sendHelp($sender);
                        break;

                    case "create":
                    case "cre":
                    case "make":
                        if(UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('IN_FACTION'));
                        } else {
                            if (!isset($args[1])) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION_NAME_FORGOT'));
                            } else {
                                if (strlen($args[1]) > UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Maximum faction name']) {
                                    $lag = str_replace("{MAX_CHAR}", UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Maximum faction name'], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION_MAX_NAME'));
                                    $sender->sendMessage($lag);
                                } else {
                                    $description = isset($args[2]) ? $args[2] : UltraFaction::getInstance()->getLanguage()->getLanguageValue("DEFAULT_FACTION_DESCRIPTION");
                                    UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionCreateEvent(UltraFaction::getInstance(), $sender, $args[1], $description));

                                    if (!$event->isCancelled()) {
                                        UltraFaction::getInstance()->getFactionManager()->createFaction($sender, $args[1], $description);
                                        $lag = str_replace(['{NAME}', '{DESCRIPTION}'], [$args[1], $description], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION'));
                                        $sender->sendMessage($lag);
                                        if (UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Broadcast faction creation']) {
                                            $sender->getServer()->broadcastMessage(str_replace(['{PLAYER}', '{FACTION}', '{DESCRIPTION}'], [$sender->getName(), $args[1], $description], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CREATION_BROADCAST')));
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "description":
                    case "des":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            $sender->sendMessage(str_replace('{DESCRIPTION}', UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getDescription(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DESCRIPTION')));
                        }
                        break;

                    case "setdescription":
                    case "setdes":
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
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_DELETION_SUCCESS'));
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
                    case "inv":
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
                                            $player->sendMessage(str_replace(["{PLAYER}", "{FACTION}"], [$sender->getName(), UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getName()], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_SUCCESS_PARTY')));
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
                            UltraFaction::getInstance()->getFactionManager()->addToFaction($sender, $this->invites[$sender->getName()]);
                            $sender->sendMessage(str_replace(["{PLAYER}", "{FACTION}"], [UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getLeader(),
                                UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getName()], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_INVITE_ACCEPT')));
                            UltraFaction::getInstance()->getFactionManager()->getFactionByID($this->invites[$sender->getName()])->broadcastMessage("MEMBER_JOIN", ['Extra' => $sender->getName()]);
                            unset($this->invites[$sender->getName()]);
                        }
                        break;

                    case "power":
                    case "pw":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            $sender->sendMessage(str_replace('{POWER}', UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getPower(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_POWER')));
                        }
                        break;

                    case "kick":
                    case "k":
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
                    case "gotohome":
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

                        $mem = "";

                        if(count(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers()) == 0){
                            $mem = "It's only you!";
                        } else {
                            foreach (UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers() as $member) {
                                if (max(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers()) == $member) {
                                    $mem .= $member;
                                } else {
                                    $mem .= $member . ",";
                                }
                            }
                        }

                        foreach ($la as $i){
                            $message = str_replace("{LEADER}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getLeader(), $i);
                            $message = str_replace("{MEMBERS}", count(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers()) + 1, $message);
                            $message = str_replace("{MEMBERS_LIST}", $mem, $message);
                            $message = str_replace("{POWER}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getPower(), $message);
                            $message = str_replace("{MAX_POWER}", UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Max amount of power'], $message);
                            $message = str_replace("{DESCRIPTION}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getDescription(), $message);
                            $message = str_replace("{OPEN}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isOpen() ? "Yes" : "No", $message);
                            $message = str_replace("{ROLE}", UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getRole($sender, true), $message);

                            $sender->sendMessage($message);
                        }
                    }
                    break;

                    case "chat":
                    case "c":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(!isset($args[1])){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CHAT_FORGOT_MESSAGE'));
                            } else {
                                $msg = str_replace($args[0], "", implode(" ", $args));

                                UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionChatEvent(UltraFaction::getInstance(), $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender), $msg));

                                if(!$event->isCancelled()){
                                    $message = UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CHAT_FORMAT');
                                    $message = str_replace("{MESSAGE}", $event->getMessage(), $message);
                                    $message = str_replace("{PLAYER}", $sender->getName(), $message);

                                    foreach (UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getMembers(true) as $member){
                                        $member = UltraFaction::getInstance()->getServer()->getPlayer($member);

                                        if($member != null){
                                            $member->sendMessage($message);
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "setopen":
                    case "makeopen":
                    if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                        $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                    } else {
                        if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_SET_OPEN_NOT_LEADER'));
                        } else {
                            UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionStatusChangeEvent(UltraFaction::getInstance(),
                             $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender), UltraFaction::getInstance()->getFactionManager()->getFaction($sender)
                             ->isOpen() ? FactionStatusChangeEvent::STATUS_FACTION_OPEN : FactionStatusChangeEvent::STATUS_FACTION_CLOSE));


                            if(!$event->isCancelled()){
                                $message = UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->setOpen() ? UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_OPEN_SUCCESS_OPEN')
                                    : UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_OPEN_SUCCESS_CLOSE');
                                $sender->sendMessage($message);
                            }
                        }
                    }
                    break;

                    case "join":
                        if(UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('IN_FACTION'));
                        } else {
                            if(!isset($args[1])){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_FORGOT_NAME'));
                            } else {
                                $player = UltraFaction::getInstance()->getServer()->getPlayer($args[1]);

                                if($player == null){
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_FACTION_NOT_FOUND'));
                                } else {
                                    if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($player)){
                                        $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_FACTION_PLAYER_NOT_IN_FACTION')));
                                    } else {
                                        if(!UltraFaction::getInstance()->getFactionManager()->getFaction($player)->isOpen()){
                                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_NOT_OPEN'));

                                            if (UltraFaction::getInstance()->getConfiguration()->configurations[Configuration::CONFIG]['Faction']['Notify leader when someone tries to join a faction']){
                                                if(UltraFaction::getInstance()->getFactionManager()->getFaction($player)->returnTheLeader() != null){
                                                    UltraFaction::getInstance()->getFactionManager()->getFaction($player)->returnTheLeader()->sendMessage(str_replace("{PLAYER}", $sender->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_NOT_OPEN_LEADER_NOTIFY')));
                                                }
                                            }
                                        } else {
                                            UltraFaction::getInstance()->getFactionManager()->addToFaction($sender, UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getID());
                                            $sender->sendMessage(str_replace("{FACTION}", UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_JOIN_SUCCESS')));
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "claim":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CLAIM_NOT_LEADER'));
                            } else {
                                if (count(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getClaims()) ==
                                    UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Max Claims']) {
                                    $sender->sendMessage(str_replace("{MAX_CLAIM}", UltraFaction::getInstance()->getConfiguration()->getConfig()['Faction']['Max Claims'], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_MAX_CLAIM_REACHED')));
                                } else {
                                    UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionClaimEvent(UltraFaction::getInstance(),
                                        $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender), $sender->asVector3()));

                                    if(!$event->isCancelled()){
                                        UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->addClaim($event->getClaim());
                                        $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_CLAIM_SUCCESS'));
                                    }
                                }
                            }
                        }
                        break;

                    case "unclaim":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if (!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)) {
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_UNCLAIM_NOT_LEADER'));
                            } else {
                                if (count(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getClaims()) == 0) {
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_UNCLAIM_NO_CLAIM'));
                                } else {

                                    $claim = isset($args[1]) ? is_int($args[1]) ? 0 : $args[1] : 0;
                                    if(!isset(UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->claims[$claim])){
                                        $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_UNCLAIM_CLAIM_NOT_FOUND'));
                                    } else {
                                        UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionUnclaimEvent(UltraFaction::getInstance(),
                                            $sender, UltraFaction::getInstance()->getFactionManager()->getFaction($sender), $sender->asVector3()));

                                        if(!$event->isCancelled()){
                                            UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->removeClaim($claim);
                                            $sender->sendMessage(str_replace("{CLAIM_ID}", $claim, UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_UNCLAIM_SUCCESS')));
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    case "promote":
                    case "demote":
                        if(!UltraFaction::getInstance()->getFactionManager()->isInFaction($sender)){
                            $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('NOT_IN_FACTION'));
                        } else {
                            if(!UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->isLeader($sender)){
                                $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_NOT_LEADER'));
                            } else {
                                if (!isset($args[1])) {
                                    $sender->sendMessage(UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_FORGOT_NAME'));
                                } else {
                                    $player = UltraFaction::getInstance()->getServer()->getPlayer($args[1]);

                                    if ($player == null) {
                                        $sender->sendMessage(str_replace("{PLAYER}", $args[1], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_NOT_FOUND')));
                                    } else {
                                        if (!UltraFaction::getInstance()->getFactionManager()->isInFaction($player)) {
                                            $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_PLAYER_NOT_IN_FACTION_ELSE')));
                                        } else {
                                            if(UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getName() !=
                                              (UltraFaction::getInstance()->getFactionManager()->getFaction($sender)->getName())){
                                                $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_PLAYER_NOT_IN_FACTION_ELSE')));
                                            } else {
                                                if(UltraFaction::getInstance()->getFactionManager()->getFaction($player)->isLeader($player)){
                                                    $sender->sendMessage(str_replace("{PLAYER}", $player->getName(), UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_CANNOT_PROMOTE_LEADER')));
                                                } else {
                                                    $faction = UltraFaction::getInstance()->getFactionManager()->getFaction($sender);
                                                    $role = $faction->getRole($player);
                                                    $newRole = $faction->getRole($player) == Role::MEMBER ? Role::OFFICER : Role::MEMBER or $faction->getRole($player) == Role::OFFICER ? Role::MEMBER : Role::OFFICER;

                                                    UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionPromoteEvent(UltraFaction::getInstance(), $player, $faction, $role, $newRole));

                                                    if(!$event->isCancelled()){
                                                        $faction->setRole($player);
                                                        $sender->sendMessage(str_replace(['{PLAYER}', '{ROLE}', '{OLD_ROLE}'], [$player->getName(), $newRole, $role], UltraFaction::getInstance()->getLanguage()->getLanguageValue('FACTION_PROMOTE_SUCCESSFUL')));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
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

        $la = UltraFaction::getInstance()->getLanguage()->getLanguageValueArray("FACTION_HELP");

        foreach ($la as $i){
            $message = str_replace("{PLAYER}", $player->getName(), $i);
            $player->sendMessage($message);
        }
    }
}