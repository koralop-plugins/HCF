<?php

namespace Koralop\HCF\factions\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\Command;
use Koralop\HCF\utils\Time;
use Koralop\HCF\utils\Translate;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

/**
 * Class LFFCommand
 * @package Koralop\HCF\factions\commands
 */
class LFFCommand extends Command
{

    /** @var array */
    protected array $cooldown = [];

    /**
     * LFFCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('lff', HCFLoader::getInstance());
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

        if ($sender->inFaction())
            return;

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] > time()) {
            $sender->sendMessage('&cYou are still on cooldown for ' . Time::secondAndMinutes($this->cooldown[$sender->getName()]) . '.');
            return;
        }

        Server::getInstance()->broadcastMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
        Server::getInstance()->broadcastMessage(Translate::getMessage('&6%player% &eis looking for a &6faction&e!', ['player' => $sender->getName()]));
        Server::getInstance()->broadcastMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));

        $this->cooldown[$sender->getName()] = HCFLoader::getYamlProvider()->getCooldowns()['lff'];
    }
}