<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/27/2017
 * Time: 3:34 PM
 */

namespace PocketEssential\UltraFaction\Manager\Listener;


use PocketEssential\UltraFaction\Manager\Request\RequestPlayerFaction;
use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;

/**
 * Class UltraFactionListener
 * @package PocketEssential\UltraFaction\Manager\Listener
 */
class UltraFactionListener implements Listener {

	public $plugin;

	/**
	 * UltraFactionListener constructor.
	 * @param UltraFaction $plugin
	 */
	public function __construct(UltraFaction $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @param PlayerLoginEvent $event
	 */
	public function onPre(PlayerLoginEvent $event){

		$player = $event->getPlayer();
		$this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new RequestPlayerFaction($player->getName(), $this->plugin->getDataFolder()));
	}
}