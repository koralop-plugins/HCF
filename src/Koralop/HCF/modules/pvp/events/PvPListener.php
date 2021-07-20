<?php

namespace Koralop\HCF\modules\pvp\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\modules\pvp\PvPManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\level\Position;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

/**
 * Class PvPListener
 * @package Koralop\HCF\modules\pvp\events
 */
class PvPListener implements Listener
{

    /** @var PvPManager */
    protected PvPManager $manager;

    /**
     * PvPListener constructor.
     * @param PvPManager $manager
     */
    public function __construct(PvPManager $manager)
    {
        $this->manager = $manager;
    }

    /***
     * @param SignChangeEvent $event
     */
    public function SignChangeEvent(SignChangeEvent $event): void
    {
        if ($event->getLine(0) == 'DeathBan') {
            if ($event->getLine(1) == 'Revive') {
                $event->setLine(0, '¿Have a life?');
                $event->setLine(1, TextFormat::GREEN . 'Click Here');
            }

            if ($event->getLine(1) == 'Kit') {
                $event->setLine(0, TextFormat::GREEN . '- Kit -');
                $event->setLine(1, TextFormat::GREEN . 'PvP');
            }
        }
    }


    /**
     * @param PlayerInteractEvent $event
     */
    public function PlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $diff = $player->getLevel()->getTileAt($block->x, $block->y, $block->z);

        if ($diff instanceof Sign) {

            if (!$player instanceof HCFPlayer)
                return;

            $line = $diff->getText();
            if ($line[1] == TextFormat::GREEN . 'Click Here') {
                if (HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName()) != 0) {
                    HCFLoader::getPlayerManager()->getPlayerData()->setLives($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName()) - 1);

                    $player->spawn();

                    $player->getCooldowns()->setDeathbanTime(null);
                } else $player->sendMessage(TextFormat::RED . 'You don' . "'t" . ' have lives!');
            }
            if ($line[1] == TextFormat::GREEN . 'PvP') {
                if (HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->isKit('Deathban')) {
                    HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getKit('Deathban')->setKit($player);
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
            if ($player->getCooldowns()->getPvPTimer() != null) {
                if (HCFLoader::getFactionManager()->isFaction($player->getBeforeClaim())) {
                    $faction = HCFLoader::getFactionManager()->getFaction($player->getBeforeClaim());

                    if ($faction->getDtr() == 1000)
                        return;

                    if ($faction->getDtr() == 1200)
                        return;

                    if ($faction->getName() == $player->getFactionName())
                        return;

                    $event->setCancelled(true);
                }
            }
        }

        if ($this->manager->isDeathBan($player)) {
            $c = HCFLoader::getYamlProvider()->getConfig()->get('deathban')['spawn'];
            if ($player->getLevel()->getFolderName() != $c[3]) {
                $player->teleport(new Position($c[0], $c[1], $c[2], HCFLoader::getInstance()->getServer()->getLevelByName($c[3])));
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function PLayerDeathEvent(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($this->manager->isDeathBan($player)) {
                if (($player->getCooldowns()->getDeathbanTime() - (60 * 5)) < 0) {
                    $player->getCooldowns()->setDeathbanTime(null);

                    $player->spawn();
                    return;
                }
                $player->getCooldowns()->setDeathbanTime(($player->getCooldowns()->getDeathbanTime() - (60 * 5)));
                return;
            }

            if (HCFLoader::getFactionManager()->isFaction($player->getCurrentClaim())) {
                $faction = HCFLoader::getFactionManager()->getFaction($player->getCurrentClaim());

                if ($faction->getDtr() != 1000) {
                    $this->manager->addDeathBan($player);
                }
            }

            $this->manager->addDeathBan($player);

            $player->sendMessage(TextFormat::RED . 'You have been sent to the deathban arena.');
            $player->sendMessage(' ');
            $player->sendMessage(TextFormat::RED . 'You can click the sign to revive yourself if you have lives.');
            $player->sendMessage(TextFormat::RED . 'No lives? Hop in the PvP arena and kit some time.');
        }
    }

    /**
     * @param PlayerRespawnEvent $event
     */
    public function PlayerRespawnEvent(PlayerRespawnEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($this->manager->isDeathBan($player)) {
                $c = HCFLoader::getYamlProvider()->getConfig()->get('deathban')['spawn'];
                $player->teleport(new Position($c[0], $c[1], $c[2], HCFLoader::getInstance()->getServer()->getLevelByName($c[3])));

                return;
            } else {
                $player->getCooldowns()->setPvPTimer(HCFLoader::getYamlProvider()->getCooldowns()['pvptimer']);
            }
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function PlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($this->manager->isDeathBan($player)) {
                $event->setCancelled(true);
                $player->sendMessage(TextFormat::RED . "You can't run commands whilst deathbanned!");
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function EntityDamageEvent(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();

        if ($entity instanceof HCFPlayer) {

            if (HCFLoader::getFactionManager()->isFactionRegion($entity)) {
                if (HCFLoader::getFactionManager()->getFaction(HCFLoader::getFactionManager()->getFactionByPosition($entity))->getDtr() == 1000) {
                    return;
                }
            }

            if ($entity->getCooldowns()->getPvPTimer() != null) {
                $event->setCancelled(true);
            }
            
            if (HCFLoader::getModulesManager()->getModuleById(ModulesIds::TIMER)->isTimer('Sotw')) {
                $sotw = HCFLoader::getModulesManager()->getModuleById(ModulesIds::TIMER)->getTimer('Sotw');
                if ($sotw->isEnable()) {
                    $event->setCancelled(true);
                }
            }
        }
    }
}