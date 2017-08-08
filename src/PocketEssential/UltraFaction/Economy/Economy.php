<?php
# _    _ _ _             ______         _   _                 
#| |  | | | |           |  ____|       | | (_)                
#| |  | | | |_ _ __ __ _| |__ __ _  ___| |_ _  ___  _ __  ___ 
#| |  | | | __| '__/ _` |  __/ _` |/ __| __| |/ _ \| '_ \/ __|
#| |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | \__ \
# \____/|_|\__|_|  \__,_|_|  \__,_|\___|\__|_|\___/|_| |_|___/
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
namespace PocketEssential\UltraFaction\Economy;


use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\Player;

/**
 * Class Economy
 * @package PocketEssential\UltraFaction\Economy
 */
class Economy {

	const NOT_ENOUGH_MONEY = "NOT_ENOUGH_MONEY";
	const SUCCESS = "SUCCESS";

	public $plugin;

	/**
	 * Economy constructor.
	 * @param UltraFaction $plugin
	 */
	public function __construct(UltraFaction $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @param Player $player
	 * @param $amount
	 */
	public function addMoney(Player $player, $amount){

	}

	/**
	 * @param Player $player
	 * @param $money
	 */
	public function takeMoney(Player $player, $money){

	}

	/**
	 * @param Player $player
	 * @param $money
	 */
	public function giveMoney(Player $player, $money){

	}
}