<?php

namespace Koralop\HCF\factions\scheduler;

use Koralop\HCF\HCFLoader;
use pocketmine\scheduler\Task;

class FreezeTask extends Task
{

    /** @var string */
    protected string $factionName;

    /**
     * FreezeTask constructor.
     * @param string $factionName
     */
    public function __construct(string $factionName)
    {
        $this->factionName = $factionName;

        HCFLoader::getFactionManager()->getFaction($factionName)->setFreezeTime(1800);
    }

    public function onRun(int $currentTick)
    {
        $faction = HCFLoader::getFactionManager()->getFaction($this->factionName);

        if ($faction->getFreezeTime() == 0) {

            $faction->setDtr(count($faction->getPlayers()) * 1.1);

            $faction->setFreezeTime(null);

            $faction->sendMessage('&e&lYour faction is now regenerating DTR.');
            HCFLoader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        $faction->setFreezeTime($faction->getFreezeTime() - 1);
    }
}