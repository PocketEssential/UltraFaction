<?php
namespace PocketEssential\UltraFaction;

use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\event\Listener;

class EventListener implements Listener{
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
}
