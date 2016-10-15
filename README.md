# UltraFaction
Rich Ultra Faction plugin for Minecraft: Pocket Edition
Create your Faction, Claim your Land, Fight your Enemies!

## Features
- [x] 3D chunk claiming system with configurable claim sizes
- [x] Faction homes, protections, and money.
- [x] Faction, Ally, Enemy, Global, Local, and even custom chat channels (for the more advanced factions, with complicated relations).
- [x] On-the-fly loading of config and data files - no reloading required! 
- [x] A very complex (but simple to use!) access system that allows for both global permission changes and per chunk access settings. 
- [x] Warzone, safezone, and peaceful faction toggles - for example, you can have multiple warzone factions, each with their own names.
- [x] Custom language/messages support. Server admins can translate the plugins themselves, or just replace the messages with their own.

### Simple API

```php
// First you"ll need this
$UltraFaction = $this->getServer()->getPluginManager()->getPlugin("UltraFaction");
```

#### Checking if the player belongs to a faction

```php
// $player should be Instance of Player!

$UltraFaction->IsPlayerInFaction($player)

/*
  Will return "true". If the player is in a faction, Or false if not
*/
```

#### More API examples will be added, as of now we're more into finishing the plugin and adding as much features as we can. And this includes a simple API that allows developers to extend UltraFaction without the need of editing the core itself!
