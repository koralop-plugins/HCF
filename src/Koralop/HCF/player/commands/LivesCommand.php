<?php

namespace Koralop\HCF\player\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class LivesCommand
 * @package Koralop\HCF\player\commands
 */
class LivesCommand extends Command
{

    /**
     * LivesCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('lives', HCFLoader::getInstance());
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
            $sender->sendMessage(TextFormat::colorize('&eYou currently have &c' . HCFLoader::getPlayerManager()->getPlayerData()->getLives($sender->getName()) . ' &elives'));
            $sender->sendMessage(TextFormat::colorize('&eVisit our store to purchase more: &cstore.vipermc.net'));
            return;
        }

        switch ($args[0]) {
            case 'set':
                if (!$sender->hasPermission('lives.give')) {
                    $sender->sendMessage(TextFormat::RED . 'No Permission.');
                    return;
                }

                if (empty($args[1]) or empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/lives set <target> <amount>');
                    return;
                }

                HCFLoader::getPlayerManager()->getPlayerData()->setLives($args[1], $args[2]);
                break;
            case 'add':
                if (!$sender->hasPermission('lives.give')) {
                    $sender->sendMessage(TextFormat::RED . 'No Permission.');
                    return;
                }

                if (empty($args[1]) or empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/lives add <target> <amount>');
                    return;
                }

                HCFLoader::getPlayerManager()->getPlayerData()->setLives($args[1], HCFLoader::getPlayerManager()->getPlayerData()->getLives($args[1]) + $args[2]);

                break;
            case 'take':
                if (empty($args[1]) or empty($args[2])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/lives take <target> <amount>');
                    return;
                }

                if (HCFLoader::getPlayerManager()->getPlayerData()->getLives($sender->getName()) < $args[2]) {
                    return;
                }

                $sender->sendMessage(TextFormat::colorize('&eYou have taken &6' . $args[2] . "&e's lives from &a" . $args[1] . "&e."));
                HCFLoader::getPlayerManager()->getPlayerData()->setLives($args[1], HCFLoader::getPlayerManager()->getPlayerData()->getLives($args[1]) + $args[2]);
                HCFLoader::getPlayerManager()->getPlayerData()->setLives($sender->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getLives($sender->getName()) - $args[2]);
                break;
            case 'help':
                $msg = [
                    "&7&m-----------------------------------------------------",
                    "&6&lLives &cHelp",
                    "&7&m-----------------------------------------------------",
                    "&e/lives set <target> <amount> - Set a player's lives",
                    "&e/lives add  <target> <amount> - Give a player lives",
                    "&e/lives take <target> <amount> - Take lives from a player",
                    "&7&m-----------------------------------------------------"
                ];

                foreach ($msg as $message) {
                    $sender->sendMessage(TextFormat::colorize($message));
                }
                break;
            case 'items':
                if (empty($args[1])) {
                    return;
                }

                $item = Item::get(ItemIds::PAPER, 0, $args[1]);
                $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . 'Lives');

                $nbt = $item->getNamedTag();
                $nbt->setString('lives', $args[1]);

                $item->setCompoundTag($nbt);

                $sender->getInventory()->addItem($item);
                break;
        }
    }
}