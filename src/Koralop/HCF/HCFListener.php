<?php

namespace Koralop\HCF;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use HCF\VauteRanks;

/**
 * Class HCFListener
 * @package Koralop\HCF
 */
class HCFListener implements Listener
{

    /**
     * @param PlayerCreationEvent $event
     */
    public function PlayerCreationEvent(PlayerCreationEvent $event): void
    {
        $event->setPlayerClass(HCFPlayer::class);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function PlayerJoinEvent(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {
            $player->join();
        }
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function PlayerQuitEvent(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {
            $player->quit();
        }
    }
}