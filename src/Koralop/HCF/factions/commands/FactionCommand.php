<?php

namespace Koralop\HCF\factions\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\Command;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\command\CommandSender;

/**
 * Class FactionCommand
 * @package Koralop\HCF\factions\commands
 */
class FactionCommand extends Command
{

    /** @var array */
    protected array $commands = [];

    /**
     * FactionCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('f', HCFLoader::getInstance());
        $this->setAliases(['team', 't']);

        $files = glob(__DIR__ . DIRECTORY_SEPARATOR . 'subcommands' . DIRECTORY_SEPARATOR . '*.php');

        foreach ($files as $file) {

            require($file);

            $dir = str_replace('.php', '', $file);
            $class = new $dir();

            if ($class instanceof SubCommand)
                $this->addSubCommand(new $class());
        }
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

        if (!$sender instanceof HCFPlayer)
            return;

        if (empty($args[0])) {
            $this->getSubCommand('help')->execute($sender, $args);
            return;
        }

        if ($this->isSubCommand($args[0])) {
            $this->getSubCommand($args[0])->execute($sender, $args);
        } else {
            $this->getSubCommand('help')->execute($sender, $args);
        }
    }
}