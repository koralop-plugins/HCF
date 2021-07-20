<?php

namespace Koralop\HCF\block;

use Koralop\HCF\block\types\Dropper;
use Koralop\HCF\block\types\MobSpawner;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use pocketmine\block\BlockFactory;

/**
 * Class BlockManager
 * @package Koralop\HCF\block
 */
class BlockManager extends Modules
{

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        BlockFactory::registerBlock(new Dropper(), true);
        BlockFactory::registerBlock(new MobSpawner(), true);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}