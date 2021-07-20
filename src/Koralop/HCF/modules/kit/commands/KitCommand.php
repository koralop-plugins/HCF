<?php


namespace Koralop\HCF\modules\kit\commands;

use Koralop\HCF\form\FormUtils;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ModulesIds;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use Koralop\HCF\form\utils\SimpleForm;

/**
 * Class KitCommand
 * @package Koralop\HCF\modules\kit\commands
 */
class KitCommand extends PluginCommand
{

    /**
     * KitCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('kit', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $kit = HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT);

        if (!$sender instanceof HCFPlayer)
            return;

        if (empty($args[0])) {
            $form = new SimpleForm(function (HCFPlayer $player, $data = null) {
                if ($data == null)
                    return;
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getKit($data)->setKit($player);
            });

            $form->setTitle(TextFormat::GOLD . 'Kit Selector');
            foreach (HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT) as $kitName => $iKit) {
                $form->addButton($iKit->getFormat() . TextFormat::EOL . TextFormat::RESET . TextFormat::GRAY . 'Press to get.', -1, '', $kitName);
            }

            $sender->sendForm($form);
            return;

        }

        if ($kit->isKit($args[0])) {
            $kit->getKit($args[0])->setKit($sender);
            return;
        }

        if (!$sender->isOp())
            return;

        switch ($args[0]) {
            case 'create':
                FormUtils::getCreateKitForm($sender);
                break;
            case 'delete':
                if (empty($args[1])) {
                    $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/kit delete <kitName>');
                    return;
                }

                if (!$kit->isKit($args[1])) {
                    $sender->sendMessage(TextFormat::RED . 'There is no kit with that name!');
                    return;
                }

                $kit->removeKit($args[0]);
                $sender->sendMessage('&cKit delete successfully', true);
                break;
            case 'list':
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                $sender->sendMessage(TextFormat::BOLD . TextFormat::GOLD . 'Kit List');
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                foreach ($kit->getAllKits() as $kitName => $kit) {
                    $sender->sendMessage(TextFormat::GRAY . ' - ' . TextFormat::GOLD . $kitName);
                }
                $sender->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
                break;
        }
    }
}