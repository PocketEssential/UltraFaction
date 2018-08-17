<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 12:11 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration\Provider;


use xZeroMCPE\UltraFaction\Configuration\Provider\Types\FactionsProvider;
use xZeroMCPE\UltraFaction\Configuration\Provider\Types\JsonProvider;
use xZeroMCPE\UltraFaction\Configuration\Provider\Types\YamlProvider;
use xZeroMCPE\UltraFaction\UltraFaction;

/**
 * Class Provider
 * @package xZeroMCPE\UltraFaction\Configuration\Provider
 */
class Provider
{

    public $provider;
    public $providerType;

    /**
     * Provider constructor.
     * @param string $provider
     */
    public function __construct(string $provider)
    {
        $this->provider = $provider;
        $this->loadProvider();
    }

    public function loadProvider() : void {

        if(!file_exists(Provider::getProviderDataFolder())){
            @mkdir(Provider::getProviderDataFolder());
        }

        switch (strtolower($this->provider)){

            case "json":
                $this->providerType = new JsonProvider();
                break;

            case "yaml":
                $this->providerType = new YamlProvider();
                break;

            default:
                UltraFaction::getInstance()->getLogger()->error(str_replace(['{DATA_PROVIDER}', '{DEFAULT_PROVIDER}'], [$this->provider, 'json'], UltraFaction::getInstance()->getLanguage()->getLanguageValueArray('ULTRA_FACTION')['DATA_PROVIDER_NOT_FOUND']));
                $this->provider = 'json';
                $this->loadProvider();
                break;
        }
    }

    /**
     * @return FactionsProvider
     */
    public function getProvider() : FactionsProvider {
        return $this->providerType;
    }

    /**
     * @return string
     */
    public static function getProviderDataFolder() : string {
        return UltraFaction::getInstance()->getServer()->getDataPath() . "UltraFaction/". "Data/";
    }
}