<?php

namespace Koralop\HCF\player\cooldowns;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\player\cooldowns\scheduler\CooldownsTask;
use pocketmine\utils\Config;

/**
 * Class CooldownsManager
 * @package Koralop\HCF\player\cooldowns
 */
class CooldownManager
{

    /** @var Cooldown[] */
    protected array $playerData = [];

    /**
     * CooldownsManager constructor.
     */
    public function __construct()
    {
        HCFLoader::getInstance()->getScheduler()->scheduleRepeatingTask(new CooldownsTask($this), 20);
    }

    /**
     * @param HCFPlayer $player
     */
    public function addCooldown(HCFPlayer $player): void
    {
        $this->playerData[$player->getName()] = new Cooldown($player->getName());
    }

    /**
     * @param HCFPlayer $player
     * @return bool
     */
    public function isCooldown(HCFPlayer $player): bool
    {
        return isset($this->playerData[$player->getName()]);
    }

    /**
     * @param HCFPlayer $player
     */
    public function removeCooldown(HCFPlayer $player): void
    {
        if (!$this->isCooldown($player))
            return;

        unset($this->playerData[$player->getName()]);
    }

    /**
     * @param HCFPlayer $player
     * @return Cooldown
     */
    public function getCooldown(HCFPlayer $player): Cooldown
    {
        return $this->playerData[$player->getName()];
    }

    /**
     * @return Cooldown[]
     */
    public function getCooldowns(): array
    {
        return $this->playerData;
    }
}