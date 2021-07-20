<?php

namespace Koralop\HCF\factions;

use Koralop\HCF\factions\commands\FactionCommand;
use Koralop\HCF\factions\commands\LFFCommand;
use Koralop\HCF\factions\commands\LocationCommand;
use Koralop\HCF\factions\events\FactionListener;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

/**
 * Class HCFactions
 * @package Koralop\HCF\factions
 */
class FactionManager
{

    /** @var Faction[] */
    protected array $factions = [];

    /** @var string */
    public const LEADER_FACTION = 'Leader';
    /** @var string */
    public const COLEADER_FACTION = 'Co-Leader';
    /** @var string */
    public const MEMBER_FACTION = 'Member';

    /**
     * HCFactions constructor.
     * @param HCFLoader $loader
     */
    public function __construct(HCFLoader $loader)
    {
        $loader->getServer()->getPluginManager()->registerEvents(new FactionListener(), $loader);

        foreach ($this->getAllFactions() as $faction) {
            $this->factions[$faction] = new Faction($faction);

            if ($this->getFaction($faction)->getDtr() < 100) {
                $this->getFaction($faction)->setDtr(count($this->getFaction($faction)->getPlayers()) * 1.1);
            }
        }

        $loader->getServer()->getCommandMap()->register('/f', new FactionCommand());
        $loader->getServer()->getCommandMap()->register('/tl', new LocationCommand());
        $loader->getServer()->getCommandMap()->register('/lff', new LFFCommand());
    }

    /**
     * @param string $factionName
     * @param HCFPlayer $player
     */
    public function addFaction(string $factionName, HCFPlayer $player): void
    {
        # Add faction to variable
        $this->factions[$factionName] = new Faction($factionName);

        # Add Player
        $this->getFaction($factionName)->addMember($player->getName(), self::LEADER_FACTION);

        # Other
        $this->getFaction($factionName)->setPoints(0);
        $this->getFaction($factionName)->setAnnounce('None');
        $this->getFaction($factionName)->setKoth(0);
        $this->getFaction($factionName)->setLives(0);
        $this->getFaction($factionName)->setBalance(200);
        $this->getFaction($factionName)->setDtr(1.1);
        $this->getFaction($factionName)->setAllys([]);
    }

    /**
     * @param string $factionName
     * @return Faction
     */
    public function getFaction(string $factionName): Faction
    {
        return $this->factions[$factionName];
    }

    /**
     * @param string $factionName
     */
    public function removeFaction(string $factionName): void
    {
        foreach ($this->getFaction($factionName)->getPlayers() as $player) {
            $this->getFaction($factionName)->removeMember($player);
        }

        unlink(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $factionName . '.yml');

        unset($this->factions[$factionName]);
    }

    /**
     * @param string $factionName
     * @return bool
     */
    public function isFaction(string $factionName): bool
    {
        return is_dir(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $factionName . '.yml');
    }

    /**
     * @return array
     */
    public function getAllFactions(): array
    {
        $factions = [];
        $files = glob(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '*.yml');

        foreach ($files as $file) {
            $a = explode('.', basename($file));
            $factions[] = $a[0];
        }
        return $factions;
    }

    /**
     * @return Faction[]
     */
    public function getFactions(): array
    {
        return $this->factions;
    }

    /**
     * @param Position $position
     * @return bool
     */
    public function isFactionRegion(Position $position): bool
    {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        foreach ($this->getFactions() as $faction) {
            if ($faction->getClaim() != null)
                if ($x >= $faction->getClaim()['x1'] && $x <= $faction->getClaim()['x2'] && $z >= $faction->getClaim()['z1'] && $z <= $faction->getClaim()['z2']) {
                    return true;
                }
        }
        return false;

    }

    /**
     * @param Vector3 $position
     * @return string
     */
    public function getFactionByPosition(Vector3 $position): string
    {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        foreach ($this->getFactions() as $faction) {
            if ($faction->getClaim() != null)
                if ($x >= $faction->getClaim()['x1'] && $x <= $faction->getClaim()['x2'] && $z >= $faction->getClaim()['z1'] && $z <= $faction->getClaim()['z2']) {
                    return $faction->getName();
                }
        }
        return ((new Vector3(0, 100, 0))->distance($position) < 500 ? 'Warzone' : 'The Wilderness');
    }

    /**
     * @param string $playerName
     * @return string|null
     */
    public function getFactionByPlayer(string $playerName): ?string
    {
        foreach ($this->getFactions() as $faction) {
            if (in_array($playerName, $faction->getPlayers()))
                return $faction->getName();
        }
        return null;
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function inFaction(string $playerName): bool
    {
        foreach ($this->getFactions() as $faction) {
            if (in_array($playerName, $faction->getPlayers()))
                return true;
        }
        return false;
    }

    /**
     * @param HCFPlayer $player
     * @param bool $bool
     */
    public function seeClaim(HCFPlayer $player, bool $bool): void
    {
        $blocks = [BlockIds::LAPIS_ORE, BlockIds::DIAMOND_BLOCK, BlockIds::GOLD_BLOCK, BlockIds::COAL_BLOCK, BlockIds::EMERALD_BLOCK];

        foreach ($this->getFactions() as $faction) {
            if ($faction->getClaim() != null) {
                $block = $bool == true ? $blocks[array_rand($blocks)] : BlockIds::AIR;
                $position1 = new Vector3($faction->getClaim()['x1'], $player->getFloorY(), $faction->getClaim()['z1']);
                $position2 = new Vector3($faction->getClaim()['x2'], $player->getFloorY(), $faction->getClaim()['z2']);
                $position3 = new Vector3($faction->getClaim()['x1'], $player->getFloorY(), $faction->getClaim()['z2']);
                $position4 = new Vector3($faction->getClaim()['x2'], $player->getFloorY(), $faction->getClaim()['z1']);
                for ($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++) {
                    $pk = new UpdateBlockPacket();
                    $pk->x = $position1->getFloorX();
                    $pk->y = $i;
                    $pk->z = $position1->getFloorZ();
                    $pk->flags = UpdateBlockPacket::FLAG_ALL;
                    $pk->blockRuntimeId = $block;
                    $player->dataPacket($pk);
                }
                for ($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++) {
                    $pk = new UpdateBlockPacket();
                    $pk->x = $position2->getFloorX();
                    $pk->y = $i;
                    $pk->z = $position2->getFloorZ();
                    $pk->flags = UpdateBlockPacket::FLAG_ALL;
                    $pk->blockRuntimeId = $block;
                    $player->dataPacket($pk);
                }
                for ($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++) {
                    $pk = new UpdateBlockPacket();
                    $pk->x = $position3->getFloorX();
                    $pk->y = $i;
                    $pk->z = $position3->getFloorZ();
                    $pk->flags = UpdateBlockPacket::FLAG_ALL;
                    $pk->blockRuntimeId = $block;
                    $player->dataPacket($pk);
                }
                for ($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++) {
                    $pk = new UpdateBlockPacket();
                    $pk->x = $position4->getFloorX();
                    $pk->y = $i;
                    $pk->z = $position4->getFloorZ();
                    $pk->flags = UpdateBlockPacket::FLAG_ALL;
                    $pk->blockRuntimeId = $block;
                    $player->dataPacket($pk);
                }

                $player->sendMessage('&b' . $faction->getName() . ' &eis shown with block &c' . Block::get($block)->getName() . '&e.');
            }
        }
    }

    /**
     * @param string $oldName
     * @param string $newName
     */
    public function rename(string $oldName, string $newName): void
    {
        unset($this->factions[$oldName]);

        $this->factions[$newName] = new Faction($newName);
    }
}