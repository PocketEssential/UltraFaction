<?php
namespace PocketEssential/UltraFaction;

use PocketEssential\UltraFaction\UltraFaction;

class Listener {
    public function __construct(UltraFaction $plugin){
        $this->plugin = $plugin;
    }
}
