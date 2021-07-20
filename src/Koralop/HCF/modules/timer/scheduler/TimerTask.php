<?php

namespace Koralop\HCF\modules\timer\scheduler;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\timer\Timer;
use Koralop\HCF\modules\timer\TimerManager;
use pocketmine\scheduler\Task;

/**
 * Class TimerTask
 * @package Koralop\HCF\modules\timer\scheduler
 */
class TimerTask extends Task
{

    /** @var TimerManager */
    protected TimerManager $manager;

    /**
     * TimerTask constructor.
     * @param TimerManager $manager
     */
    public function __construct(TimerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        foreach ($this->manager->getAllTimer() as $timerName => $timer) {
            if ($timer instanceof Timer) {
                if ($timer->isEnable()) {
                    if ($timer->getCurrentTime() == 0) {
                        $timer->setEnable(false);
                    }
                    $timer->setCurrentTime($timer->getCurrentTime() - 1);
                }
            }
        }
    }
}