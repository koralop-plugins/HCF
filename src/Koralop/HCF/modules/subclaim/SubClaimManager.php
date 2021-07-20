<?php

namespace Koralop\HCF\modules\subclaim;


use Koralop\HCF\modules\Modules;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\subclaim\events\SubClaimListener;


/**
 * Class SubClaimManager
 * @package Koralop\HCF\modules\subclaim
 */
class SubClaimManager extends Modules
{


    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getPluginManager()->registerEvents(new SubClaimListener(), $loader);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}