<?php

namespace Koralop\HCF\entity;

use Koralop\HCF\entity\types\FireworksRocket;
use Koralop\HCF\entity\types\mob\Blaze;
use Koralop\HCF\entity\types\mob\Cow;
use Koralop\HCF\entity\types\mob\Creeper;
use Koralop\HCF\entity\types\mob\Enderman;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use pocketmine\entity\Entity;

/**
 * Class EntityManager
 * @package Koralop\HCF\entity
 */
class EntityManager extends Modules
{


    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        Entity::registerEntity(FireworksRocket::class, false, ['FireworksRocket']);

        Entity::registerEntity(Blaze::class, false, ['Blaze']);
        Entity::registerEntity(Cow::class, false, ['Cow']);
        Entity::registerEntity(Creeper::class, false, ['Creeper']);
        Entity::registerEntity(Enderman::class, false, ['Enderman']);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}