<?php

namespace Koralop\HCF\player\cooldowns\scheduler;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;

use Koralop\HCF\HCFUtils;
use Koralop\HCF\modules\kit\KitIds;
use Koralop\HCF\player\cooldowns\Cooldown;
use Koralop\HCF\player\cooldowns\CooldownManager;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\scheduler\Task;

use pocketmine\utils\TextFormat;

/**
 * Class CooldownsTask
 * @package Koralop\HCF\player\cooldowns\scheduler
 */
class CooldownsTask extends Task
{

    /** @var CooldownManager */
    protected CooldownManager $cooldownsManager;

    /**
     * CooldownsTask constructor.
     * @param CooldownManager $cooldownsManager
     */
    public function __construct(CooldownManager $cooldownsManager)
    {
        $this->cooldownsManager = $cooldownsManager;
    }

    public function onRun(int $currentTick)
    {
        foreach ($this->cooldownsManager->getCooldowns() as $playerName => $cooldown) {
            if (HCFUtils::isOnline($playerName)) {
                if (HCFUtils::getPlayer($playerName)->isEnter()) {

                    /** @var HCFPlayer */
                    $player = HCFUtils::getPlayer($playerName);

                    $this->enderpearl($player);
                    $this->combattag($player);
                    $this->pvpTimer($player);
                    $this->gapple($player);
                    $this->stuck($player);
                    $this->apple($player);
                    $this->home($player);
                    $this->logout($player);
                    $this->classLoad($player);
                }
            } else
                $this->deathBan($cooldown);
        }
    }


