<?php

namespace Koralop\HCF\modules\koth\scheduler;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\koth\Koth;
use Koralop\HCF\modules\koth\KothManager;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\utils\Translate;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

/**
 * Class KothTask
 * @package Koralop\HCF\modules\koth\scheduler
 */
class KothTask extends Task
{

    /** @var Koth */
    protected Koth $koth;

    /** @var int */
    protected int $time = 0;

    /**
     * KothTask constructor.
     * @param Koth $koth
     */
    public function __construct(Koth $koth)
    {
        $this->koth = $koth;
    }

    public function onRun(int $currentTick)
    {
        if (HCFLoader::getModulesManager()->getModuleById(ModulesIds::KOTH)->getKothEnable() != null) {
            if ($this->koth->getCapturer() == null) {
                foreach (HCFLoader::getInstance()->getServer()->getOnlinePlayers() as $player) {
                    if ($player instanceof HCFPlayer) {
                        if ($this->koth->isInPosition($player)) {
                            if ($player->inFaction()) {
                            $this->koth->setCapturer($player);

                            Server::getInstance()->broadcastMessage(Translate::getMessage(
                                KothManager::PREFIX.'&d%faction% &6has started to control &9%name%&6.',
                            [
                                'faction' => $player->getFactionName(),
                                'zone' => $this->koth->getName()
                            ]));

                            $player->getFaction()->sendMessage(Translate::getMessage(
                                KothManager::PREFIX.'&6Your team is now controlling &9%name%&e.',
                            [
                                'name' => $this->koth->getName()
                            ]));

                            return;
                        }
                        }
                    }
                }
                return;
            }

            if (!$this->koth->isInPosition($this->koth->getCapturer())) {
                $this->koth->setCapturer(null);

                $this->koth->setTime($this->time);
                return;
            }

            if ($this->time == 60) {

                $this->time = $this->time - 60;

                $this->koth->setTime($this->time);

                Server::getInstance()->broadcastMessage(Translate::getMessage(
                    KothManager::PREFIX.'&9%name% &eis now at &6%time%&e.',
                    [
                        'name' => $this->koth->getName(),
                        'time' => $this->koth->getTime()
                    ]
                ));

            }

            if ($this->koth->getTime() == 0) {
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KOTH)->setKothEnable(null);
                
                HCFLoader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
                return;
            }

            $this->koth->setTime($this->koth->getTime() - 1);

            $this->time++;
        } else {
            HCFLoader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}