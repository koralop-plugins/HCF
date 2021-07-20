<?php

namespace Koralop\HCF\modules\elevator;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\elevator\events\ElevatorListener;
use Koralop\HCF\modules\Modules;
use pocketmine\block\Air;
use pocketmine\block\Solid;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

/**
 * Class ElevatorManager
 * @package Koralop\HCF\modules\elevator
 */
class ElevatorManager extends Modules
{

    /** @var string */
    public const UP = 'Up';

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
    }

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getPluginManager()->registerEvents(new ElevatorListener($this), $loader);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @return int
     */
    public function getTextUp(int $x, int $y, int $z): int
    {
        $level = HCFLoader::getInstance()->getServer()->getDefaultLevel();
        for ($i = $y + 1; $i <= 256; $i++) {
            $pos1 = $level->getBlockAt($x, $i, $z);
            $pos2 = $level->getBlockAt($x, $i + 1, $z);
            $pos3 = $level->getBlockAt($x, $i - 1, $z);
            if ($pos1 instanceof Air && $pos2 instanceof Air && $pos3 instanceof Solid) {
                return $i;
            }
        }
        return $y;
    }
}