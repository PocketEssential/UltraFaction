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
     * @param string $key
     * @return Vector3
     */
    public static function getVectorFromString(string $vector, string $key = ":"): Vector3
    {
        $ex = explode($key, $vector);
        return new Vector3((int)$ex[0], (int)$ex[1], (int)$ex[2]);
    }


    /**
     * @param Vector3 $vector3
     * @param string $key
     * @return string
     */
    public static function getStringFromVector(Vector3 $vector3, $key = ":"): string
    {
        return $vector3->getX() . $key . $vector3->getY() . $key . $vector3->getZ();
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function getFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}