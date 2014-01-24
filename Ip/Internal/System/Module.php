<?php

/**
 * @package   ImpressPages
 *
 *
 */


namespace Ip\Internal\System;


class Module
{


    public function clearCache($cachedUrl)
    {
        \Ip\ServiceLocator::storage()->set('Ip', 'cachedBaseUrl', ipConfig()->baseUrl());

        // TODO move somewhere
        if (ipConfig()->baseUrl() != $cachedUrl) {
            ipEvent('ipUrlChanged', array('oldUrl' => $cachedUrl, 'newUrl' => ipConfig()->baseUrl()));
        }

        static::cacheClear();
    }

    public static function cacheClear()
    {
        $oldCacheVersion = \Ip\ServiceLocator::storage()->get('Ip', 'cacheVersion', 1);
        $newCacheVersion = $oldCacheVersion + 1;
        \Ip\ServiceLocator::storage()->set('Ip', 'cacheVersion', $newCacheVersion);

        ipEvent('ipCacheClear', array('oldCacheVersion' => $oldCacheVersion, 'newCacheVersion' => $newCacheVersion));
    }

    public function getSystemInfo()
    {

        $answer = '';

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, \Ip\Internal\System\Model::instance()->getImpressPagesAPIUrl());
            curl_setopt($ch, CURLOPT_POST, 1);

            $postFields = 'module_name=communication&action=getInfo&version=1&afterLogin=';
            $postFields .= '&systemVersion=' . \Ip\ServiceLocator::storage()->get('Ip', 'version');

            //TODOXX refactor #144
//            $groups = \Modules\developer\modules\Db::getGroups();
//            foreach ($groups as $groupKey => $group) {
//                $modules = \Modules\developer\modules\Db::getModules($group['id']);
//                foreach ($modules as $moduleKey => $module) {
//                    $postFields .= '&modules[' . $group['name'] . '][' . $module['name'] . ']=' . $module['version'];
//                }
//            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_REFERER, ipConfig()->baseUrl());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            $answer = curl_exec($ch);

            if (json_decode($answer) === null) { //json decode error
                return '';
            }


        }

        return $answer;
    }


    public function getUpdateInfo()
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        $ch = curl_init();

        $curVersion = \Ip\ServiceLocator::storage()->get('Ip', 'version');

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1800, // set this to 30 min so we dont timeout
            CURLOPT_URL => \Ip\Internal\System\Model::instance()->getImpressPagesAPIUrl(),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'module_name=communication&action=getUpdateInfo&curVersion=' . $curVersion
        );

        curl_setopt_array($ch, $options);

        $jsonAnswer = curl_exec($ch);

        $answer = json_decode($jsonAnswer, true);

        if ($answer === null || !isset($answer['status']) || $answer['status'] != 'success') {
            return false;
        }

        return $answer;
    }

}

