<?php

namespace Koralop\HCF\scoreboard\scheduler;

use Koralop\Staff\StaffLoader;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\modules\timer\Timer;
use Koralop\HCF\scoreboard\Scoreboard;

use Koralop\HCF\utils\Time;
use pocketmine\scheduler\Task;
use pocketmine\Server;

use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;

class ScoreboardTask extends Task
{

    public function onRun(int $currentTick)
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player instanceof HCFPlayer) {
                $data = [];

                if ($player->isEnter()) {

                    if ($player->getCooldowns()->getDeathbanTime() != null) {
                        $data[] = TextFormat::RED . TextFormat::BOLD . 'Deathban' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::RED . Time::secondAndMinutes(HCFLoader::getModulesManager()->getModuleById(ModulesIds::PVP)->getDeathBan($player));
                        $data[] = TextFormat::GOLD . TextFormat::BOLD . 'Lives' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RED . HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName());
                    } else {

                        if ($player->getCooldowns()->getEnderPearl() != null)
                            $data[] = TextFormat::BOLD . TextFormat::YELLOW . 'EnderPearl' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . Time::secondAndMinutes($player->getCooldowns()->getEnderPearl());

                        if ($player->getCooldowns()->getCombatTag() != null)
                            $data[] = TextFormat::BOLD . TextFormat::RED . 'Spawn Tag' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . Time::secondAndMinutes($player->getCooldowns()->getCombatTag());

                        if ($player->getCooldowns()->getPvPTimer() != null)
                            $data[] = TextFormat::BOLD . TextFormat::GREEN . 'Starting Timer' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . Time::secondAndMinutes($player->getCooldowns()->getPvPTimer());

                        if ($player->getCooldowns()->getAppleTime() != null)
                            $data[] = TextFormat::BOLD . TextFormat::GOLD . 'Apple' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . Time::secondAndMinutes($player->getCooldowns()->getAppleTime());

                        if ($player->getCooldowns()->getGappleTime() != null)
                            $data[] = TextFormat::BOLD . TextFormat::GOLD . 'Gapple: ' . TextFormat::RESET . TextFormat::GOLD . Time::secondAndMinutes($player->getCooldowns()->getGappleTime());

                        if ($player->getCooldowns()->getHomeTime() != null)
                            $data[] = TextFormat::BOLD . TextFormat::BLUE . 'Home: ' . TextFormat::RESET . TextFormat::BLUE . Time::secondAndMinutes($player->getCooldowns()->getHomeTime());

                        if ($player->getCooldowns()->getStuckTime() != null)
                            $data[] = TextFormat::BOLD . TextFormat::RED . 'Stuck' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . Time::secondAndMinutes($player->getCooldowns()->getStuckTime());

                        if ($player->getClass() != null) {

                            if ($player->getClass() == KitIds::MAGE)
                                $data[] = TextFormat::BOLD . TextFormat::DARK_GREEN . 'Mage Energy' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . $player->getCooldowns()->getMageEnergy() . '.0';

                            if ($player->getClass() == KitIds::BARD)
                                $data[] = TextFormat::BOLD . TextFormat::YELLOW . 'Bard Energy' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . $player->getCooldowns()->getBardEnergy() . '.0';

                            if ($player->getClass() == KitIds::ARCHER)
                                $data[] = TextFormat::BOLD . TextFormat::LIGHT_PURPLE . 'Archer Energy' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::GRAY . $player->getCooldowns()->getArcherEnergy() . '.0';

                        }

                        foreach (HCFLoader::getModulesManager()->getModuleById(ModulesIds::TIMER)->getAllTimer() as $timerName => $timer) {
                            if ($timer instanceof Timer) {
                                if ($timer->isEnable()) {
                                    $data[] = str_replace('{time}', Time::HSM($timer->getCurrentTime()), $timer->getFormat());
                                }
                            }
                        }

                        foreach ($player->getCooldowns()->getAllEffects() as $effectName => $time) {
                            if ($time != null) {
                                $data[] = TextFormat::GOLD . TextFormat::BOLD . $effectName . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RED . Time::secondAndMinutes($time);
                            }

                            if ($player->getFocus() != null) {

                                if (count($data) >= 1) {
                                    $data[] = '    ';
                                }

                                $f = HCFLoader::getFactionManager()->getFaction($player->getFocus());

                                $data[] = TextFormat::GOLD . TextFormat::BOLD . 'Team' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::YELLOW . $player->getFocus();
                                $data[] = TextFormat::GOLD . TextFormat::BOLD . 'HQ' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::YELLOW . ($f->getHome() == null ? 'Not Set' : $f->getHome()['x'] . ', ' . $f->getHome()['z'] . TextFormat::GRAY . '(' . floor($player->distance(new Vector3($f->getHome()['x'], $f->getHome()['y'], $f->getHome()['z']))) . 'm)');
                                $data[] = TextFormat::GOLD . TextFormat::BOLD . 'DTR' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::YELLOW . $f->getDtr() . '' . TextFormat::GRAY . ($f->getFreezeTime() == null ? '' : ' (' . Time::asd($f->getFreezeTime()) . ')');
                                $data[] = TextFormat::GOLD . TextFormat::BOLD . 'Online' . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::RESET . TextFormat::YELLOW . $f->getCountOnlinePLayers() . TextFormat::GRAY . ' / ' . TextFormat::YELLOW . count($f->getPlayers());
                            }

                            if (HCFLoader::getModulesManager()->getModuleById(ModulesIds::KOTH)->getKothEnable() != null) {
                                $koth = HCFLoader::getModulesManager()->getModuleById(ModulesIds::KOTH)->getKoth(HCFLoader::getModulesManager()->getModuleById(ModulesIds::KOTH)->getKothEnable());

                                $data[] = TextFormat::BOLD . TextFormat::BLUE . $koth->getName() . TextFormat::RESET . TextFormat::WHITE . ': ' . TextFormat::GRAY . Time::secondAndMinutes($koth->getTime());
                            }
                        }
                        if (count($data) >= 1) {

                            if (!HCFLoader::getScoreboardManager()->isScoreboard($player))
                                HCFLoader::getScoreboardManager()->addScoreboard($player);

                            $data[] = TextFormat::GRAY . str_repeat(' ', 29);
                            $data[] = TextFormat::GRAY . str_repeat(' ', 7) . 'play.vipermc.net';

                            $data = array_merge([
                                TextFormat::GRAY . str_repeat(' ', 30)
                            ], $data);
                            // ━

                            foreach ($data as $line => $text) {
                                $player->getScoreboard()->addLine($line + 1, ' ' . $text);
                            }
                        } else {
                            if (HCFLoader::getScoreboardManager()->isScoreboard($player)) {
                                $player->getScoreboard()->clearScoreboard();
                                $player->getScoreboard()->removeScoreboard();

                                HCFLoader::getScoreboardManager()->removeScoreboard($player);
                            }
                        }
                    }
                }
            }
        }
    }
}