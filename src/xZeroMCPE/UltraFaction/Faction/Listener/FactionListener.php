<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/9/2018
 * Time: 6:47 PM
 */

namespace xZeroMCPE\UltraFaction\Faction\Listener;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use xZeroMCPE\UltraFaction\Faction\Event\FactionMemberAttackMemberEvent;
use xZeroMCPE\UltraFaction\UltraFaction;

class FactionListener implements Listener
{

    public function onDamage(EntityDamageEvent $event)
    {

        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();

                if ($damager instanceof Player) {
                    if (UltraFaction::getInstance()->getFactionManager()->isInFaction($player)
                        && (UltraFaction::getInstance()->getFactionManager()->isInFaction($damager))) {
                        if (UltraFaction::getInstance()->getFactionManager()->getFaction($player)->getName() ==
                            UltraFaction::getInstance()->getFactionManager()->getFaction($damager)->getName()) {

                            UltraFaction::getInstance()->getServer()->getPluginManager()->callEvent($event = new FactionMemberAttackMemberEvent(UltraFaction::getInstance(), $damager, UltraFaction::getInstance()->getFactionManager()->getFaction($damager), $player));

                            if (!$event->isCancelled()) {
                                $message = str_replace(['{VICTIM}'], [$player->getName()], UltraFaction::getInstance()->getLanguage()->getLanguageValue('MEMBER_ATTACK_MEMBER'));
                                $damager->sendMessage($message);
                                $event->setCancelled();
                            }
                        }
                    }
                }
            }
        }
    }
}