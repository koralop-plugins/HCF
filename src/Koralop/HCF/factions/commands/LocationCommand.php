<?php

namespace Koralop\HCF\factions\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class LocationCommand
 * @package Koralop\HCF\factions\commands
 */
class LocationCommand extends PluginCommand
{

    /**
     * LocationCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('tl', HCFLoader::getInstance());
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

        if ($sender->inFaction()) {
            if ($sender->getFaction()->getLeader() == $sender->getName()) {
                $sender->getFaction()->sendMessage(TextFormat::GOLD . '[Leader] ' . TextFormat::BLUE . '(Team) ' . $sender->getName() . ': ' . TextFormat::YELLOW . '[' . $sender->getFloorX() . ', ' . $sender->getFloorY() . ', ' . $sender->getFloorZ() . ', World: ' . $sender->getLevel()->getFolderName() . ']');
                return;
            }

            if ($sender->getFaction()->isCoLeader($sender->getName())) {
                $sender->getFaction()->sendMessage(TextFormat::GOLD . '[Co-Leader] ' . TextFormat::BLUE . '(Team) ' . $sender->getName() . ': ' . TextFormat::YELLOW . '[' . $sender->getFloorX() . ', ' . $sender->getFloorY() . ', ' . $sender->getFloorZ() . ', World: ' . $sender->getLevel()->getFolderName() . ']');

                return;
            }

            $sender->getFaction()->sendMessage(TextFormat::BLUE . '(Team) ' . $sender->getName() . ': ' . TextFormat::YELLOW . '[' . $sender->getFloorX() . ', ' . $sender->getFloorY() . ', ' . $sender->getFloorZ() . ', World: ' . $sender->getLevel()->getFolderName() . ']');

        } else {
            $sender->sendMessage(TextFormat::RED . 'You are not in any faction!');
        }
    }
}