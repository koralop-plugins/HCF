<?php

namespace Koralop\HCF\player\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class BalanceCommand
 * @package Koralop\HCF\player\commands
 */
class BalanceCommand extends Command
{

    /**
     * BalanceCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('balance', HCFLoader::getInstance());
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
            $sender->sendMessage(TextFormat::colorize('&6Balance: &f' . HCFLoader::getPlayerManager()->getPlayerData()->getBalance($sender->getName())));
            return;
        }

        $sender->sendMessage(TextFormat::colorize("&6" . $args[0] . "''s Balance: &f" . HCFLoader::getPlayerManager()->getPlayerData()->getBalance($args[0])));
    }
}