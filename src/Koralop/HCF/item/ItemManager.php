<?php

namespace Koralop\HCF\item;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\item\types\Dropper;
use Koralop\HCF\item\types\EnderPearl;
use Koralop\HCF\item\types\Fireworks;
use Koralop\HCF\item\types\GlassBottle;
use Koralop\HCF\item\types\SplashPotion;
use Koralop\HCF\modules\Modules;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

/**
 * Class ItemManager
 * @package Koralop\HCF\item
 */
class ItemManager extends Modules
{

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        ItemFactory::registerItem(new Fireworks(), true);
        ItemFactory::registerItem(new GlassBottle(), true);
        ItemFactory::registerItem(new Dropper(), true);
        Item::initCreativeItems();
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}