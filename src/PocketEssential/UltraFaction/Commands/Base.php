<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/7/2017
 * Time: 7:35 PM
 */

namespace PocketEssential\UltraFaction\Commands;


use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

/**
 * Class Base
 * @package PocketEssential\UltraFaction\Commands
 */
class Base extends Command implements PluginIdentifiableCommand
{

	private $plugin;

	/**
	 * Base constructor.
	 * @param UltraFaction $plugin
	 * @param string $name
	 * @param string $description
	 * @param array|string[] $usageMessage
	 * @param $aliases
	 */
	public function __construct(UltraFaction $plugin, $name, $description, $usageMessage, $aliases)
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin() : Plugin
	{
		return $this->plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
	{
		if ($sender->hasPermission($this->getPermission())) {
			$result = $this->onExecute($sender, $args);
			if (is_string($result)) {
				$sender->sendMessage($result);
			}
			return true;
		} else {
			$sender->sendMessage("Wow such command, :o");
		}
		return false;
	}
}