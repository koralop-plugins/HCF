<?php

namespace Koralop\HCF;

use Koralop\HCF\factions\Faction;
use Koralop\HCF\modules\kit\KitManager;
use Koralop\HCF\modules\npc\entity\NPCEntity;
use Koralop\HCF\player\cooldowns\Cooldown;
use Koralop\HCF\scoreboard\Scoreboard;
use Koralop\HCF\utils\Translate;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\level\particle\FloatingTextParticle;
use Koralop\HCF\utils\FloatingTextParticle as FT;
use pocketmine\level\Position;

use pocketmine\lang\TextContainer;
use pocketmine\lang\TranslationContainer;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\TextFormat;

/**
 * Class HCFPlayer
 * @package Koralop\HCF
 */
class HCFPlayer extends Player
{

    /** @var string */
    public const FACTION_CHAT = 'Faction';

    /** @var string */
    public const PUBLIC_CHAT = 'Public';

    /** @var string */
    public const ALLY_CHAT = 'Ally';

    /** @var string|null */
    protected ?string $class = null;

    /** @var bool */
    protected bool $claimInteract = false;

    /** @var string|null */
    protected ?string $beforeClaim = 'The Wilderness';

    /** @var bool */
    protected bool $enter = false;

    /** @var string */
    protected string $chat = 'Public';

    /** @var array */
    protected array $fInvite = [];

    /** @var string|null */
    protected ?string $focus = null;

    /** @var bool */
    protected bool $kothClaim = false;

    /** @var FloatingTextParticle[] */
    private array $floatingTexts = [];

    /**
     * @param $class
     */
    public function setClass($class)
    {

        if ($class == null) {
            $this->class = $class;
            return;
        }

        if ($class == $this->class) {
            $this->class = $class;
            return;
        }

        $name = isset(KitManager::KIT_IDS[$class]) ? KitManager::KIT_IDS[$class] : $class;
        $this->sendMessage(TextFormat::colorize('&bClass: &b&l' . $name . ' &7--> &aEnabled!' . TextFormat::EOL . '&bClass Info: &astore.vipermc.net/' . $name));

        $this->class = $class;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @return Scoreboard|null
     */
    public function getScoreboard(): ?Scoreboard
    {
        return HCFLoader::getScoreboardManager()->getScoreboard($this);
    }

    /**
     * @return Cooldown
     */
    public function getCooldowns(): Cooldown
    {
        return HCFLoader::getInstance()->getCooldownManager()->getCooldown($this);
    }


    public function join(): void
    {
        if (!HCFLoader::getInstance()->getCooldownManager()->isCooldown($this))
            HCFLoader::getInstance()->getCooldownManager()->addCooldown($this);

        if (!HCFLoader::getScoreboardManager()->isScoreboard($this))
            HCFLoader::getScoreboardManager()->addScoreboard($this);

        if (!$this->hasPlayedBefore())
            $this->getCooldowns()->setPvPTimer(HCFLoader::getYamlProvider()->getCooldowns()['pvptimer']);


        foreach (Server::getInstance()->getDefaultLevel()->getEntities() as $entity) {
            if ($entity instanceof NPCEntity)
                $entity->spawnText($this);

        }

        $this->sendMessage(' ');
        $this->sendMessage(TextFormat::GOLD . " You're now connect to our " . TextFormat::BOLD . 'Infernal ' . TextFormat::RESET . TextFormat::GOLD . 'server.');
        $this->sendMessage(TextFormat::colorize(' &6&oThe map began on 27 of June.'));
        $this->sendMessage(' ');
        $this->enter = true;
    }


    public function quit(): void
    {
        $this->enter = false;
    }

    /**
     * @return string|null
     */
    public function getFactionName(): ?string
    {
        return HCFLoader::getFactionManager()->getFactionByPlayer($this->getName());
    }

    /**
     * @return bool
     */
    public function inFaction(): bool
    {
        return HCFLoader::getFactionManager()->inFaction($this->getName());
    }

    public function addTool(): void
    {
        $item = ItemFactory::get(ItemIds::GOLD_HOE, 0, 1);
        $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . 'Claim Tool');

        $nbt = $item->getNamedTag();
        $nbt->setString('claim-tool', '');

        $item->setCompoundTag($nbt);

        $this->getInventory()->addItem($item);

        $this->sendMessage(TextFormat::GOLD . " Team land started.");
        $this->sendMessage(TextFormat::YELLOW . "Left click at corner of the land you'd like to claim.");
        $this->sendMessage(TextFormat::YELLOW . "Right click on the second corner of the land you'd like to claim.");
        $this->sendMessage(TextFormat::YELLOW . "Crouch left click the air to purchase your claim.");
    }

    /**
     * @param bool $claim
     * @return bool
     */
    public function setClaimInteract(bool $claim)
    {
        return $this->claimInteract = $claim;
    }

    /**
     * @return bool
     */
    public function getClaimInteract(): bool
    {
        return $this->claimInteract;
    }

    /**
     * @param bool $claim
     * @return bool
     */
    public function setKothClaim(bool $claim)
    {
        return $this->kothClaim = $claim;
    }

    /**
     * @return bool
     */
    public function getKothClaim(): bool
    {
        return $this->kothClaim;
    }

    /**
     * @param string $beforeClaim
     * @return string
     */
    public function setBeforeClaim(string $beforeClaim)
    {
        return $this->beforeClaim = $beforeClaim;
    }

    /**
     * @return string|null
     */
    public function getBeforeClaim(): ?string
    {
        return $this->beforeClaim;
    }

    /**
     * @return string
     */
    public function getCurrentClaim(): string
    {
        return HCFLoader::getFactionManager()->getFactionByPosition($this);
    }

    /**
     * @return bool
     */
    public function isEnter(): bool
    {
        return $this->enter;
    }

    /**
     * @param $chat
     * @return mixed
     */
    public function setChat($chat)
    {
        return $this->chat = $chat;
    }

    /**
     * @return string
     */
    public function getChat(): string
    {
        return $this->chat;
    }

    /**
     * @return Faction
     */
    public function getFaction(): Faction
    {
        return HCFLoader::getFactionManager()->getFaction($this->getFactionName());
    }

    /**
     * @param string $factionName
     * @return string
     */
    public function addInvite(string $factionName)
    {
        return $this->fInvite[$factionName] = $factionName;
    }

    /**
     * @return array
     */
    public function getInvite(): array
    {
        return $this->fInvite;
    }

    /**
     * @return array|null
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @param string|null $factionName
     * @return string|null
     */
    public function setFocus(?string $factionName)
    {
        return $this->focus = $factionName;
    }

    /**
     * @return bool
     */
    public function isGod(): bool
    {
        if ($this->isOp()) {
            if ($this->getGamemode() == 1) {
                return true;
            }
        }
        return false;
    }

    public function spawn(): void
    {
        $this->teleport(HCFLoader::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
    }

    /**
     * @param TextContainer|string $message
     */
    public function sendMessage($message)
    {
        $message = TextFormat::colorize($message);

        if ($message instanceof TextContainer) {
            if ($message instanceof TranslationContainer) {
                $this->sendTranslation($message->getText(), $message->getParameters());
                return;
            }
            $message = $message->getText();
        }

        $pk = new TextPacket();
        $pk->type = TextPacket::TYPE_RAW;
        $pk->message = $this->server->getLanguage()->translateString($message);
        $this->dataPacket($pk);
    }
}