<?php

namespace Koralop\HCF\player\cooldowns;

use Koralop\HCF\HCFPlayer;

/**
 * Class PlayerCooldowns
 * @package Koralop\HCF\player\cooldowns
 */
class Cooldown
{

    /** @var string */
    protected string $player;

    /** @var int|null */
    protected ?int $enderPearl = null;
    /** @var int|null */
    protected ?int $combatTag = null;
    /** @var int|null */
    protected ?int $pvpTimer = null;
    /** @var int|null */
    protected ?int $gapple = null;
    /** @var int|null */
    protected ?int $apple = null;
    /** @var int|null */
    protected ?int $home = null;
    /** @var int|null */
    protected ?int $stuck = null;
    /** @var null|int */
    protected ?int $deathBan = null;
    /** @var int|null */
    protected ?int $logout = null;

    /** @var float */
    protected float $archerEnergy = 0;
    /** @var float */
    protected float $bardEnergy = 0;
    /** @var float */
    protected float $mageEnergy = 0;

    /** @var array */
    private array $effectCooldowns = [];
    /** @var array */
    protected array $kitCooldowns = [];
    /** @var int|null */
    protected ?int $archerMark = null;

    /**
     * PlayerCooldowns constructor.
     * @param string $player
     */
    public function __construct(string $player)
    {
        $this->player = $player;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->player;
    }

    /**
     * @return int|null
     */
    public function getEnderPearl()
    {
        return $this->enderPearl;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setEnderPearl($d)
    {
        return $this->enderPearl = $d;
    }

    /**
     * @return int|null
     */
    public function getCombatTag()
    {
        return $this->combatTag;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setCombatTag($d)
    {
        return $this->combatTag = $d;
    }

    /**
     * @return int|null
     */
    public function getPvPTimer()
    {
        return $this->pvpTimer;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setPvPTimer($d)
    {
        return $this->pvpTimer = $d;
    }

    /**
     * @return int|null
     */
    public function getArcherMark()
    {
        return $this->archerMark;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setArcherMark($d)
    {
        return $this->archerMark = $d;
    }

    /**
     * @return int|null
     */
    public function getAppleTime()
    {
        return $this->apple;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setAppleTime($d)
    {
        return $this->apple = $d;
    }

    /**
     * @return int|null
     */
    public function getGappleTime()
    {
        return $this->gapple;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setGappleTime($d)
    {
        return $this->gapple = $d;
    }

    /**
     * @return int|null
     */
    public function getHomeTime()
    {
        return $this->home;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setHomeTime($d)
    {
        return $this->home = $d;
    }

    /**
     * @return int|null
     */
    public function getStuckTime()
    {
        return $this->stuck;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setStuckTime($d)
    {
        return $this->stuck = $d;
    }

    /**
     * @return int|null
     */
    public function getMageEnergy()
    {
        return $this->mageEnergy;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setMageEnergy($d)
    {
        return $this->mageEnergy = $d;
    }

    /**
     * @return int|null
     */
    public function getArcherEnergy()
    {
        return $this->archerEnergy;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setArcherEnergy($d)
    {
        return $this->archerEnergy = $d;
    }

    /**
     * @return int|null
     */
    public function getBardEnergy()
    {
        return $this->bardEnergy;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setBardEnergy($d)
    {
        return $this->bardEnergy = $d;
    }

    /**
     * @return int|null
     */
    public function getDeathbanTime()
    {
        return $this->deathBan;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setDeathbanTime($d)
    {
        return $this->deathBan = $d;
    }

    /**
     * @return int|null
     */
    public function getLogoutTime()
    {
        return $this->logout;
    }

    /**
     * @param $d
     * @return mixed
     */
    public function setLogoutTime($d)
    {
        return $this->logout = $d;
    }

    /**
     * @param string $effectName
     * @return null
     */
    public function getEffectCooldown(string $effectName)
    {
        if (isset($this->effectCooldowns[$effectName])) {
            return $this->effectCooldowns[$effectName];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAllEffects(): array
    {
        return $this->effectCooldowns;
    }

    /**
     * @param string $effectName
     * @param int|null $cooldown
     */
    public function setEffectCooldown(string $effectName, ?int $cooldown = null): void
    {
        $this->effectCooldowns[$effectName] = $cooldown;
    }

    /**
     * @param string $kitName
     * @param int|null $cooldown
     */
    public function setKitCooldown(string $kitName, $cooldown): void
    {
        $this->kitCooldowns[$kitName] = $cooldown;
    }

    /**
     * @param string $kitName
     * @return int|null
     */
    public function getKitCooldown(string $kitName): ?int
    {
        return $this->kitCooldowns[$kitName];
    }
}