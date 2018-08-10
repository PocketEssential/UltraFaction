<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 4:35 PM
 */

namespace xZeroMCPE\UltraFaction\Command;


use pocketmine\command\Command as PMMPcommand;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class Command
 * @package xZeroMCPE\UltraFaction\Command
 */
class Command extends PMMPcommand implements PluginIdentifiableCommand {

    private $plugin;

    /**
     * Command constructor.
     * @param UltraFaction $plugin
     * @param $name
     * @param $description
     * @param $usageMessage
     * @param $aliases
     */
    public function __construct(UltraFaction $plugin, $name, $description, $usageMessage, $aliases){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    /**
     * @return Plugin
     */
    public function getPlugin() : Plugin{
        return $this->plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if($sender->hasPermission($this->getPermission())){
            $result = $this->onExecute($sender, $args);
            if(is_string($result)){
                $sender->sendMessage($result);
            }
            return true;
        }else{
            $sender->sendMessage("You don't have permission to do that!");
        }
        return false;
    }


    /**
     * @param CommandSender $sender
     * @param array $args
     */
    public function onExecute(CommandSender $sender, array $args)
    {

    }
}