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
use pocketmine\utils\TextFormat;

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

			$manager = new FactionManager($this->plugin);

			if(!isset($args[0])){
				$sender->sendMessage("-----.[ UltraFaction Help ].-----");
				$sender->sendMessage("/f create <Name> - Creates a faction");
				$sender->sendMessage("/f desc <Descriotion> - Change/set the faction description");
				$sender->sendMessage("/f setdesc <Descriotion> - set the faction description");
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
			}else{
				switch($args[0]){

					default:
						$sender->sendMessage(UltraFaction::PREFIX."Please use /f help for a list of commands");
						break;

					case "help":
					case "h":
						$sender->sendMessage("-----.[ UltraFaction Help ].-----");
						$sender->sendMessage("/f create <Name> - Creates a faction");
						$sender->sendMessage("/f desc <Descriotion> - Change/set the faction description");
					$sender->sendMessage("/f setdesc <Descriotion> - set the faction description");
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
							$sender->sendMessage(UltraFaction::PREFIX."Please use /f create <name>");
						}else{
							if($manager->isInFaction($sender)){
								$sender->sendMessage(UltraFaction::PREFIX."You're already in a faction");

							}else{
								if($manager->isNameTaken($args[1])){
									$sender->sendMessage(UltraFaction::PREFIX."That faction name already exist");
								}else{
									$manager->createFaction($sender, $args[1]);
									$sender->sendMessage(UltraFaction::PREFIX."Faction successfully created!");
								}
							}
						}
						break;

					case "description":
						if(!$manager->isInFaction($sender)){
							$sender->sendMessage(UltraFaction::PREFIX."You're not in a faction");
						}else{
							$sender->sendMessage(UltraFaction::PREFIX."This faction description is: ".$manager->getFactionData($sender)['Description']);
						}
						break;

					case "desc":
					case "setdesc":
					case "setdescription":
						if(!$manager->isInFaction($sender)){
							$sender->sendMessage(UltraFaction::PREFIX."You're not in a faction");
						}else{
							if(!$manager->isLeader($sender)){
								$sender->sendMessage(UltraFaction::PREFIX."Only the faction leader can do this");
							} else {
								if(!isset($args[1])){
									$sender->sendMessage("/f desc <name>");
								} else {
									$manager->updateFactionData($sender, "Description", $args[1]);
									$sender->sendMessage(UltraFaction::PREFIX."Faction description has been set to $args[1]!");
								}
							}
						}
						break;

					case "members":
					case "member"	:
					if(!$manager->isInFaction($sender)){
						$sender->sendMessage(UltraFaction::PREFIX."You're not in a faction");
					}else{
						$sender->sendMessage("-- ".$manager->getFaction($sender)." Members:");
						$sender->sendMessage(TextFormat::YELLOW."Leader: ".TextFormat::LIGHT_PURPLE . $manager->getFactionLeader($sender));
						foreach($manager->getFactionData($sender)['Members'] as $m){
							$sender->sendMessage(TextFormat::YELLOW ."- ".TextFormat::GREEN . $m);
						}
					}
					break;

					case "allies":
						if(!$manager->isInFaction($sender)){
							$sender->sendMessage(UltraFaction::PREFIX."You're not in a faction");
						}else{
							if(count($manager->getFactionData($sender)['Allies']) == 0){
								$sender->sendMessage(UltraFaction::PREFIX."This faction does not have any allies");
							} else {
								$sender->sendMessage(UltraFaction::PREFIX."Allies:");
								foreach($manager->getFactionData($sender)['Allies'] as $a){
									$sender->sendMessage("- ".$a);
								}
							}
						}
						break;

					case "kick":
					case "k":
					if(!$manager->isInFaction($sender)){
						$sender->sendMessage(UltraFaction::PREFIX."You're not in a faction");
					}else{
						if(!$manager->isLeader($sender)){
							$sender->sendMessage(UltraFaction::PREFIX . "Only the faction leader can do this");
						}else{
							if(!isset($args[1])){
								$sender->sendMessage("/f kick <name>");
							}else{
								if($args[1] == $sender->getName()){
									$sender->sendMessage(UltraFaction::PREFIX . "You can't kick yourself!");
								}else{
									if(!in_array($args[1], $manager->getFactionData($sender)['Members'])){
										$sender->sendMessage(UltraFaction::PREFIX . $args[1] . " is not in your faction!");
									}else{
										$manager->kickFromFaction($sender, $args[1]);
										$sender->sendMessage(UltraFaction::PREFIX . "We successfully kicked {$args[1]} out of your faction!");
									}
								}
							}
						}
					}
					break;

					// Todo:Other help things such as War, Waraccept, change name, kick etc!
				}
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