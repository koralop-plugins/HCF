<?php

namespace Koralop\HCF\factions;

use Koralop\HCF\factions\scheduler\FreezeTask;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\HCFUtils;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

/**
 * Class Faction
 * @package Koralop\HCF\factions
 */
class Faction
{

    /** @var string */
    protected string $factionName;
    /** @var int|null */
    protected ?int $freezeTime = null;
    /** @var array|null */
    protected ?array $inviteAlly = [];

    /** @var Config */
    public Config $config;

    /**
     * Faction constructor.
     * @param string $factionName
     */
    public function __construct(string $factionName)
    {
        $this->factionName = $factionName;

        $this->config = new Config(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $factionName . '.yml', Config::YAML);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->factionName;
    }


    /**
     * @param string $playerName
     * @param string $rank
     */
    public function addMember(string $playerName, string $rank = FactionManager::MEMBER_FACTION): void
    {
        $config = $this->config;

        $data[$playerName] = ['rank' => $rank];

        $config->set('players', $data);

        $this->sendMessage(TextFormat::YELLOW . $playerName . ' has joined the team!');
    }

    /**
     * @param string $playerName
     */
    public function removeMember(string $playerName): void
    {
        $config = $this->config;
        $players = [];

        foreach ($config->get('players') as $player => $data) {
            if ($player != $playerName)
                $player[$player] = ['rank' => $data['rank']];
        }
        $config->set('players', $players);
        $config->save();
    }

    /**
     * @return array|null
     */
    public function getPlayers(): ?array
    {
        $p = [];

        foreach ($this->config->get('players') as $player => $data) {
            $p[] = $player;
        }
        return $p;

    }

    /**
     * @param float $dtr
     */
    public function setDtr(float $dtr): void
    {
        if (($this->getDtr() + $dtr) < $this->getDtr()) {
            if ($this->getFreezeTime() == null) {
                HCFLoader::getInstance()->getScheduler()->scheduleRepeatingTask(new FreezeTask($this->factionName), 20);
            }
        }

        $this->config->set('dtr', $dtr);
        $this->config->save();
    }

    /**
     * @return float|null
     */
    public function getDtr(): ?float
    {
        return $this->config->exists('dtr') ? $this->config->get('dtr') : null;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $config = $this->config;
        $config->set('points', $points);
        $config->save();
    }

    /**
     * @return int|null
     */
    public function getPoints(): ?int
    {
        return $this->config->exists('points') ? $this->config->get('poitns') : null;
    }

    /**
     * @return int|null
     */
    public function getFreezeTime(): ?int
    {
        return $this->freezeTime;
    }

    /**
     * @param int $freezeTime
     * @return int
     */
    public function setFreezeTime(?int $freezeTime)
    {
        return $this->freezeTime = $freezeTime;
    }

    /**
     * @param String $factionName
     * @return Int|null
     */
    public function getBalance(): ?int
    {
        return $this->config->exists('balance') ? $this->config->get('balance') : null;
    }

    /**
     * @param String $factionName
     * @param Int $balance
     * @return void
     */
    public function addBalance(int $balance): void
    {
        $this->setBalance($this->getBalance() + $balance);
    }

    /**
     * @param String $factionName
     * @param Int $balance
     * @return void
     */
    public function reduceBalance(int $balance): void
    {
        $this->setBalance($this->getBalance() - $balance);
    }

    /**
     * @param int $balance
     */
    public function setBalance(int $balance): void
    {
        $config = $this->config;
        $config->set('balance', $balance);
        $config->save();
    }

    /**
     * @param string|null $level
     * @param array $position1
     * @param array $position2
     */
    public function claimRegion(?string $level, Vector3 $position1, Vector3 $position2): void
    {
        $xMin = min($position1->getX(), $position2->getX());
        $xMax = max($position1->getX(), $position2->getX());

        $zMin = min($position1->getZ(), $position2->getZ());
        $zMax = max($position1->getZ(), $position2->getZ());

        $config = $this->config;

        $config->set('claim',
            [
                'x1' => $xMin,
                'z1' => $zMin,
                'x2' => $xMax,
                'z2' => $zMax,
                'level' => $level
            ]);
        $config->save();
    }

