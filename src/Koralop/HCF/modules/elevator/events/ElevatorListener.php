<?php

namespace Koralop\HCF\modules\elevator\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\elevator\ElevatorManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Position;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

/**
 * Class ElevatorListener
 * @package Koralop\HCF\modules\elevator\events
 */
class ElevatorListener implements Listener
{

    /** @var ElevatorManager */
    protected ElevatorManager $manager;

    /**
     * ElevatorListener constructor.
     * @param ElevatorManager $manager
     */
    public function __construct(ElevatorManager $manager)
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
        $tile = HCFLoader::getInstance()->getServer()->getDefaultLevel()->getTileAt($block->x, $block->y, $block->z);

        if ($tile instanceof Sign) {

            if ($player instanceof HCFPlayer) {

                $text = $tile->getText();

                if ($text[0] == TextFormat::DARK_RED . '[Elevator]') {

                    if ($text[1] == 'Up' or $text[1] == 'up') {
                        $player->teleport(new Position($block->getFloorX(), $this->manager->getTextUp($block->getFloorX(), $block->getFloorY(), $block->getFloorZ()), $block->getFloorZ(), $player->getLevel()));
                    }

                    if ($text[1] == TextFormat::RED . 'INVALID') {
                        $player->sendMessage("&cPlease use one of the following status''s for line 2: Up", true);
                    }
                }
            }
        }
    }

    /**
     * @param SignChangeEvent $event
     */
    public function SignChangeEvent(SignChangeEvent $event): void
    {
        if ($event->getLine(0) == '[elevator]' or $event->getLine(0) == '[Elevator]') {
            if ($event->getLine(1) == 'Up' or $event->getLine(1) == 'up') {
                $event->setLine(0, TextFormat::DARK_RED . '[Elevator]');
                $event->setLine(1, 'Up');
            } else {
                $event->setLine(0, TextFormat::DARK_RED . '[Elevator]');
                $event->setLine(1, TextFormat::RED . 'INVALID');
            }
        }
    }
}