<?php

namespace Koralop\HCF\modules\pvp\commands;

use Koralop\HCF\form\FormUtils;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

/**
 * Class DeathbanCommand
 * @package Koralop\HCF\modules\pvp\commands
 */
class DeathbanCommand extends PluginCommand
{

    /**
     * DeathbanCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('deathban', HCFLoader::getInstance());
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof HCFPlayer)
            return;

        if (!$sender->isOp())
            return;

        if (empty($args[0])) {

            return;
        }

        switch ($args[0]) {
            case 'set':
                $c = HCFLoader::getYamlProvider()->getConfig();
                $c->set('deathban', [
                    'spawn' => [
                        $sender->getFloorX(),
                        $sender->getFloorY(),
                        $sender->getFloorZ(),
                        $sender->getLevel()->getFolderName()
                    ]
                ]);
                $c->save();
                break;
            case 'kit':
                FormUtils::getCreateKitForm($sender);
                break;
        }
    }
}