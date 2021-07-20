<?php

namespace Koralop\HCF\tile;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\tile\types\Dropper;
use Koralop\HCF\tile\types\MobSpawner;
use pocketmine\tile\Tile;

/**
 * Class TileManager
 * @package Koralop\HCF\tile
 */
class TileManager extends Modules
{

    /**
     * @param HCFLoader $loader
     * @throws \ReflectionException
     */
    public function onEnable(HCFLoader $loader): void
    {
        Tile::registerTile(Dropper::class, ['Dropper']);
        Tile::registerTile(MobSpawner::class, ['MobSpawner']);
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}