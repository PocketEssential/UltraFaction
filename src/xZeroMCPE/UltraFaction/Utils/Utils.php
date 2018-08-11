<?php
/**
 * Created by PhpStorm.
 * User: xZero
 * Date: 8/11/2018
 * Time: 11:24 AM
 */

namespace xZeroMCPE\UltraFaction\Utils;


use pocketmine\math\Vector3;

/**
 * Class Utils
 * @package xZeroMCPE\UltraFaction\Utils
 */

/**
 * Class Utils
 * @package xZeroMCPE\UltraFaction\Utils
 */
class Utils
{

    /**
     * @param string $vector
     * @return Vector3
     */
    /**
     * @param string $vector
     * @return Vector3
     */
    public static function getVectorFromString(string $vector): Vector3
    {
        $ex = explode(":", $vector);
        return new Vector3((int)$ex[0], (int)$ex[1], (int)$ex[2]);
    }

    /**
     * @param Vector3 $vector3
     * @return string
     */
    /**
     * @param Vector3 $vector3
     * @return string
     */
    public static function getStringFromVector(Vector3 $vector3): string
    {
        return $vector3->getX() . ":" . $vector3->getY() . ":" . $vector3->getZ();
    }
}