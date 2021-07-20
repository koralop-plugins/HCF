<?php

declare(strict_types = 1);

namespace Koralop\HCF\entity\types;

use Koralop\HCF\item\types\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

/**
 * Class FireworksRocket
 * @package Koralop\Extensions\entitys\types
 */
class FireworksRocket extends Entity
{

    public const NETWORK_ID = Entity::FIREWORKS_ROCKET;

    public const DATA_FIREWORK_ITEM = 16; //firework item

    public $width = 0.25;
    public $height = 0.25;

    /** @var int */
    protected $lifeTime = 0;

    /**
     * FireworksRocket constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param Fireworks|null $fireworks
     */
    public function __construct(Level $level, CompoundTag $nbt, ?Fireworks $fireworks = null)
    {
        parent::__construct($level, $nbt);

        if ($fireworks !== null && $fireworks->getNamedTagEntry("Fireworks") instanceof CompoundTag) {
            $this->propertyManager->setCompoundTag(self::DATA_FIREWORK_ITEM, $fireworks->getNamedTag());
            $this->setLifeTime($fireworks->getRandomizedFlightDuration());
        }

        $level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LAUNCH);
    }

    protected function tryChangeMovement(): void
    {
        $this->motion->x *= 1.15;
        $this->motion->y += 0.04;
        $this->motion->z *= 1.15;
    }

    /**
     * @param int $tickDiff
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->closed) {
            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);
        if ($this->doLifeTimeTick()) {
            $hasUpdate = true;
        }

        return $hasUpdate;
    }
    
    /**
     * @param int $life
     */
    public function setLifeTime(int $life): void
    {
        $this->lifeTime = $life;
    }
    
    /**
     * @return bool
     */
    protected function doLifeTimeTick(): bool
    {
        if (!$this->isFlaggedForDespawn() and --$this->lifeTime < 0) {
            $this->doExplosionAnimation();
            $this->flagForDespawn();
            return true;
        }

        return false;
    }

    protected function doExplosionAnimation(): void
    {
        $this->broadcastEntityEvent(ActorEventPacket::FIREWORK_PARTICLES);
    }
}