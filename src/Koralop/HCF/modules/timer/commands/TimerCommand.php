<?php

namespace Koralop\HCF\modules\timer\commands;

use Koralop\HCF\form\FormUtils;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\timer\TimerManager;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

/**
 * Class TimerCommand
 * @package Koralop\HCF\modules\timer\commands
 */
class TimerCommand extends Command
{

    /** @var TimerManager */
    protected TimerManager $manager;

    /**
     * TimerCommand constructor.
     */
    public function __construct(TimerManager $manager)
    {
        parent::__construct('timer', HCFLoader::getInstance());

        $this->manager = $manager;
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

        if (!$sender->isOp())
            return;

        if (empty($args[0]))
            return;


        switch ($args[0]) {
            case 'create':
                FormUtils::addTimer($sender);
                break;
            case 'start':
                if (empty($args[1])) {
                    return;
                }
                if (!$this->manager->isTimer($args[1])) {
                    return;
                }

                $this->manager->getTimer($args[1])->setCurrentTime($this->manager->getTimer($args[1])->getTime());
                $this->manager->getTimer($args[1])->setEnable(true);
                break;
            case 'list':
                $sender->sendMessage(TextFormat::RED . TextFormat::UNDERLINE . str_repeat('━', 30));
                $sender->sendMessage(TextFormat::GOLD . TextFormat::BOLD . 'Timer List:');
                $sender->sendMessage(TextFormat::RED . TextFormat::UNDERLINE . str_repeat('━', 30));
                foreach ($this->manager->getAllTimer() as $timerName => $timer) {
                    $sender->sendMessage(TextFormat::GOLD . ' - ' . $timerName);
                }
                $sender->sendMessage(TextFormat::RED . TextFormat::UNDERLINE . str_repeat('━', 30));
                break;
            case 'delete':
                if (empty($args[1])) {
                    return;
                }
                if (!$this->manager->isTimer($args[1])) {
                    return;
                }

                $this->manager->removeTimer($args[1]);
                break;

        }
    }
}