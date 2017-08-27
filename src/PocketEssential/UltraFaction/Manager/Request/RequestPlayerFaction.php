<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/27/2017
 * Time: 3:17 PM
 */

namespace PocketEssential\UltraFaction\Manager\Request;


use PocketEssential\UltraFaction\Tool\Tool;
use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

/**
 * Class RequestPlayerFaction
 * @package PocketEssential\UltraFaction\Manager\Request
 */
class RequestPlayerFaction extends AsyncTask {

	public $request;
	public $__dir;

	/**
	 * RequestPlayerFaction constructor.
	 * @param mixed|null $request
	 * @param $__dir
	 */
	public function __construct($request, $__dir){
		$this->request = $request;
		$this->__dir = $__dir;
	}

	public function onRun(){

		$file = file_get_contents($this->__dir . "Factions/Data.json");

		$deject = json_decode($file, true);

		$info = [
			"Status" => [
				"hasFaction" => "No",
				"factionName" => "*",
			],
			"Username" => $this->request
		];

		if(isset($deject[$this->request])){
			$info['Status']['hasFaction'] = "Yes";

			$file = file_get_contents($this->__dir . "Factions/Factions.json");

			$bject = json_decode($file, true);
			$info['Status']['factionName'] = $bject[$deject[$this->request]['Faction']]['Name'];
		}
		unset($this->request);
		$this->setResult($info);
	}

	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server){

		if($server->getPlayer($this->getResult()['Username']) == null) return;

			$po = [
				TextFormat::RED."----- Ultra Faction -----",
				TextFormat::BLUE."User: ".TextFormat::YELLOW.$this->getResult()['Username'],
				TextFormat::BLUE."hasFaction: ".TextFormat::YELLOW.$this->getResult()['Status']['hasFaction'] . " (".$this->getResult()['Status']['factionName'].")",
				TextFormat::BLUE."lastPlayed: ".date("Y-m-d H:i:s", $server->getPlayer($this->getResult()['Username'])->getLastPlayed()),
				TextFormat::RED."-------------------------"
			];

			foreach($po as $pt){
				Tool::getUltraFaction()->getLogger()->info($pt);
		}
	}
}