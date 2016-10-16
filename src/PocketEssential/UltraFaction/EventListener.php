<?php
namespace PocketEssential\UltraFaction;

use PocketEssential\UltraFaction\UltraFaction;

class EventListener {
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
}
