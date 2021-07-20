<?php

namespace Koralop\HCF\player\commands;

use Koralop\HCF\HCFLoader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

/**
 * Class LeaderboardsCommand
 * @package Koralop\HCF\player\commands
 */
class LeaderboardsCommand extends PluginCommand
{

    /**
     * LeaderboardsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('leaderboards', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (empty($args[0])) {
            $data = (new Config(HCFLoader::getInstance()->getDataFolder() . 'kills.yml', Config::YAML))->getAll();
            arsort($data);
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            $sender->sendMessage(TextFormat::BOLD . TextFormat::YELLOW . 'Leaderboards for' . TextFormat::RESET . TextFormat::YELLOW . ': ' . TextFormat::RED . 'Kills');
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            $i = 1;
            foreach ($data as $playerName => $kills) {
                $sender->sendMessage(TextFormat::RED . ($i == 1 ? TextFormat::RED . $i : TextFormat::YELLOW . $i) . TextFormat::YELLOW . ' ' . $playerName . ': ' . TextFormat::RED . $kills);
                if ($i > 9) {
                    break;
                }
                $i++;
            }
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
            return;
        }

        switch ($args[0]) {
            case 'kills':
                $data = (new Config(HCFLoader::getInstance()->getDataFolder() . 'kills.yml', Config::YAML))->getAll();
                arsort($data);
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                $sender->sendMessage(TextFormat::BOLD . TextFormat::YELLOW . 'Leaderboards for' . TextFormat::RESET . TextFormat::YELLOW . ': ' . TextFormat::RED . 'Kills');
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                $i = 1;
                foreach ($data as $playerName => $kills) {
                    $sender->sendMessage(TextFormat::RED . ($i == 1 ? TextFormat::RED . $i : TextFormat::YELLOW . $i) . TextFormat::YELLOW . ' ' . $playerName . ': ' . TextFormat::RED . $kills);
                    if ($i > 9) {
                        break;
                    }
                    $i++;
                }
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                break;
            case 'kd':
                $kd = [];
                foreach ((new Config(HCFLoader::getInstance()->getDataFolder().'kills.yml', Config::YAML))->getAll() as $playerName => $kills) {
                    $kd[$playerName] = HCFLoader::getPlayerManager()->getPlayerData()->getKDR($playerName);
                }

                arsort($kd);
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                $sender->sendMessage(TextFormat::BOLD . TextFormat::YELLOW . 'Leaderboards for' . TextFormat::RESET . TextFormat::YELLOW . ': ' . TextFormat::RED . 'KD');
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                $i = 1;
                foreach ($kd as $playerName => $kdr) {
                    $sender->sendMessage(TextFormat::RED . ($i == 1 ? TextFormat::RED . $i : TextFormat::YELLOW . $i) . TextFormat::YELLOW . ' ' . $playerName . ': ' . TextFormat::RED . (float)$kdr);
                    if ($i > 9) {
                        break;
                    }
                    $i++;
                }
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                break;
        }
    }
}