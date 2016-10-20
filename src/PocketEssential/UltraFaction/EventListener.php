<?php
namespace PocketEssential\UltraFaction;

use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\event\Listener;
use pocektmine\event\player\PlayerChatEvent;

class EventListener implements Listener{
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        //TODO
    }
}
