<?php



/**
 *
 * 8888888b.                   888               888    8888888888                                    888    d8b          888
 * 888   Y88b                  888               888    888                                           888    Y8P          888
 * 888    888                  888               888    888                                           888                 888
 * 888   d88P .d88b.   .d8888b 888  888  .d88b.  888888 8888888   .d8888b  .d8888b   .d88b.  88888b.  888888 888  8888b.  888
 * 8888888P" d88""88b d88P"    888 .88P d8P  Y8b 888    888       88K      88K      d8P  Y8b 888 "88b 888    888     "88b 888
 * 888       888  888 888      888888K  88888888 888    888       "Y8888b. "Y8888b. 88888888 888  888 888    888 .d888888 888
 * 888       Y88..88P Y88b.    888 "88b Y8b.     Y88b.  888            X88      X88 Y8b.     888  888 Y88b.  888 888  888 888
 * 888        "Y88P"   "Y8888P 888  888  "Y8888   "Y888 8888888888 88888P'  88888P'  "Y8888  888  888  "Y888 888 "Y888888 888
 *
 * Copyright (C) 2016 PocketEssential
 *
 * This is a public software, you cannot redistribute it a and/or modify any way
 * unless otherwise given permission to do so.
 *
 * @author PocketEssential
 * @link https://github.com/PocketEssential/
 *
 */

namespace PocketEssential\UltraFaction;


use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;

class UltraFaction extends PluginBase implements Listener{

    /*
     * Registering the PREFIX!
     */
    const PREFIX = TextFormat::YELLOW."[".TextFormat::AQUA."Faction".TextFormat::YELLOW."]";


    public function onEnable(){
        $this->config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
    }

    public function onDisable(){
        $this->saveFile();
    }

    /*
     *  For easy access, for saving configs / resources.
     */

    public function saveFile(){
        $this->getLogger()->info(TextFormat::YELLOW."|| Saving all files ||");
        $this->getConfig->save();
        $this->getLogger()->info(TextFormat::DARK_BLUE."All config / files has been saved!");
    }
}