<?php

namespace Koralop\HCF\modules;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\announce\AnnounceManager;
use Koralop\HCF\modules\ce\EnchantManager;
use Koralop\HCF\modules\claim\ClaimManager;
use Koralop\HCF\modules\elevator\ElevatorManager;
use Koralop\HCF\modules\kit\KitManager;
use Koralop\HCF\modules\koth\KothManager;
use Koralop\HCF\modules\logout\LogoutManager;
use Koralop\HCF\modules\npc\NPCManager;
use Koralop\HCF\modules\partner\PartnerManager;
use Koralop\HCF\modules\pvp\PvPManager;
use Koralop\HCF\modules\shop\ShopManager;
use Koralop\HCF\modules\subclaim\SubClaimManager;
use Koralop\HCF\modules\timer\TimerManager;

/**
 * Class ModulesManager
 * @package Koralop\HCF\modules
 */
class ModulesManager
{

    /** @var array */
    private array $data = [];

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $this->data['shopManager'] = new ShopManager;
        $this->data['elevatorManager'] = new ElevatorManager;
        $this->data['NPCManager'] = new NPCManager;
        $this->data['timerManager'] = new TimerManager;
        $this->data['logoutManager'] = new LogoutManager;
        $this->data['kothManager'] = new KothManager;
        $this->data['kitManager'] = new KitManager;
        $this->data['pvPManager'] = new PvPManager;
        $this->data['announceManager'] = new AnnounceManager;
        $this->data['subClaimManager'] = new SubClaimManager;
        $this->data['enchantManager'] = new EnchantManager;

        $this->data['partnerManager'] = new PartnerManager;

        $this->data['claimManager'] = new ClaimManager;
    }

    /**
     * @param $id
     * @return AnnounceManager|EnchantManager|ElevatorManager|KitManager|KothManager|LogoutManager|NPCManager|PartnerManager|PvPManager|ShopManager|SubClaimManager|TimerManager|ClaimManager
     */
    public function getModuleById($id)
    {
        switch ($id) {
            case 1:
                return $this->data['announceManager'];
            case 2:
                return $this->data['pvPManager'];
            case 3:
                return $this->data['kitManager'];
            case 4:
                return $this->data['kothManager'];
            case 5:
                return $this->data['logoutManager'];
            case 6:
                return $this->data['shopManager'];
            case 7:
                return $this->data['NPCManager'];
            case 8:
                return $this->data['elevatorManager'];
            case 9:
                return $this->data['timerManager'];
            case 10:
                return $this->data['subClaimManager'];
            case 11:
                return $this->data['enchantManager'];
            case 12:
                return $this->data['partnerManager'];
            case 13:
                return $this->data['claimManager'];
        }
        return null;
    }
}