    /**
     * @return string|null
     */
    public function getLeader(): ?string
    {
        $config = $this->config;

        foreach ($this->getPlayers() as $player) {
            if ($config->get('players')[$player]['rank'] == FactionManager::LEADER_FACTION)
                return $player;
        }
        return null;
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function isCoLeader(string $playerName): bool
    {
        $config = $this->config;
        if ($config->get('players')[$playerName]['rank'] == FactionManager::COLEADER_FACTION)
            return true;

        return false;
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function isLeader(string $playerName): bool
    {
        $config = $this->config;
        if ($config->get('players')[$playerName]['rank'] == FactionManager::LEADER_FACTION)
            return true;

        return false;
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message): void
    {
        foreach ($this->getPlayers() as $fPlayer) {
            $player = HCFLoader::getInstance()->getServer()->getPlayer($fPlayer);
            if ($player instanceof HCFPlayer) {
                $player->sendMessage(str_replace(['&'], ['§'], $message));
            }
        }
    }

    /**
     * @param array $position
     */
    public function setHome(array $position): void
    {
        $config = $this->config;
        $config->set('home', [
            'x' => $position[0],
            'y' => $position[1],
            'z' => $position[2],
            'level' => $position[3]
        ]);
        $config->save();
    }

    /**
     * @return array|null
     */
    public function getHome(): ?array
    {
        return $this->config->exists('home') ? $this->config->get('home') : null;
    }

    /**
     * @return int
     */
    public function getCountOnlinePLayers(): int
    {
        $data = [];
        foreach ($this->getPlayers() as $player) {
            if (HCFUtils::isOnline($player)) {
                $data[] = $player;
            }
        }
        return count($data);
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function inFaction(string $playerName): bool
    {
        $config = $this->config->getAll();
        return $config['players'][$playerName] == null ? false : true;
    }

    /**
     * @return HCFPlayer[]
     */
    public function getOnlinePlayers(): array
    {
        $players = [];

        foreach ($this->getPlayers() as $player) {
            if (HCFUtils::isOnline($player))
                $players[] = HCFUtils::getPlayer($player);
        }
        return $players;
    }

    /**
     * @return array|null
     */
    public function getClaim(): ?array
    {
        return $this->config->exists('claim') ? $this->config->get('claim') : null;
    }

    /**
     * @param string $announce
     */
    public function setAnnounce(string $announce): void
    {
        $data = $this->config;
        $data->set('announce', $announce);
        $data->save();
    }

    /**
     * @return string|null
     */
    public function getAnnounce(): ?string
    {
        return $this->config->exists('announce') ? $this->config->get('announce') : null;
    }

    /**
     * @param int $lives
     */
    public function setLives(int $lives): void
    {
        $data = $this->config;
        $data->set('lives', $lives);
        $data->save();
    }

    /**
     * @return int|null
     */
    public function getLives(): ?int
    {
        return $this->config->exists('lives') ? $this->config->get('lives') : null;
    }

    /**
     * @param int $koth
     */
    public function setKoth(int $koth): void
    {
        $data = $this->config;
        $data->set('koth', $koth);
        $data->save();
    }

    /**
     * @return int|null
     */
    public function getKoth(): ?int
    {
        return $this->config->exists('koth') ? $this->config->get('koth') : null;
    }

    public function removeClaim(): void
    {
        $data = $this->config;
        $data->remove('claim');
        $data->save();
    }

    /**
     * @param array $allys
     */
    public function setAllys(array $allys): void
    {
        $data = $this->config;
        $data->set('allys', $allys);
        $data->save();
    }

    /**
     * @return int|null
     */
    public function getAllys(): ?array
    {
        return $this->config->exists('allys') ? $this->config->get('allys') : null;
    }

    /**
     * @param string $factionName
     */
    public function addInviteAlly(string $factionName): void
    {
        $this->inviteAlly[$factionName] = $factionName;
    }

    /**
     * @param string $factionName
     */
    public function deleteInviteAlly(string $factionName): void
    {
        unset($this->inviteAlly[$factionName]);
    }

    /**
     * @param string $factionName
     * @return bool
     */
    public function isInviteAlly(string $factionName): bool
    {
        return isset($this->inviteAlly[$factionName]);
    }

    /**
     * @return array
     */
    public function getInviteAlly(): array
    {
        return $this->inviteAlly;
    }
}