<?php

namespace Koralop\HCF\player\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\utils\commands\Command;
use Koralop\HCF\utils\Translate;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class EconomyCommand
 * @package Koralop\HCF\player\commands
 */
class EconomyCommand extends Command
{

    /**
     * EconomyCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('economy', HCFLoader::getInstance());

        $this->setAliases(['eco']);
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
            $sender->sendMessage(TextFormat::colorize('&6&lEconomy Help'));
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            $sender->sendMessage(TextFormat::colorize("&e/eco set <target> <balance> - Set a player's balance"));
            $sender->sendMessage(TextFormat::colorize("&e/eco give <target> <balance> - Give a player money"));
            $sender->sendMessage(TextFormat::colorize("&e/eco take <target> <balance> - Take money from a player"));
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            return;
        }

        switch ($args[0]) {
            case 'set':
                if (!$sender->hasPermission('ecoset.command.use'))
                    return;

                if (empty($args[1]) || empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: /eco set <target> <balance>');
                }

                $sender->sendMessage(Translate::getMessage("&aSet %player%''s balance to %amount%", ['player' => $args[1], 'amount' => $args[2]]));
                HCFLoader::getPlayerManager()->getPlayerData()->setBalance($args[1], $args[2]);
                break;
            case 'give':
                if (!$sender->hasPermission('ecogive.command.use'))
                    return;

                if (empty($args[1]) || empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: /eco give <target> <balance>');
                }

                $sender->sendMessage(Translate::getMessage("&aGiven %amount% to %target%", ['target' => $args[1], 'amount' => $args[2]]));
                HCFLoader::getPlayerManager()->getPlayerData()->setBalance($args[1], HCFLoader::getPlayerManager()->getPlayerData()->getBalance($args[1]) + $args[2]);
                break;
            case 'take':
                if (!$sender->hasPermission('ecotake.command.use'))
                    return;

                if (empty($args[1]) || empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: /eco take <target> <balance>');
                }

                $sender->sendMessage(Translate::getMessage("&aTook %amount% from %target%", ['target' => $args[1], 'amount' => $args[2]]));
                HCFLoader::getPlayerManager()->getPlayerData()->setBalance($args[1], HCFLoader::getPlayerManager()->getPlayerData()->getBalance($args[1]) - $args[2]);
                HCFLoader::getPlayerManager()->getPlayerData()->setBalance($sender->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getBalance($sender->getName()) + $args[2]);
                break;
        }
    }
}