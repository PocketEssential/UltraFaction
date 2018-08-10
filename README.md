```
 _    _ _ _             ______         _   _                 
| |  | | | |           |  ____|       | | (_)                
| |  | | | |_ _ __ __ _| |__ __ _  ___| |_ _  ___  _ __  ___ 
| |  | | | __| '__/ _` |  __/ _` |/ __| __| |/ _ \| '_ \/ __|
| |__| | | |_| | | (_| | | | (_| | (__| |_| | (_) | | | \__ \
 \____/|_|\__|_|  \__,_|_|  \__,_|\___|\__|_|\___/|_| |_|___/
```
### The Feature-Rich Ultra Factions plugin for your Minecraft: Pocket Edition PHP7 Server!
#### Create your Faction, Claim your Land, Fight your Enemies!

**This is being MAINTAINED for Versai.pro**
## Features
- [x] 3D chunk claiming system with configurable claim sizes
- [x] Faction homes, protections, and money.
- [x] Faction, Ally, Enemy, Global, Local, and even custom chat channels (for the more advanced factions, with complicated relations).
- [x] On-the-fly loading of config and data files - no reloading required! 
- [x] A very complex (but simple to use!) access system that allows for both global permission changes and per chunk access settings. 
- [x] Warzone, safezone, and peaceful faction toggles - for example, you can have multiple warzone factions, each with their own names.
- [x] Custom language/messages support. Server admins can translate the plugins themselves, or just replace the messages with their own.
- [x] Open your faction, allow others to join without invitations!

### Unique but *simple* API


#### Checking if the player belongs to a faction
Make sure you're using: `xZeroMCPE\UltraFaction\UltraFaction`

```php

/*
* $player should be instance of a Player
* returns a bool
*/

UltraFaction::getInstance()->getFactionManager()->isInFaction($player);
```

#### Get a player faction

```php

/*
* $player should be instance of a Player
* returns a \Faction\Faction object
* You should check if they belong to a faction first!
*/

UltraFaction::getInstance()->getFactionManager()->getFaction($player);
```

#### Not quite yet
We have a bunch of events you can tie to.

```php
// Listen to when someone creates a faction?

public function onCreate(FactionCreateEvent $event){
  
  $player = $event->getPlayer();
  
  if($event->getFactionName() == "Zero"){
   $player->sendMessage("You can't use that faction name because you're not cool!");
  }
}
```


#### That's all you need to know for now.
We have a ton of others, we'll try to update the readme, or alternatively, create a wiki

# Builds
[![Poggit-CI](https://poggit.pmmp.io/ci.badge/PocketEssential/UltraFaction/UltraFaction)](https://poggit.pmmp.io/ci/PocketEssential/UltraFaction/UltraFaction)
