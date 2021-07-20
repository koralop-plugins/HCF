<?php

namespace Koralop\HCF\modules\pvp;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\modules\pvp\commands\DeathbanCommand;
use Koralop\HCF\modules\pvp\commands\EndCommand;
use Koralop\HCF\modules\pvp\commands\PvPCommand;
use Koralop\HCF\modules\pvp\commands\ReviveCommand;
use Koralop\HCF\modules\pvp\events\PvPListener;

/**
 * Class PvPManager
 * @package Koralop\HCF\modules\pvp
 */
class PvPManager extends Modules
{

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getPluginManager()->registerEvents(new PvPListener($this), $loader);

        $loader->getServer()->getCommandMap()->register('/pvp', new PvPCommand());
        $loader->getServer()->getCommandMap()->register('/deathban', new DeathbanCommand());
        $loader->getServer()->getCommandMap()->register('/revive', new ReviveCommand());
    }

    /**
     * @param HCFPlayer $player
     */
    public function addDeathBan(HCFPlayer $player)
    {
        $player->getCooldowns()->setDeathbanTime(HCFLoader::getYamlProvider()->getCooldowns()['deathBan']);
    }

    /**
     * @param HCFPlayer $player
     * @return int
     */
    public function getDeathBan(HCFPlayer $player): int
    {
        return $player->getCooldowns()->getDeathbanTime();
    }

    /**
     * @param HCFPlayer $player
     */
    public function removeDeathBan(HCFPlayer $player)
    {
        $player->getCooldowns()->setDeathbanTime(null);
    }

    /**
     * @param HCFPlayer $player
     * @return bool
     */
    public function isDeathBan(HCFPlayer $player): bool
    {
        return $player->getCooldowns()->getDeathbanTime() == null ? false : true;
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }
}