<?php

namespace Koralop\HCF\modules\ce\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ce\Enchant;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\utils\TextFormat;

/**
 * Class EnchantCommand
 * @package Koralop\HCF\modules\ce\commands
 */
class EnchantCommand extends Command
{

    /**
     * EnchantCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('customenchat', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $manager = HCFLoader::getModulesManager()->getModuleById(ModulesIds::ENCHANT);
        if (!$sender instanceof HCFPlayer)
            return;

        if (!$sender->isOp())
            return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::colorize('&cUsage: /ce <enchant>'));
            return;
        }

        if ($sender->getInventory()->getItemInHand() instanceof Armor) {
            $item = $sender->getInventory()->getItemInHand();
            $item->addEnchantment(new EnchantmentInstance(Enchant::getEnchantment($manager->getEnchantments()[$args[0]]->getId()), 1));

            $item->setLore($item->getLore() + [$manager->getEnchantments()[$args[0]]->getCustomName()]);
            $sender->getInventory()->setItemInHand($item);
        }
    }
}