<?php

namespace Koralop\HCF\events;

use addon\Math\lVector3;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;

use Koralop\HCF\modules\ce\Enchant;
use Koralop\HCF\utils\Translate;
use pocketmine\block\BlockIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;

/**
 * Class PlayerListener
 * @package Koralop\HCF\events
 */
class PlayerListener implements Listener
{

    /** @var array */
    protected array $hit;
    /** @var array */
    protected array $blocks = [];
    /** @var array */
    protected array $last = [];

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function EntityDamageByEntityEvent(EntityDamageByEntityEvent $event): void
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if ($damager instanceof HCFPlayer and $entity instanceof HCFPlayer) {

            if ($damager->getCooldowns()->getPvPTimer() != null) {
                $event->setCancelled(true);
                return;
            }

            if ($entity->getCooldowns()->getPvPTimer() != null) {
                $event->setCancelled(true);
                return;
            }

            if ($damager->getCooldowns()->getCombatTag() == null)
                $damager->sendMessage(TextFormat::YELLOW . 'You have been spawn-tagged for ' . TextFormat::RED . HCFLoader::getYamlProvider()->getCooldowns()['combat'] . TextFormat::YELLOW . ' seconds!');

            if ($entity->getCooldowns()->getCombatTag() == null)
                $entity->sendMessage(TextFormat::YELLOW . 'You have been spawn-tagged for ' . TextFormat::RED . HCFLoader::getYamlProvider()->getCooldowns()['combat'] . TextFormat::YELLOW . ' seconds!');

            $damager->getCooldowns()->setCombatTag(HCFLoader::getYamlProvider()->getCooldowns()['combat']);
            $entity->getCooldowns()->setCombatTag(HCFLoader::getYamlProvider()->getCooldowns()['combat']);

            if ($damager->getCooldowns()->getHomeTime() != null) {
                $damager->getCooldowns()->setHomeTime(null);
            }

            if ($entity->getCooldowns()->getHomeTime() != null) {
                $entity->getCooldowns()->setHomeTime(null);
            }

            if ($damager->getCooldowns()->getStuckTime() != null) {
                $damager->getCooldowns()->setStuckTime(null);
            }

            if ($entity->getCooldowns()->getStuckTime() != null) {
                $entity->getCooldowns()->setStuckTime(null);
            }

            $this->hit[$entity->getName()] = $damager->getDisplayName();
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function PlayerDeathEvent(PlayerDeathEvent $event): void
    {
        $entity = $event->getEntity();

        if ($entity instanceof HCFPlayer) {

            if (isset($this->hit[$entity->getName()])) {

                $player = HCFLoader::getInstance()->getServer()->getPlayer($this->hit[$entity->getName()]);

                if (!$player instanceof HCFPlayer)
                    return;

                $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::DARK_RED . '[' . HCFLoader::getPlayerManager()->getPlayerData()->getKills($entity->getName()) . ']' . TextFormat::YELLOW . ' was slain by ' . TextFormat::RED . $player->getName() . TextFormat::DARK_RED . '[' . HCFLoader::getPlayerManager()->getPlayerData()->getKills($player->getName()) . ']' . TextFormat::YELLOW . ' using ' . TextFormat::RED . $player->getInventory()->getItemInHand()->getCustomName());

                HCFLoader::getPlayerManager()->getPlayerData()->checkTop($player->getName());

                HCFLoader::getPlayerManager()->getPlayerData()->setDeaths($entity->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getDeaths($entity->getName()) + 1);
                HCFLoader::getPlayerManager()->getPlayerData()->setKills($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getKills($player->getName()) + 1);

                if ($entity->inFaction()) {
                    $entity->getFaction()->setPoints($entity->getFaction()->getPoints() + 1);
                }

                if ($player->inFaction()) {
                    $player->getFaction()->setPoints($player->getFaction()->getPoints() - 1);
                }

                if ($player->getInventory()->getItemInHand() instanceof Tool) {
                    $item = $player->getInventory()->getItemInHand();

                    $lore = $item->getLore();

                    $lore = array_merge($lore, [
                        Translate::getMessage('&e%dead% &fkilled by &e%killer% &6%date%', [
                            'dead' => $entity->getName(),
                            'killer' => $player->getName(),
                            'date' => date('d-m-Y') . ' ' . gmdate('H:i:s')
                        ])
                    ]);

                    $item->setLore(
                        $lore
                    );

                    $player->getInventory()->setItemInHand($item);
                }
            } else {
                switch ($entity->getLastDamageCause()->getCause()) {
                    case EntityDamageEvent::CAUSE_FALL:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' hit the ground too hard.');
                        break;
                    case EntityDamageEvent::CAUSE_DROWNING:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' thought he could swim.');
                        break;
                    case EntityDamageEvent::CAUSE_FIRE:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' thought he was a firefighter.');
                        break;
                    case EntityDamageEvent::CAUSE_FIRE_TICK:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' thought he was a firefighter.');
                        break;
                    case EntityDamageEvent::CAUSE_LAVA:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' burned to death.');
                        break;
                    case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' blew up.');
                        break;
                    case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' blew up.');
                        break;
                    case EntityDamageEvent::CAUSE_SUICIDE:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' has committed suicide.');
                        break;
                    case EntityDamageEvent::CAUSE_SUFFOCATION:
                        $event->setDeathMessage(TextFormat::RED . $entity->getName() . TextFormat::YELLOW . ' has suffocated in a wall.');
                        break;
                }
            }

            if ($entity->inFaction()) {
                $dtrLoss = [0.25, 0.50];
                $entity->getFaction()->setDtr($entity->getFaction()->getDtr() - $dtrLoss[array_rand($dtrLoss)]);

                $entity->getFaction()->sendMessage(
                    TextFormat::RED . 'Member Death: ' . TextFormat::WHITE . $entity->getName() . TextFormat::EOL .
                    TextFormat::RED . 'DTR: ' . TextFormat::WHITE . HCFLoader::getFactionManager()->getFaction($entity->getFactionName())->getDtr()
                );
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

            if ($player->getCooldowns()->getHomeTime() != null) {
                $player->getCooldowns()->setHomeTime(null);
            }

            if ($player->getCooldowns()->getStuckTime() != null) {
                $player->getCooldowns()->setStuckTime(null);
            }

            if ($player->getCooldowns()->getLogoutTime() != null) {
                $player->getCooldowns()->setLogoutTime(null);
            }
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function PlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if ($player instanceof HCFPlayer) {
            if (in_array($message, HCFLoader::getYamlProvider()->getCommandsBlockedInCombatTag())) {
                if ($player->getCooldowns()->getCombatTag() != null) {
                    $player->sendMessage('&cYou may not do this in combat.', true);
                    $event->setCancelled(true);
                    return;
                }
            }
        }
    }


    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        if (!$event->isCancelled()) {
            if ($block->getId() == BlockIds::DIAMOND_ORE) {

                if (!isset($this->blocks[lVector3::vector3AsString($block->asVector3())])) {
                    $count = 0;
                    for ($x = $block->getX() - 4; $x <= $block->getX() + 4; $x++) {
                        for ($z = $block->getZ() - 4; $z <= $block->getZ() + 4; $z++) {
                            for ($y = $block->getY() - 4; $y <= $block->getY() + 4; $y++) {
                                if ($player->getLevel()->getBlockIdAt($x, $y, $z) == BlockIds::DIAMOND_ORE) {
                                    if (!isset($this->blocks[lVector3::vector3AsString(new Vector3($x, $y, $z))])) {
                                        $this->blocks[lVector3::vector3AsString(new Vector3($x, $y, $z))] = true;
                                        ++$count;
                                    }
                                }
                            }
                        }
                    }
                    HCFLoader::getInstance()->getServer()->broadcastMessage(TextFormat::WHITE . '[FD] ' . TextFormat::AQUA . $player->getName() . ' found ' . $count . ' diamonds.');
                }
            }
        }
    }
}