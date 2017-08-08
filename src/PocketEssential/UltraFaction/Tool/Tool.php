<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/7/2017
 * Time: 7:22 PM
 */

namespace PocketEssential\UltraFaction\Tool;


use PocketEssential\UltraFaction\Commands\FactionCommand;
use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\utils\Config;

/**
 * Class tool
 * @package PocketEssential\UltraFaction\Tool
 */
class Tool {

	public $plugin;
	public $data;
	public static $instance;

	/**
	 * tool constructor.
	 * @param UltraFaction $plugin
	 */
	public function __construct(UltraFaction $plugin){
		$this->plugin = $plugin;
		$this->data = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
		self::$instance = $this;
	}

	/**
	 * @return Tool
	 */
	public static function getInstance(){
		return self::$instance;
	}

	public function ready(){

		if(!file_exists($this->plugin->getDataFolder())){
			@mkdir($this->plugin->getDataFolder());
		}

		if(!file_exists($this->plugin->getDataFolder() . "Factions")){
			@mkdir($this->plugin->getDataFolder() . "Factions");
		}

		if(!file_exists($this->plugin->getDataFolder() . "Factions/Data.json")){
			$t = [

			];

			file_put_contents($this->plugin->getDataFolder() . "Factions/Data.json", json_encode($t, JSON_PRETTY_PRINT));
		}

		if(!file_exists($this->plugin->getDataFolder() . "Factions/Factions.json")){
			$t = [

			];

			file_put_contents($this->plugin->getDataFolder() . "Factions/Factions.json", json_encode($t, JSON_PRETTY_PRINT));
		}

		$this->plugin->saveDefaultConfig();
		$this->data = $this->plugin->getConfig();


		$this->plugin->getServer()->getCommandMap()->registerAll('UltraFaction', [
			new FactionCommand($this->plugin)
		]);
	}

	/**
	 * @return mixed
	 */
	public function getLanguage(){

		return $this->data->get("Language");
	}

	/**
	 * @return mixed
	 */
	public function getProvider(){

		return $this->data->get("data-provider");
	}

	/**
	 * @return mixed
	 */
	public function getEconomy(){

		return $this->data->get("Economy");
	}
}