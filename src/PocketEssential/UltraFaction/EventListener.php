<?php
namespace PocketEssential\UltraFaction;

use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\event\Listener;
use pocektmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat as TF;

class EventListener implements Listener{
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($this->plugin->IsPlayerInFaction($player)){
            $faction = $this->plugin->getPlayerFaction($player);
            if($this->plugin->getFactionLeader($faction) == $player->getName()){
                $event->setFormat(TF::DARK_PURPLE . "**" . $faction  . " " . $player ." > ". $message);
            } else {
                $event->setFotmat(TF::DARK_PURPLE . "$faction . " " . $player .">". $message");
            }
        }
    }
}
