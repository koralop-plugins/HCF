<?php

namespace Koralop\HCF\modules\pvp\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\Time;
use Koralop\HCF\utils\Translate;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class PvPCommand
 * @package Koralop\HCF\modules\pvp\commands
 */
class PvPCommand extends PluginCommand
{

    /**
     * PvPCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('pvp', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof HCFPlayer)
            return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 35));
            $sender->sendMessage(TextFormat::YELLOW . '/pvp time - Show time left on PvP Protection');
            $sender->sendMessage(TextFormat::YELLOW . '/pvp enable - Remove PvP Protection');
            $sender->sendMessage(TextFormat::YELLOW . '/pvp revive <target> - Revive a player with a friend life');
            $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 35));
            return;
        }

        switch ($args[0]) {
            case 'enable':
                if ($sender->getCooldowns()->getPvPTimer() != null) {
                    $sender->getCooldowns()->setPvPTimer(null);

                    $sender->sendMessage('&aYour PvP has been enabled.', true);
                } else
                    $sender->sendMessage('&cYou do not have an active PvP timer.', true);
                break;
            case 'time':
                if ($sender->getCooldowns()->getPvPTimer() != null) {
                    $sender->sendMessage(Translate::getMessage(
                        '',
                        [

                        ]
                    ));
                }
                break;
        }
    }
}