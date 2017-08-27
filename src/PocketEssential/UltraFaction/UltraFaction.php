<?php
# _ _ _ _ ______ _ _ 
#| | | | | | | ____| | | (_) 
#| | | | | |_ _ __ __ _| |__ __ _ ___| |_ _ ___ _ __ ___ 
#| | | | | __| '__/ _` | __/ _` |/ __| __| |/ _ \| '_ \/ __|
#| |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | \__ \
# \____/|_|\__|_| \__,_|_| \__,_|\___|\__|_|\___/|_| |_|___/
#
# Made by PocketEssential Copyright 2016 ©
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author:The PocketEssential Team
# Link:https://github.com/PocketEssential
#
#|------------------------------------------------- UltraFaction -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/UltraFaction/issues |
#|----------------------------------------------------------------------------------------------------------------|

namespace PocketEssential\UltraFaction;

use MongoDB\Driver\Command;
use PocketEssential\UltraFaction\Tool\Tool;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

/**
 * Class UltraFaction
 * @package PocketEssential\UltraFaction
 */
class UltraFaction extends PluginBase implements Listener {

	const PREFIX = TextFormat::YELLOW . "[" . TextFormat::AQUA . "§c§lUF§r" . TextFormat::YELLOW . "]". TextFormat::GOLD ." ";
	public $economy;
	public $config;
	public $language;
	public $lang;
	public $data;

	public function onEnable(){

		$this->getReady();

		$this->getLogger()->info(TextFormat::YELLOW. "---------------------------------------");
		$this->getLogger()->info(TextFormat::LIGHT_PURPLE ."Using Language: " .TextFormat::BLUE. Tool::getInstance()->getLanguage());
		$this->getLogger()->info(TextFormat::LIGHT_PURPLE ."Data-Provider: " .TextFormat::BLUE. Tool::getInstance()->getProvider());
		$this->getLogger()->info(TextFormat::LIGHT_PURPLE ."Economy: " .TextFormat::BLUE. Tool::getInstance()->getEconomy());
		$this->getLogger()->info(TextFormat::GREEN ."|| Everything has been loaded ||| ");
		$this->getLogger()->info(TextFormat::YELLOW. "----------------------------------------");

	}

	public function getReady(){

		$ready = new Tool($this);
		$ready->ready();
	}
}