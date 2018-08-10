<?php
#  _    _ _ _             ______         _   _
#  | |  | | | |           |  ____|       | | (_)
#  | |  | | | |_ _ __ __ _| |__ __ _  ___| |_ _  ___  _ __
#  | |  | | | __| '__/ _` |  __/ _` |/ __| __| |/ _ \| '_ \
#  | |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | |
#    \____/|_|\__|_|  \__,_|_|  \__,_|\___|\__|_|\___/|_| |_|
# 
# 
# Made by xZeroMCPE
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author: The PocketEssential Team (xZeroMCPE)
# Link: https://github.com/PocketEssential
#
#|------------------------------------------------- UltraFaction -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/UltraFaction/issues             |
#|----------------------------------------------------------------------------------------------------------------|

namespace xZeroMCPE\UltraFaction;


use pocketmine\plugin\PluginBase;
use xZeroMCPE\UltraFaction\Command\Types\F;
use xZeroMCPE\UltraFaction\Configuration\Configuration;
use xZeroMCPE\UltraFaction\Configuration\Language\Language;
use xZeroMCPE\UltraFaction\Faction\FactionManager;

/**
 * Class UltraFaction
 * @package xZeroMCPE\UltraFaction
 */
class UltraFaction extends PluginBase
{

    public $components = [];
    public static $instance;

    public function onEnable()
    {
        self::$instance = $this;
        $this->loadComponents();
    }

    public function onDisable()
    {
        if(!$this->getServer()->isRunning()){
            $this->getConfiguration()->handleShutdown();
        }
    }

    /**
     * @return UltraFaction
     */
    public static function getInstance() : UltraFaction {
        return self::$instance;
    }

    public function loadComponents(){


        $this->components['Configuration'] = new Configuration();
        $this->components['Language'] = new Language();
        $this->components['FactionManager'] = new FactionManager();

        $this->getServer()->getCommandMap()->registerAll('UltraFaction', [
            new F($this)
        ]);
    }

    /**
     * @return Configuration
     */
    public function getConfiguration() : Configuration {
        return $this->components['Configuration'];
    }

    /**
     * @return Language
     */
    public function getLanguage() : Language {
        return $this->components['Language'];
    }

    /**
     * @return FactionManager
     */
    public function getFactionManager() : FactionManager{
        return $this->components['FactionManager'];
    }
}