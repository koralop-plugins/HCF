<?php

namespace Koralop\HCF\modules\subclaim\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

/**
 * Class SubClaimListener
 * @package Koralop\HCF\modules\subclaim\events
 */
class SubClaimListener implements Listener
{

    /**
     * @param SignChangeEvent $event
     */
    public function SignChangeEvent(SignChangeEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($player instanceof HCFPlayer) {
            if ($player->inFaction()) {

                if ($event->getLine(0) == '' || $event->getLine(1) == '')
                    return;

                if ($event->getLine(0) == '[subclaim]') {
                    if (HCFLoader::getFactionManager()->isFactionRegion($block) && HCFLoader::getFactionManager()->getFactionByPosition($block) == $player->getFactionName()) {
                        $event->setLine(0, TextFormat::RED . '[Subclaim]');
                        $event->setLine(1, $player->getName());

                    }
                }


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
        $chest = $block->getLevel()->getTile($block);

        if ($chest instanceof Chest) {

            for ($face = 2; $face <= 5; $face++) {
                $tile = $chest->getLevel()->getTile($chest->getSide($face));
                if ($tile instanceof Sign) {
                    if ($tile->getLine(0) === TextFormat::RED . '[Subclaim]') {
                        $players = [$tile->getLine(1), $tile->getLine(2), $tile->getLine(3)];
                        foreach ($players as $member) {
                            if ($member === $player->getName()) {
                                return;
                            }
                        }
                        $event->setCancelled();
                        return;
                    }
                }
            }

            if ($chest->isPaired() === true) {
                $pair = $chest->getPair();
                for ($face = 2; $face <= 5; $face++) {
                    $tile = $pair->getLevel()->getTile($pair->getSide($face));
                    if ($tile instanceof Sign) {
                        if ($tile->getLine(0) === TextFormat::RED . '[Subclaim]') {
                            $players = [$tile->getLine(1), $tile->getLine(2), $tile->getLine(3)];
                            foreach ($players as $member) {
                                if ($member === $player->getName()) {
                                    return;
                                }
                            }
                            $event->setCancelled();
                            $player->sendMessage(TextFormat::colorize('&cNo Permission.'));
                            return;
                        }
                    }
                }
            }
        }
    }
}