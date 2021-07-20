<?php

namespace Koralop\HCF\player\commands;

use Koralop\HCF\HCFLoader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

use pocketmine\utils\TextFormat;

/**
 * Class StatsCommand
 * @package Koralop\HCF\player\commands
 */
class StatsCommand extends PluginCommand
{

    /**
     * StatsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('stats', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            $sender->sendMessage(TextFormat::BOLD . TextFormat::GOLD . $sender->getName());
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            $sender->sendMessage(TextFormat::YELLOW . 'Kills: ' . TextFormat::RED . HCFLoader::getPlayerManager()->getPlayerData()->getKills($sender->getName()));
            $sender->sendMessage(TextFormat::YELLOW . 'Deaths: ' . TextFormat::RED . HCFLoader::getPlayerManager()->getPlayerData()->getDeaths($sender->getName()));
            $sender->sendMessage(TextFormat::YELLOW . 'KD: ' . TextFormat::RED . HCFLoader::getPlayerManager()->getPlayerData()->getKDR($sender->getName()));
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            return;
        }

        if (!$sender->isOp())
            return;

        switch ($args[0]) {
            case 'reset':
                if (empty($args[1])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/stats reset <playerName>');
                    return;
                }

                $data = HCFLoader::getPlayerManager()->getPlayerData();
                $data->setDeaths($args[1], 0);
                $data->setKills($args[1], 0);

                $sender->sendMessage(TextFormat::GOLD . $args[1] . "'s" . TextFormat::GREEN . ' stats have been reset');
                break;
        }
    }
}