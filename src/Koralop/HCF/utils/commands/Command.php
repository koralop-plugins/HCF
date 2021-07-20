<?php

namespace Koralop\HCF\utils\commands;

use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\command\PluginCommand;

/**
 * Class Command
 * @package Koralop\HCF\utils
 */
abstract class Command extends PluginCommand
{

    /** @var array */
    protected array $subCommands;

    /**
     * @param SubCommand $subCommand
     */
    public function addSubCommand(SubCommand $subCommand): void
    {
        $this->subCommands[$subCommand->getName()] = $subCommand;
        foreach ($subCommand->getAliases() as $alias) {
            $this->subCommands[$alias] = $subCommand;
        }
    }

    /**
     * @param string $subCommandName
     * @return SubCommand|null
     */
    public function getSubCommand(string $subCommandName): ?SubCommand
    {
        if (isset($this->subCommands[$subCommandName])) {
            return $this->subCommands[$subCommandName];
        }
        return null;
    }

    /**
     * @param string $subCommandName
     * @return bool
     */
    public function isSubCommand(string $subCommandName): bool
    {
        return isset($this->subCommands[$subCommandName]);
    }


}