    public function enderpearl(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getEnderPearl() != null) {
            if ($player->getCooldowns()->getEnderPearl() == 0) {
                $player->getCooldowns()->setEnderPearl(null);
                $player->sendMessage(TextFormat::RED . 'Your enderpearl cooldown has expired');
                return;
            }
            $player->getCooldowns()->setEnderPearl($player->getCooldowns()->getEnderPearl() - 1);
        }
    }

    public function combattag(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getCombatTag() != null) {
            if ($player->getCooldowns()->getCombatTag() == 0) {

                $player->sendMessage('&eYou cannot enter end whilst having a combat timer.', true);

                $player->getCooldowns()->setCombatTag(null);
                return;
            }
            $player->getCooldowns()->setCombatTag($player->getCooldowns()->getCombatTag() - 1);
        }
    }

    public function pvpTimer(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getPvPTimer() != null) {
            if ($player->getCooldowns()->getPvPTimer() == 0) {
                $player->getCooldowns()->setPvPTimer(null);
                $player->sendMessage(TextFormat::RED . 'Your pvptimer has expired!');
                return;
            }

            if (HCFLoader::getFactionManager()->isFaction($player->getCurrentClaim())) {
                $faction = HCFLoader::getFactionManager()->getFaction($player->getCurrentClaim());
                if ($faction->getDtr() == 1000)
                    return;
            }

            $player->getCooldowns()->setPvPTimer($player->getCooldowns()->getPvPTimer() - 1);
        }
    }

    public function apple(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getAppleTime() != null) {
            if ($player->getCooldowns()->getAppleTime() == 0) {
                $player->getCooldowns()->setAppleTime(null);
                return;
            }
            $player->getCooldowns()->setAppleTime($player->getCooldowns()->getAppleTime() - 1);
        }
    }

    public function gapple(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getGappleTime() != null) {
            if ($player->getCooldowns()->getGappleTime() == 0) {
                $player->getCooldowns()->setGappleTime(null);
                return;
            }
            $player->getCooldowns()->setGappleTime($player->getCooldowns()->getGappleTime() - 1);
        }
    }

    public function home(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getHomeTime() != null) {

            if ($player->getCooldowns()->getHomeTime() == 0) {

                $home = $player->getFaction()->getHome();

                $player->teleport(new Position($home['x'], $home['y'], $home['z'], $this->getPLugin()->getServer()->getLevelByName($home['level'])));

                $player->sendMessage(TextFormat::YELLOW . 'Warping to' . TextFormat::LIGHT_PURPLE . $player->getFactionName() . TextFormat::YELLOW . "'s HQ.");

                $player->getCooldowns()->setHomeTime(null);
                return;
            }
            $player->getCooldowns()->setHomeTime($player->getCooldowns()->getHomeTime() - 1);
        }
    }

    public function stuck(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getStuckTime() != null) {

            if ($player->getCooldowns()->getStuckTime() == 0) {

                $player->teleport(new Vector3($player->getX() + rand(20, 50), 75, $player->getZ() + rand(20, 50)));

                $player->getCooldowns()->setStuckTime(null);

                return;
            }
            $player->getCooldowns()->setStuckTime($player->getCooldowns()->getStuckTime() - 1);
        }
    }

    public function logout(HCFPlayer $player): void
    {
        if ($player->getCooldowns()->getLogoutTime() != null) {

            if ($player->getCooldowns()->getLogoutTime() == 0) {

                $player->close();
                $player->getCooldowns()->setLogoutTime(null);

                return;
            }
            $player->getCooldowns()->setLogoutTime($player->getCooldowns()->getLogoutTime() - 1);
        }
    }

    public function classLoad(HCFPlayer $player): void
    {
        if ($player->getClass() != null) {
            if ($player->getClass() == KitIds::ARCHER)
                if ($player->getCooldowns()->getArcherEnergy() != 70)
                    $player->getCooldowns()->setArcherEnergy($player->getCooldowns()->getArcherEnergy() + 1);

            if ($player->getClass() == KitIds::BARD)
                if ($player->getCooldowns()->getBardEnergy() != 60)
                    $player->getCooldowns()->setBardEnergy($player->getCooldowns()->getBardEnergy() + 1);

            if ($player->getClass() == KitIds::MAGE)
                if ($player->getCooldowns()->getMageEnergy() != 70)
                    $player->getCooldowns()->setMageEnergy($player->getCooldowns()->getMageEnergy() + 1);
        }

        foreach ($player->getCooldowns()->getAllEffects() as $effectName => $time) {
            if ($time != null) {
                if ($time == 0) {
                    $player->getCooldowns()->setEffectCooldown($effectName, null);
                } else {
                    $player->getCooldowns()->setEffectCooldown($effectName, $time - 1);
                }
            }
        }
    }

    /**
     * @param Cooldown $cooldown
     */
    public function deathBan(Cooldown $cooldown): void
    {
        if ($cooldown->getDeathbanTime() == null)
            return;

        if ($cooldown->getDeathbanTime() == 0) {
            $nbt = HCFLoader::getInstance()->getServer()->getOfflinePlayerData($cooldown->getName());
            $items = [];


            foreach ($nbt->getTag('Inventory') as $slot => $serialize) {
                $item = Item::jsonDeserialize($serialize);
                if ($item->getCustomName() != TextFormat::colorize('&r&6Deathban')) {
                    $items[$slot] = $item;
                }
            }

            $nbt->setTag(new ListTag('Inventory', $items, NBT::TAG_Compound));

            $spawn = HCFLoader::getInstance()->getServer()->getDefaultLevel()->getSpawnLocation();

            $nbt->setTag(new ListTag('Pos',
                [
                    new DoubleTag('', $spawn->x),
                    new DoubleTag('', $spawn->y),
                    new DoubleTag('', $spawn->z)
                ], NBT::TAG_Double));

            $nbt->setTag(new StringTag('Level', HCFLoader::getInstance()->getServer()->getDefaultLevel()->getFolderName()));
            
            HCFLoader::getInstance()->getServer()->saveOfflinePlayerData($cooldown->getName(), $nbt);


        } else
            $cooldown->setDeathbanTime($cooldown->getDeathbanTime() - 1);
    }
}