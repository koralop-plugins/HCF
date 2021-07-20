<?php

namespace Koralop\HCF\modules\kit\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\utils\Translate;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

/**
 * Class KitListener
 * @package Koralop\HCF\modules\kit\events
 */
class KitListener implements Listener
{

    /**
     * @param PlayerInteractEvent $event
     */
    public function kitListener(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($player instanceof HCFPlayer) {

            if ($player->getClass() != null) {
                $class = HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById($player->getClass());


                if ($player->getClass() == KitIds::ARCHER) {

                    if ($class->getEnergyByItem($item->getId()) != null) {

                        if (HCFLoader::getFactionManager()->isFactionRegion($player)) {
                            if (HCFLoader::getFactionManager()->getFaction(HCFLoader::getFactionManager()->getFactionByPosition($player))->getDtr() == 1000) {
                                $player->sendMessage('&cYou cannot use Bard effects in SafeZone areas.', true);
                                return;
                            }
                        }

                        if ($class->getEnergyByItem($item->getId()) < $player->getCooldowns()->getArcherEnergy()) {

                            if ($player->getCooldowns()->getEffectCooldown($class->getEffectNameByItem($item)) == null) {
                                return;
                            }

                            $player->addEffect($class->getEffectByItem($item));

                            $player->getCooldowns()->setEffectCooldown($class->getEffectNameByItem($item), 15);
                            $player->getCooldowns()->setArcherEnergy($player->getCooldowns()->getArcherEnergy() - $class->getEnergyByItem($item->getId()));
                        }
                    }
                }

                if ($player->getClass() == KitIds::MAGE) {

                    if ($class->getEnergyByItem($item->getId()) != null) {

                        if (HCFLoader::getFactionManager()->isFactionRegion($player)) {
                            if (HCFLoader::getFactionManager()->getFaction(HCFLoader::getFactionManager()->getFactionByPosition($player))->getDtr() == 1000) {
                                $player->sendMessage('&cYou cannot use Bard effects in SafeZone areas.', true);
                                return;
                            }
                        }

                        if ($class->getEnergyByItem($item->getId()) < $player->getCooldowns()->getMageEnergy()) {
                            $player->addEffect($class->getEffectByItem($item));

                            $player->getCooldowns()->setMageEnergy($player->getCooldowns()->getMageEnergy() - $class->getEnergyByItem($item->getId()));
                        }

                    }
                }

                if ($player->getClass() == KitIds::BARD) {

                    if ($class->getEnergyByItem($item->getId()) != null) {

                        if (HCFLoader::getFactionManager()->isFactionRegion($player)) {
                            if (HCFLoader::getFactionManager()->getFaction(HCFLoader::getFactionManager()->getFactionByPosition($player))->getDtr() == 1000) {
                                $player->sendMessage('&cYou cannot use Bard effects in SafeZone areas.', true);
                                return;
                            }
                        }

                        if ($class->getEnergyByItem($item->getId()) < $player->getCooldowns()->getBardEnergy()) {

                            if ($player->getCooldowns()->getEffectCooldown($class->getEffectNameByItem($item)) != null) {
                                return;
                            }

                            if ($player->inFaction()) {
                                foreach ($player->getFaction()->getOnlinePlayers() as $onlinePlayer) {
                                    $onlinePlayer->addEffect($class->getEffectByItem($item));
                                }
                            } else
                                $player->addEffect($class->getEffectByItem($item));

                            $player->getCooldowns()->setEffectCooldown($class->getEffectNameByItem($item), 15);
                            $player->getCooldowns()->setBardEnergy($player->getCooldowns()->getBardEnergy() - $class->getEnergyByItem($item->getId()));

                            $player->sendMessage(Translate::getMessage(
                                '&eYou have just used &b%effect% &ebard buff for &a%energy%&e energy.',
                                [
                                    'effect' => $class->getEffectNameByItem($item),
                                    'energy' => $class->getEnergyByItem($item->getId())
                                ]), true);

                        } else
                            $player->sendMessage('&cYou do not have enough energy to use this effect.', true);

                    }
                }
            }
        }
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function PlayerMoveEvent(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($player->getFloorY() < 40) {
                if ($player->getClass() != null) {
                    if ($player->getClass() == 'Miner') {
                        if (!isset(HCFLoader::$pos[$player->getName()])) {
                            $player->sendMessage(TextFormat::BLUE . 'Miner invisibility ' . TextFormat::YELLOW . 'has been enabled!');
                            HCFLoader::$pos[$player->getName()] = true;
                        }
                        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 20, 2));
                    }
                }
            }
        }
    }

    /**
     * @param ProjectileHitEntityEvent $event
     */
    public function ProjectileHitEntityEvent(ProjectileHitEntityEvent $event): void
    {
        $entity = $event->getEntity();

        if ($entity instanceof Arrow) {

            $damager = $entity->getOwningEntity();

            if ($damager instanceof HCFPlayer && $event instanceof ProjectileHitEntityEvent && $damager->getClass() == KitIds::ARCHER) {

                if ($damager->getClass() == KitIds::ARCHER) {
                    $player = $event->getEntityHit();

                    if ($player instanceof HCFPlayer) {

                        if (HCFLoader::getFactionManager()->isFactionRegion($player) or HCFLoader::getFactionManager()->isFactionRegion($damager)) {
                            return;
                        }

                        if ($player->inFaction() && $damager->inFaction() && $player->getFactionName() == $damager->getFactionName()) {
                            return;
                        }

                        if ($player->getClass() == KitIds::ARCHER) {
                            $damager->sendMessage(Translate::getMessage(
                                '&e[&9Arrow Range &e(&c%range%&e)] &cCannot mark other Archers. &9&l(%hearts% hearts)',
                                [
                                    'range' => floor($damager->distance($player)),
                                    'time' => HCFLoader::getYamlProvider()->getCooldowns()['archer-mark'],
                                    'hearts' => $player->getHealth()
                                ]), true);
                            return;
                        }

                        $player->getCooldowns()->setArcherEnergy(HCFLoader::getYamlProvider()->getCooldowns()['archer-mark']);

                        $damager->sendMessage(Translate::getMessage(
                            '&e[&9Arrow Range &e(&c%range%&e)] &6Marked player for %time% seconds. &9&l(%hearts% hearts)',
                            [
                                'range' => floor($damager->distance($player)),
                                'time' => HCFLoader::getYamlProvider()->getCooldowns()['archer-mark'],
                                'hearts' => $player->getHealth()
                            ]), true);
                    }
                }
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function EntityDamageEvent(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();

        if ($event instanceof EntityDamageByEntityEvent) {

            $damager = $event->getDamager();

            if ($damager instanceof HCFPlayer && $entity instanceof HCFPlayer) {

                if ($entity->getClass() == KitIds::ARCHER) {
                    $event->setBaseDamage($event->getBaseDamage() + 2.0);
                }

                if ($damager->getClass() == KitIds::ROGUE) {
                    if ($damager->getInventory()->getItemInHand()->getId() == ItemIds::GOLD_SWORD) {
                        if (($entity->getHealth() - 4) < 0) {
                            $entity->setHealth(0);
                        } else {
                            $entity->setHealth($entity->getHealth() - 4);
                        }

                        $entity->sendMessage(Translate::getMessage(
                            '&cYou were backstabbed by %backstabber%',
                        [
                            'backstabber' => $entity->getName()
                        ]), true);

                        $damager->sendMessage(Translate::getMessage(
                            '&cYou backstabbed %backstabbed%',
                            [
                                'backstabbed' => $damager->getName()
                            ]
                        ), true);
                    }
                }
            }
        }
    }
}