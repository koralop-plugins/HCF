<?php

namespace Koralop\HCF\utils\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\command\CommandSender;

/**
 * Class SubCommand
 * @package Koralop\HCF\utils\commands
 */
abstract class SubCommand
{

    /** @var string */
    protected string $name;
    /** @var string */
    protected string $usage;
    /** @var string */
    protected string $description;
    /** @var array */
    protected array $aliases;
    /** @var string */
    protected string $label = '';


    /**
     * SubCommand constructor.
     * @param string $name
     * @param string $usage
     * @param string $description
     */
    public function __construct(string $name, string $usage = '', string $description = '', array $aliases = [])
    {
        $this->name = $name;
        $this->usage = $usage;
        $this->description = $description;

        $this->aliases = $aliases;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases
     */
    public function setAliases(array $aliases): void
    {
        $this->aliases = $aliases;
    }

    /**
     * @return HCFLoader
     */
    public function getPLugin(): HCFLoader
    {
        return HCFLoader::getInstance();
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    abstract function execute(HCFPlayer $player, array $args);

    /**
     * @param string $usage
     */
    public function setUsage(string $usage): void
    {
        $this->usage = $usage;
    }

    /**
     * @return string
     */
    public function getUsage(): string
    {
        return 'Usage: ' . $this->usage;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}