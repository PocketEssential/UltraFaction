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
        switch (strtolower($this->provider)){

            case "json":
                $this->providerType = new JsonProvider();
                break;

            default:
                UltraFaction::getInstance()->getLogger()->error("[PROVIDER] Unknown data provider detected [{$this->provider}], using the default one");
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
    public function getProviderName() : string {
        return $this->provider;
    }
}