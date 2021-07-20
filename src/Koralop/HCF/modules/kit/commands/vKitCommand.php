<?php

namespace Koralop\HCF\modules\kit\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFUtils;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

/**
 * Class vKitCommand
 * @package Koralop\HCF\modules\kit\commands
 */
class vKitCommand extends Command
{

    /**
     * vKitCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('vkit', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->isOp()) {
            $sender->sendMessage(TextFormat::colorize('&cNo Permission.'));
            return;
        }

        if (empty($args[0])) {
            return;
        }

        switch ($args[0]) {
            case 'give':
                if (empty($args[1]) || empty($args[2])) {
                    return;
                }

                if (!HCFUtils::isOnline($args[1]))
                    return;

                $item = Item::get(ItemIds::EMERALD, 0, 1);
                $nbt = $item->getNamedTag();
                $nbt->setString('vKit', $args[2]);
                $item->setCompoundTag($nbt);

                $item->setCustomName(TextFormat::GOLD . 'vKit Shard: ' . TextFormat::WHITE . $args[2]);
                $item->setLore(
                    [
                        TextFormat::WHITE . 'All ' . TextFormat::GOLD . 'vKit Shard ' . TextFormat::WHITE . 'come with the ',
                        TextFormat::WHITE . 'ability to unlock the vKit for ' . TextFormat::GREEN . '90 Days' . TextFormat::WHITE . '!',
                        ' ',
                        TextFormat::GRAY . 'IF YOU UNLOCK THIS KIT:',
                        TextFormat::WHITE . 'You will be able to redeem',
                        TextFormat::WHITE . 'your vKit using ' . TextFormat::GREEN . '/vKit' . TextFormat::WHITE . '!',
                        ' ',
                        TextFormat::GRAY . 'IF YOU DO NOT UNLOCK THIS KIT:',
                        TextFormat::WHITE . 'You will be given the contents',
                        'of the vKit one time.',
                        ' ',
                        TextFormat::GRAY . TextFormat::OBFUSCATED . 'Your chance to unlock the vKit will',
                        TextFormat::GRAY . TextFormat::OBFUSCATED . 'increase by 5% for each attempt.',
                        ' ',
                        TextFormat::YELLOW . 'Purchase at ' . TextFormat::LIGHT_PURPLE . 'store.test.net'
                    ]
                );

                HCFUtils::getPlayer($args[1])->getInventory()->addItem($item);
                break;
        }
    }
}