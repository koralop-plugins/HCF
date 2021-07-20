<?php

namespace Koralop\HCF\player\data;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\utils\Translate;
use pocketmine\Server;
use pocketmine\utils\Config;

/**
 * Class PlayerData
 * @package Koralop\HCF\player
 */
class PlayerData
{

    /**
     * @param string $playerName
     * @return int|null
     */
    public function getDeaths(string $playerName): ?int
    {
        return HCFLoader::getYamlProvider()->getDeathsConfig()->get($playerName);
    }

    /**
     * @param string $playerName
     * @param int $deaths
     */
    public function setDeaths(string $playerName, int $deaths): void
    {
        $data = HCFLoader::getYamlProvider()->getDeathsConfig();
        $data->set($playerName, $deaths);
        $data->save();
    }

    /**
     * @param string $playerName
     * @return int|null
     */
    public function getKills(string $playerName): ?int
    {
        return HCFLoader::getYamlProvider()->getKillsConfig()->get($playerName);
    }

    /**
     * @param string $playerName
     * @param int $kills
     */
    public function setKills(string $playerName, int $kills): void
    {
        $data = HCFLoader::getYamlProvider()->getKillsConfig();
        $data->set($playerName, $kills);
        $data->save();
    }

    /**
     * @return int
     */
    public function getLives(string $playerName): ?int
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        return $data->get('lives');
    }

    /**
     * @param string $playerName
     * @param int $lives
     */
    public function setLives(string $playerName, int $lives)
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        $data->set('lives', $lives);
        $data->save();
    }

    /**
     * @param string $playerName
     */
    public function createPlayerData(string $playerName)
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML, [
            'lives' => 30,
            'balance' => 200,
            'deathban' => false,
            'wayPoints' => []
        ]);
        $data = HCFLoader::getYamlProvider()->getKillsConfig();
        if (!$data->exists($playerName)) {
            $this->setKills($playerName, 0);
        }
        $data = HCFLoader::getYamlProvider()->getDeathsConfig();
        if (!$data->exists($playerName)) {
            $this->setDeaths($playerName, 0);
        }
    }

    /**
     * @return int
     */
    public function getBalance(string $playerName): ?int
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        return $data->get('balance');
    }

    /**
     * @param string $playerName
     * @param int $balance
     */
    public function setBalance(string $playerName, int $balance)
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        $data->set('balance', $balance);
        $data->save();
    }

    /**
     * @param string $playerName
     * @param $a
     * @param $b
     */
    public function setData(string $playerName, $a, $b): void
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        $data->set($a, $b);
        $data->save();
    }

    /**
     * @param string $playerName
     * @param $a
     * @return bool|mixed
     */
    public function getData(string $playerName, $a)
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        return $data->get($a);
    }

    /**
     * @param string $playerName
     * @return float
     */
    public function getKDR(string $playerName): float
    {
        $kills = self::getKills($playerName);
        $deaths = self::getDeaths($playerName);

        if ($kills == 0 | $deaths == 0)
            return 0.0;

        $kdr = $kills / $deaths;

        if ($kdr == 0)
            return 0.0;

        return number_format($kdr, 2);
    }

    /**
     * @param string $playerName
     * @return array|bool
     */
    public function getWayPoints(string $playerName): array
    {
        return $this->getData($playerName, 'wayPoints');
    }

    public function setWayPoints(string $playerName, array $wayPoints): void
    {

        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);

        $way = null;

        $way = $data->getAll();

        $way['wayPoints'][$wayPoints['name']] = $wayPoints;

        $data->setAll($way);
        $data->save();
    }

    /**
     * @param string $playerName
     * @param string $wayPointName
     */
    public function removeWayPoint(string $playerName, string $wayPointName): void
    {
        $data = new Config(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . $playerName . '.yml', Config::YAML);
        $data->remove(['wayPoints'][$wayPointName]);
        $data->save();
    }

    /**
     * @param string $playerName
     */
    public function checkTop(string $playerName): void
    {
        if (($this->getKills($playerName) + 1) > $this->getKills($this->getTop1())) {
            Server::getInstance()->broadcastMessage(Translate::getMessage(
                '&6%player% &fhas surpassed %top% &ffor &6#1 &fKills!',
                [
                    'player' => $playerName,
                    'top' => $this->getTop1()
                ]));
        }

        if (($this->getKills($playerName) + 1) > $this->getKills($this->getTop2())) {
            Server::getInstance()->broadcastMessage(Translate::getMessage(
                '&6%player% &fhas surpassed %top% &ffor &6#2 &fKills!',
                [
                    'player' => $playerName,
                    'top' => $this->getTop2()
                ]));
        }

        if (($this->getKills($playerName) + 1) > $this->getKills($this->getTop3())) {
            Server::getInstance()->broadcastMessage(Translate::getMessage(
                '&6%player% &fhas surpassed %top% &ffor &6#3 &fKills!',
                [
                    'player' => $playerName,
                    'top' => $this->getTop3()
                ]));
        }
    }

    /**
     * @return string
     */
    private function getTop1(): string
    {
        $kills = HCFLoader::getYamlProvider()->getKillsConfig()->getAll();
        $data = [];

        foreach ($kills as $playerName => $kill) {
            $data[] = $playerName;
        }
        arsort($data);
        return $data[0];
    }

    /**
     * @return string
     */
    private function getTop2(): string
    {
        $kills = HCFLoader::getYamlProvider()->getKillsConfig()->getAll();
        $data = [];

        foreach ($kills as $playerName => $kill) {
            $data[] = $playerName;
        }
        arsort($data);
        return $data[1];
    }

    /**
     * @return string
     */
    private function getTop3(): string
    {
        $kills = HCFLoader::getYamlProvider()->getKillsConfig()->getAll();
        $data = [];

        foreach ($kills as $playerName => $kill) {
            $data[] = $playerName;
        }
        arsort($data);
        return $data[2];
    }
}