<?php

namespace Koralop\HCF\modules\koth\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\koth\KothManager;
use Koralop\HCF\modules\koth\scheduler\KothTask;
use Koralop\HCF\utils\Claim;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\Position;
use pocketmine\plugin\Plugin;

/**
 * Class KothCommand
 * @package Koralop\HCF\modules\koth\commands
 */
class KothCommand extends PluginCommand
{

    /** @var KothManager */
    protected KothManager $manager;

    /**
     * KothCommand constructor.
     * @param KothManager $manager
     */
    public function __construct(KothManager $manager)
    {
        $this->manager = $manager;

        parent::__construct('koth', HCFLoader::getInstance());
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

            return;
        }

        switch ($args[0]) {
            case 'claim':

                if (!$sender->isOp())
                    return;

                $sender->addTool();
                $sender->setKothClaim(true);
                $sender->setClaimInteract(true);
                break;
            case 'create':

                if (!$sender->isOp())
                    return;


                if (empty($args[1])) {
                    return;
                }

                if ($this->manager->isKoth($args[1])) {
                    $sender->sendMessage(KothManager::PREFIX . '&eKoth &6%koth% &ealready exists.', true);
                    return;
                }

                if (Claim::isPosition($sender, 1) and Claim::isPosition($sender, 2)) {
                    $this->manager->addKoth([
                        'name' => $args[1],
                        'pos1' => new Position(Claim::getPosition($sender, 1)[0], Claim::getPosition($sender, 1)[1], Claim::getPosition($sender, 1)[2], $sender->getLevel()),
                        'pos2' => new Position(Claim::getPosition($sender, 2)[0], Claim::getPosition($sender, 2)[1], Claim::getPosition($sender, 2)[2], $sender->getLevel())
                    ]);

                    $sender->setKothClaim(false);

                    $sender->sendMessage(KothManager::PREFIX . '&eKoth &6' . $args[1] . ' &ehas been created.', true);
                }
                break;
            case 'start':

                if (!$sender->hasPermission('kothstart.command.use'))
                    return;

                if (empty($args[1])) {
                    return;
                }

                if (!$this->manager->isKoth($args[1])) {
                    $sender->sendMessage(KothManager::PREFIX . '&eKoth &6' . $args[1] . ' &edoes not exist.', true);
                    return;
                }

                $this->manager->setKothEnable($args[1]);

                $this->manager->getKoth($args[1])->setTime(300);

                $sender->sendMessage(KothManager::PREFIX . '&eKoth &6' . $args[1] . ' &ehas been started.', true);
                HCFLoader::getInstance()->getScheduler()->scheduleRepeatingTask(new KothTask($this->manager->getKoth($args[1]), 300), 20);
                break;
        }
    }
}