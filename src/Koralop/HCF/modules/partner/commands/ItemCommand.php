<?php

namespace Koralop\HCF\modules\partner\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/**
 * Class ItemCommand
 * @package Koralop\HCF\modules\partner\commands
 */
class ItemCommand extends Command
{

    /**
     * ItemCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('item', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof HCFPlayer)
            return;

        if (!$sender->isOp())
            return;


    }
}