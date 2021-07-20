<?php

namespace Koralop\HCF\modules\logout;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\logout\commands\LogoutCommand;
use Koralop\HCF\modules\logout\events\LogoutListener;
use Koralop\HCF\modules\Modules;

/**
 * Class LogoutManager
 * @package Koralop\HCF\modules\logout
 */
class LogoutManager extends Modules
{

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getCommandMap()->register('/logout', new LogoutCommand());

        $loader->getServer()->getPluginManager()->registerEvents(new LogoutListener(), $loader);
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}