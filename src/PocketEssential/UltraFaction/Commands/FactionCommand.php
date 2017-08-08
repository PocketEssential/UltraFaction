<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/7/2017
 * Time: 7:36 PM
 */

namespace PocketEssential\UltraFaction\Commands;


use PocketEssential\UltraFaction\Manager\FactionManager;
use PocketEssential\UltraFaction\UltraFaction;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class FactionCommand
 * @package PocketEssential\UltraFaction\Commands
 */
class FactionCommand extends Base {

	private $plugin;

	/**
	 * FactionCommand constructor.
	 * @param UltraFaction $plugin
	 */
	public function __construct(UltraFaction $plugin){
		$this->plugin = $plugin;
		parent::__construct($plugin, "faction", "UltraFaction main command", "/f help", ["f", "faction"]);
	}


	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{

		if($sender instanceof Player){

			switch($args[0]){

				case "help":
				case "h":
					$sender->sendMessage("-----.[ UltraFaction Help ].-----");
					$sender->sendMessage("/f create <Name> - Creates a faction");
					$sender->sendMessage("/f desc <Descriotion> - Change/set the faction description");
					$sender->sendMessage("/f open [yes/no] - Choose if invitation is required to join");
					$sender->sendMessage("/f invite <Player Name> - Invite a player to your faction");
					$sender->sendMessage("/f sethome - Sets the faction home at your current position");
					$sender->sendMessage("/f ally <Faction Name> - Ally with another faction");
					$sender->sendMessage("/f allyaccept <Faction Name> - Accept a ally request");
					$sender->sendMessage("/f war <Faction Name> - Send a war request");
					$sender->sendMessage("/f waraccept <Faction Name> - Accept a war request");
					$sender->sendMessage("/f rename <New Name> - Rename your faction name");
					$sender->sendMessage("/f kick <Player Name> - Kick a player off your faction");
					$sender->sendMessage("/f claim - Claim the plot your standing on");
					$sender->sendMessage("/f promote <Player Name> <Rank Type> - Promote a player on your faction");
					$sender->sendMessage("/f demote <Player Name> [Rank Type] - Demote a player to -1 rank below");

					break;

				case "create":
					if(!isset($args[1])){
						$sender->sendMessage("/f create <name>");
					}else{
						if(FactionManager::getInstance()->isInFaction($sender)){
							$sender->sendMessage("You're already in a faction");

						}else{
							if(FactionManager::getInstance()->isNameTaken($args[1])){
								$sender->sendMessage("That faction name already exist");
							}else{
								FactionManager::getInstance()->createFaction($sender, $args[1]);
								$sender->sendMessage("Faction successfully created!");
							}
						}
					}
					break;
				// Todo:Other help things such as War, Waraccept, change name, kick etc!
			}
		}
		return true;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin() : Plugin{
		return $this->plugin;
	}
}