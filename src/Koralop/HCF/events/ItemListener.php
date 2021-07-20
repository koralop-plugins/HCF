<?php

namespace Koralop\HCF\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\Time;
use Koralop\HCF\utils\Translate;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\item\EnderPearl;
use pocketmine\item\GoldenApple;
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

/**
 * Class ItemListener
 * @package Koralop\HCF\events
 */
class ItemListener implements Listener
{

    /**
     * @param PlayerInteractEvent $event
     */
    public function itemListener(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();

        if ($player instanceof HCFPlayer) {

            if ($item->getNamedTag()->hasTag('air-drop')) {
                return;
            }

            if ($item instanceof EnderPearl) {
                if ($player->getCooldowns()->getEnderPearl() == null) {
                    $player->getCooldowns()->setEnderPearl(HCFLoader::getYamlProvider()->getCooldowns()['enderpearl']);
                } else {
                    $player->sendMessage(TextFormat::colorize('&cYou cannot use this for another &c&l' . gmdate('s', $player->getCooldowns()->getEnderPearl()) . ' &cseconds!'));
                    $event->setCancelled(true);
                }
            }


            if ($item->getNamedTag()->hasTag('lives')) {

                HCFLoader::getPlayerManager()->getPlayerData()->setLives($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName()) + ($item->getCount()));

                $player->getInventory()->setItemInHand(Item::get(0, 0, 0));

                $player->sendMessage(TextFormat::colorize('&aYou received ' . $item->getCount() . ' lives.'));
            }

            if ($item->getNamedTag()->hasTag('crowbar')) {

                if ($event->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {

                    if ($block->getId() == 119 or $block->getId() == 120) {

                        if ($player->getLevel()->getFolderName() != HCFLoader::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                            $player->sendMessage(Translate::getMessage(
                                '&cYou cannot use spawners in %world%.',
                                [
                                    'world' => $player->getLevel()->getFolderName()
                                ]));

                            return;
                        }

                        if ($player->inFaction()) {

                            if (HCFLoader::getFactionManager()->isFactionRegion($player)) {

                                if (HCFLoader::getFactionManager()->getFactionByPosition($player) == $player->getFactionName()) {

                                    $player->getLevel()->setBlock($block, Block::get(BlockIds::AIR));
                                    $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1));

                                } else
                                    $player->sendMessage(TextFormat::colorize('&cYou cannot use the crowbar in that territory.'));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param PlayerItemConsumeEvent $event
     */
    public function PlayerItemConsumeEvent(PlayerItemConsumeEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($player instanceof HCFPlayer) {

            if ($item instanceof GoldenApple) {
                if ($player->getCooldowns()->getAppleTime() == null) {
                    $player->getCooldowns()->setAppleTime(HCFLoader::getYamlProvider()->getCooldowns()['apple']);
                } else {
                    $player->sendMessage(TextFormat::colorize('&cYou cannot use this for another &c&l' . Time::asd($player->getCooldowns()->getAppleTime()) . ' &cseconds!'));
                    $event->setCancelled(true);
                }
            }
            if ($item instanceof GoldenAppleEnchanted) {
                if ($player->getCooldowns()->getGappleTime() == null) {
                    $player->getCooldowns()->setGappleTime(HCFLoader::getYamlProvider()->getCooldowns()['gapple']);
                } else {
                    $player->sendMessage(TextFormat::colorize('&cYou cannot use this for another &c&l' . Time::asd($player->getCooldowns()->getGappleTime()) . ' &cseconds!'));
                    $event->setCancelled(true);
                }
            }
        }
    }
}