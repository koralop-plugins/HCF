<?php

namespace Koralop\HCF\modules\claim;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\claim\events\ClaimListener;
use Koralop\HCF\modules\Modules;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

/**
 * Class Claim
 * @package Koralop\HCF\utils
 */
class ClaimManager extends Modules
{

    /** @var array */
    public array $claim = [];

    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getPluginManager()->registerEvents(new ClaimListener($this), $loader);
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }

    /**
     * @param HCFPlayer $player
     * @param Vector3 $position
     */
    public function createTower(HCFPlayer $player, Vector3 $position, int $id)
    {
        for ($y = $position->y + 1; $y <= $position->y + 20; $y++) {
            $pk = new UpdateBlockPacket();
            $pk->x = (int)$position->x;
            $pk->y = (int)$y;
            $pk->z = (int)$position->z;
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $id;
            $player->dataPacket($pk);
        }
    }

    /**
     * @param HCFPlayer $player
     * @param int $position
     * @return Vector3|null
     */
    public function getPos(HCFPlayer $player, int $position): ?Vector3
    {
        if ($this->isClaim($player, $position))
            return $this->claim[$player->getName()][$position];

        return null;
    }

    /**
     * @param HCFPlayer $player
     * @param int $position
     * @return bool
     */
    public function isClaim(HCFPlayer $player, int $position): bool
    {
        return isset($this->claim[$player->getName()][$position]);
    }

    /**
     * @param HCFPlayer $player
     * @param int $position
     * @param Vector3 $vector3
     */
    public function setPos(HCFPlayer $player, int $position, Vector3 $vector3): void
    {
        $this->claim[$player->getName()][$position] = $vector3;
    }

    /**
     * @param HCFPlayer $player
     * @param int $position
     */
    public function removePos(HCFPlayer $player, int $position): void
    {
        if ($this->isClaim($player, $position))
            unset($this->claim[$player->getName()][$position]);
    }
}