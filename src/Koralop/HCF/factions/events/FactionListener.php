<?php

namespace Koralop\HCF\factions\events;


use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\block\{Air, ItemFrame, Door, Fence, FenceGate, Trapdoor, Chest, TrappedChest, Block};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;

/**
 * Class FactionListener
 * @package Koralop\HCF\factions\events
 */
class FactionListener implements Listener
{

    /**
     * @param PlayerInteractEvent $event
     */
    public function PlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();

        if ($block instanceof Fence || $block instanceof FenceGate || $block instanceof Door || $block instanceof Trapdoor) {
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {

                if (HCFLoader::getFactionManager()->isFactionRegion($block) or (new Vector3(0, 100, 0))->distance($block) < 500) {
                    if (!$player->isGod()) {
                        if (!$player->inFaction()) {
                            $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                            $event->setCancelled(true);
                        } else {
                            if (HCFLoader::getFactionManager()->getFactionByPosition($block) !== $player->getFactionName()) {
                                $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                                $event->setCancelled(true);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function PLayerMoveEvent(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($player->getBeforeClaim() != $player->getCurrentClaim()) {
                $format = TextFormat::GRAY . 'The Wilderness' . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                $format2 = TextFormat::GRAY . 'The Wilderness' . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';

                if ((new Vector3(0, 100, 0))->distance($player) < 500) {
                    $format = TextFormat::DARK_GREEN . 'Warzone' . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                }

                if (HCFLoader::getFactionManager()->isFaction($player->getCurrentClaim())) {
                    $faction = HCFLoader::getFactionManager()->getFaction($player->getCurrentClaim());

                    if ($faction->getDtr() == 1000) {
                        # Spawn
                        $format = TextFormat::GREEN . $player->getCurrentClaim() . TextFormat::YELLOW . ' (' . TextFormat::GREEN . 'No-Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($faction->getDtr() == 1200) {
                        # Roads
                        $format = TextFormat::GOLD . $player->getCurrentClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($faction->getDtr() == 1500) {
                        # Koth
                        $format = TextFormat::DARK_RED . $player->getCurrentClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($player->inFaction()) {
                        if ($player->getCurrentClaim() == $player->getFactionName()) {
                            $format = TextFormat::GREEN . $player->getFactionName() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                        }
                    }

                    if ($faction->getDtr() < 1000) {
                        if ($player->getCurrentClaim() != $player->getFactionName()) {
                            $format = TextFormat::RED . $player->getCurrentClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                        }
                    }
                }

                if ((new Vector3(0, 100, 0))->distance($player) < 500) {
                    $format2 = TextFormat::DARK_GREEN . 'Warzone' . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                }

                if (HCFLoader::getFactionManager()->isFaction($player->getBeforeClaim())) {
                    $faction = HCFLoader::getFactionManager()->getFaction($player->getBeforeClaim());

                    if ($faction->getDtr() == 1000) {
                        # Spawn
                        $format2 = TextFormat::GREEN . $player->getBeforeClaim() . TextFormat::YELLOW . ' (' . TextFormat::GREEN . 'No-Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($faction->getDtr() == 1200) {
                        # Roads
                        $format2 = TextFormat::GOLD . $player->getBeforeClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($faction->getDtr() == 1500) {
                        # Koth
                        $format2 = TextFormat::DARK_RED . $player->getBeforeClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                    }

                    if ($player->inFaction()) {
                        if ($player->getBeforeClaim() == $player->getFactionName()) {
                            $format2 = TextFormat::GREEN . $player->getFactionName() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                        }
                    }

                    if ($faction->getDtr() < 1000) {
                        if ($player->getBeforeClaim() != $player->getFactionName()) {
                            $format2 = TextFormat::RED . $player->getBeforeClaim() . TextFormat::YELLOW . ' (' . TextFormat::RED . 'Deathban' . TextFormat::YELLOW . ')';
                        }
                    }
                }

                $player->sendMessage(TextFormat::YELLOW . 'Now leaving: ' . TextFormat::RED . $format2);
                $player->sendMessage(TextFormat::YELLOW . 'Now entering: ' . TextFormat::RED . $format);
                $player->setBeforeClaim($player->getCurrentClaim());
            }
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function EntityDamageByEntityEvent(EntityDamageByEntityEvent $event): void
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if ($entity instanceof HCFPlayer and $damager instanceof HCFPlayer) {

            if ($entity->inFaction() and $damager->inFaction()) {
                if ($entity->getFactionName() == $damager->getFactionName()) {
                    $damager->sendMessage(TextFormat::YELLOW . 'You cannot hurt ' . TextFormat::DARK_GREEN . $entity->getName() . TextFormat::YELLOW . '.');
                    $event->setCancelled(true);
                }
            }
        }
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function PlayerChatEvent(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {
            switch ($player->getChat()) {
                case 'Faction':
                    if ($player->inFaction()) {
                        if ($player->getFaction()->getLeader() == $player->getName()) {
                            $player->getFaction()->sendMessage(TextFormat::GOLD . '[Leader] ' . TextFormat::BLUE . '(Team) ' . $player->getName() . ': ' . TextFormat::YELLOW . $event->getMessage());

                            $event->setCancelled(true);
                            return;
                        }

                        if ($player->getFaction()->isCoLeader($player->getName())) {
                            $player->getFaction()->sendMessage(TextFormat::GOLD . '[Co-Leader] ' . TextFormat::BLUE . '(Team) ' . $player->getName() . ': ' . TextFormat::YELLOW . $event->getMessage());

                            $event->setCancelled(true);
                            return;
                        }

                        $player->getFaction()->sendMessage(TextFormat::BLUE . '(Team) ' . $player->getName() . ': ' . TextFormat::YELLOW . $event->getMessage());
                        $event->setCancelled(true);
                    } else {
                        $player->sendMessage(TextFormat::RED . 'You are not in any faction!');
                        $event->setCancelled(true);
                    }
                    break;
                case HCFPlayer::ALLY_CHAT:

                    if (!$player->inFaction())
                        return;

                    if (count($player->getFaction()->getAllys()) == 0)
                        return;

                    foreach ($player->getFaction()->getAllys() as $ally) {
                        if (!HCFLoader::getFactionManager()->isFaction($ally)) {
                            $allys = [];

                            foreach ($player->getFaction()->getAllys() as $ally2) {
                                if ($ally2 != $ally)
                                    $allys[] = $ally2;
                            }

                            $player->getFaction()->setAllys($allys);
                            return;
                        }
                        $f = HCFLoader::getFactionManager()->getFaction($ally);

                        $f->sendMessage(TextFormat::BLUE . '(Ally) ' . $player->getName() . ': ' . TextFormat::YELLOW . $event->getMessage());
                    }
                    break;
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function BlockPlaceEvent(BlockPlaceEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if (HCFLoader::getFactionManager()->isFactionRegion($block) or (new Vector3(0, 100, 0))->distance($block) < 500) {
                if (!$player->isGod()) {
                    if (!$player->inFaction()) {
                        $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                        $event->setCancelled(true);
                    } else {
                        if (HCFLoader::getFactionManager()->getFactionByPosition($block) !== $player->getFactionName()) {
                            $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                            $event->setCancelled(true);
                        }
                    }
                }
            }

        }

    }

    /**
     * @param BlockBreakEvent $event
     */
    public function BlockBreakEvent(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {
            if (HCFLoader::getFactionManager()->isFactionRegion($block) or (new Vector3(0, 100, 0))->distance($block) < 500) {
                if (!$player->isGod()) {
                    if (!$player->inFaction()) {
                        $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                        $event->setCancelled(true);
                    } else {
                        if (HCFLoader::getFactionManager()->getFactionByPosition($block) !== $player->getFactionName()) {
                            $player->sendMessage(TextFormat::YELLOW . 'You cannot do this in ' . TextFormat::RED . HCFLoader::getFactionManager()->getFactionByPosition($block) . TextFormat::YELLOW . "'s territory.");
                            $event->setCancelled(true);
                        }
                    }
                }
            }
        }
    }
}