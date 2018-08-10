<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/10/2018
 * Time: 12:12 PM
 */

namespace xZeroMCPE\UltraFaction\Configuration\Provider\Types;


/**
 * Interface FactionsProvider
 * @package xZeroMCPE\UltraFaction\Configuration\Provider\Types
 */
interface FactionsProvider
{

    /**
     * @return array
     */
    public function getAllFactions() : array;

    /**
     * @return array
     */
    public function getAllFactionsID() : array;

    public function flushData() : void ;

}