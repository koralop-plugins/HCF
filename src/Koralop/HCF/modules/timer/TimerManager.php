<?php

namespace Koralop\HCF\modules\timer;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\modules\timer\commands\TimerCommand;
use Koralop\HCF\modules\timer\scheduler\TimerTask;

use pocketmine\utils\TextFormat;

/**
 * Class TimerManager
 * @package Koralop\HCF\modules\timer
 */
class TimerManager extends Modules
{

    /** @var Timer[] */
    protected array $timer = [];

    /**
     * TimerManager constructor.
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getScheduler()->scheduleRepeatingTask(new TimerTask($this), 20);

        $loader->getServer()->getCommandMap()->register('/timer', new TimerCommand($this));

        $data = HCFLoader::getYamlProvider()->getTimerConfig();

        foreach ($data->getAll() as $timerName => $timer) {
            $this->addTimer(['name' => $timerName, 'time' => $timer['time'], 'format' => $timer['format']]);
        }

        HCFLoader::getInstance()->getLogger()->info(TextFormat::AQUA . 'Timer(s) ' . count($data->getAll()) . ' load');
    }

    public function addTimer(array $config): void
    {
        $this->timer[$config['name']] = new Timer($config);
    }

    public function isTimer(string $timerName): bool
    {
        return isset($this->timer[$timerName]);
    }

    public function getTimer(string $timerName): Timer
    {
        return $this->timer[$timerName];
    }

    public function onDisable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getTimerConfig();
        $timerData = [];

        foreach ($this->timer as $timerName => $timer) {
            $timerData[$timerName] = [
                'time' => $timer->getTime(),
                'format' => $timer->getFormat()
            ];
        }

        $data->setAll($timerData);
        $data->save();
    }

    /**
     * @return Timer[]
     */
    public function getAllTimer(): array
    {
        return $this->timer;
    }

    /**
     * @param string $timerName
     */
    public function removeTimer(string $timerName): void
    {
        unset($this->timer[$timerName]);
    }
}