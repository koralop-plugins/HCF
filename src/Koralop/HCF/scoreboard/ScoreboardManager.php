<?php

namespace Koralop\HCF\scoreboard;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\scoreboard\scheduler\ScoreboardTask;

/**
 * Class ScoreboardManager
 * @package Koralop\HCF\scoreboard
 */
class ScoreboardManager
{

    /** @var Scoreboard[] */
    protected array $scoreboard = [];

    /**
     * ScoreboardManager constructor.
     * @param HCFLoader $loader
     */
    public function __construct(HCFLoader $loader)
    {
        $loader->getScheduler()->scheduleRepeatingTask(new ScoreboardTask(), 20);
    }

    /**
     * @param HCFPlayer $player
     */
    public function addScoreboard(HCFPlayer $player): void
    {
        $this->scoreboard[$player->getName()] = new Scoreboard($player);
    }

    /**
     * @param HCFPlayer $player
     */
    public function removeScoreboard(HCFPlayer $player): void
    {
        unset($this->scoreboard[$player->getName()]);
    }

    /**
     * @param HCFPlayer $player
     * @return bool
     */
    public function isScoreboard(HCFPlayer $player): bool
    {
        return isset($this->scoreboard[$player->getName()]);
    }

    /**
     * @param HCFPlayer $player
     * @return Scoreboard
     */
    public function getScoreboard(HCFPlayer $player): Scoreboard
    {
        return $this->scoreboard[$player->getName()];
    }
}