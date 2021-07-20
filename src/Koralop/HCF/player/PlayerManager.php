<?php

namespace Koralop\HCF\player;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\player\commands\BalanceCommand;
use Koralop\HCF\player\commands\EconomyCommand;
use Koralop\HCF\player\commands\LeaderboardsCommand;
use Koralop\HCF\player\commands\LivesCommand;
use Koralop\HCF\player\commands\StatsCommand;
use Koralop\HCF\player\data\PlayerData;

/**
 * Class PlayerManager
 * @package Koralop\HCF\player
 */
class PlayerManager
{

    /**
     * PlayerManager constructor.
     * @param HCFLoader $loader
     */
    public function __construct(HCFLoader $loader)
    {
        $loader->getServer()->getCommandMap()->register('stats', new StatsCommand());
        $loader->getServer()->getCommandMap()->register('leaderboards', new LeaderboardsCommand());
        $loader->getServer()->getCommandMap()->register('lives', new LivesCommand());
        $loader->getServer()->getCommandMap()->register('balance', new BalanceCommand());
        $loader->getServer()->getCommandMap()->register('eco', new EconomyCommand());
    }

    /**
     * @return PlayerData
     */
    public function getPlayerData(): PlayerData
    {
        return new PlayerData();
    }
}