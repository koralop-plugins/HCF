<?php

namespace Koralop\HCF\modules\claim\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\claim\ClaimManager;
use Koralop\HCF\modules\ModulesIds;
use pocketmine\block\Transparent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;

/**
 * Class ClaimListener
 * @package Koralop\HCF\modules\claim\events
 */
class ClaimListener implements Listener
{

    /** @var ClaimManager|null */
    protected ?ClaimManager $manager = null;

    /**
     * ClaimListener constructor.
     * @param ClaimManager $manager
     */
    public function __construct(ClaimManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function PlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $cClaim = HCFLoader::getYamlProvider()->getDefaultConfig()->get('map');

        if ($player instanceof HCFPlayer) {

            if ($item->getNamedTag()->hasTag('claim-tool')) {

                if (HCFLoader::getFactionManager()->isFactionRegion($block)) {
                    $player->sendMessage(TextFormat::colorize('&cYou cannot claim here.'));
                    return;
                }

                if ($event->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {

                    if ($this->manager->isClaim($player, 2))
                        if ($this->manager->getPos($player, 2)->distance($block) < 5) {
                            $player->sendMessage(TextFormat::colorize('&cYou must only claim a 5x5 area and claim must not exceed than 16 chunks.'));
                            return;
                        }

                    $this->manager->setPos($player, 1, $block);
                    $this->manager->createTower($player, $block, 20);

                    $player->sendMessage(TextFormat::colorize('&eSet position #1.'));
                }

                if ($event->getAction() == PlayerInteractEvent::LEFT_CLICK_BLOCK) {

                    if ($this->manager->isClaim($player, 1))
                        if ($this->manager->getPos($player, 1)->distance($block) < 5) {
                            $player->sendMessage(TextFormat::colorize('&cYou must only claim a 5x5 area and claim must not exceed than 16 chunks.'));
                            return;
                        }

                    $this->manager->setPos($player, 2, $block);
                    $this->manager->createTower($player, $block, 20);

                    $player->sendMessage(TextFormat::colorize('&eSet position #2.'));
                }

                if ($player->isSneaking()) {

                    if (!$block instanceof Transparent)
                        return;

                    if ($this->manager->isClaim($player, 1) && $this->manager->isClaim($player, 2)) {

                        if (!$player->inFaction())
                            return;

                        if ($player->getFaction()->getClaim() != null)
                            return;

                        if (($this->manager->getPos($player, 1)->distance($this->manager->getPos($player, 2)) * 40) > $player->getFaction()->getBalance()) {
                            if (!$player->isGod())
                                return;
                        }

                        $player->getFaction()->reduceBalance($this->manager->getPos($player, 1)->distance($this->manager->getPos($player, 2)) * 40);

                        $player->getFaction()->claimRegion($player->getLevel()->getName(), $this->manager->getPos($player, 1), $this->manager->getPos($player, 2));

                        $this->manager->createTower($player, $this->manager->getPos($player, 1), 0);
                        $this->manager->createTower($player, $this->manager->getPos($player, 2), 0);

                        $this->manager->removePos($player, 1);
                        $this->manager->removePos($player, 2);

                        $player->sendMessage(TextFormat::colorize('&cClaim completed.'));
                    } else
                        $player->sendMessage(TextFormat::colorize('&cYou must select both corners.'));
                } else {
                    if ($this->manager->isClaim($player, 1) && $this->manager->isClaim($player, 2)) {

                        if (!$block instanceof Transparent)
                            return;

                        $this->manager->createTower($player, $this->manager->getPos($player, 1), 0);
                        $this->manager->createTower($player, $this->manager->getPos($player, 2), 0);

                        $this->manager->removePos($player, 1);
                        $this->manager->removePos($player, 2);

                        $player->sendMessage(TextFormat::colorize('&eClaim selection cleared.'));
                    }
                }
            }
        }
    }
}