<?php

namespace Koralop\HCF\modules\timer;

/**
 * Class Timer
 * @package Koralop\HCF\modules\timer
 */
class Timer
{

    /** @var int|null */
    protected ?int $time = null;

    /** @var int */
    protected int $currentTime = 0;

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $format;

    /** @var bool */
    protected bool $enable = false;

    /**
     * Timer constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->time = $config['time'];
        $this->name = $config['name'];
        $this->format = $config['format'];
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return int
     */
    public function getCurrentTime(): int
    {
        return $this->currentTime;
    }

    /**
     * @param int $currentTime
     */
    public function setCurrentTime(int $currentTime): void
    {
        $this->currentTime = $currentTime;
    }
}