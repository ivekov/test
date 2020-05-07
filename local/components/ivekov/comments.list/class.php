<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

class CVekovCommentsList extends CBitrixComponent
{
    public $requiredModules = ['iblock'];

    protected $cacheDir;
    protected $cacheID;

    public function onPrepareComponentParams($params)
    {
        if ($params['CACHE_TYPE'] == 'Y' || $params['CACHE_TYPE'] == 'A') {
            $params['CACHE_TIME'] = intval($params['CACHE_TIME']);
        } else {
            $params['CACHE_TIME'] = 0;
        }

        $params['IBLOCK_ID'] = isset($params['IBLOCK_ID']) && intval($params['IBLOCK_ID']) > 0 ? intval($params['IBLOCK_ID']) : 0;

        return $params;
    }

    public function executeComponent()
    {
        try {
            $this->cacheDir = '/comments_list/'.$this->arParams['ELEMENT_ID'].'/';
            $this->cacheID = serialize([[$this->arParams['IBLOCK_ID'], $this->arParams['ELEMENT_ID']], false]);
            $cache = Cache::createInstance();
            if ($cache->initCache(86400, $this->cacheID, $this->cacheDir)) {
                $vars = $cache->getVars();
                $this->arResult = $vars['arResult'];
            } else {
                if ($cache->startDataCache(86400, $this->cacheID, $this->cacheDir)) {
                    $this->checkModules();
                    $this->buildResult();

                    $cache->endDataCache([
                        'arResult' => $this->arResult,
                    ]);
                }
            }
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            $this->arResult['ERROR'] = $e->getMessage();
        }
    }

    protected function checkModules()
    {
        foreach ($this->requiredModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new SystemException(Loc::getMessage('CPS_MODULE_NOT_INSTALLED', ['#NAME#' => $module]));
            }
        }
    }

    protected function buildResult()
    {
        $arParams = $this->arParams;

        $arFilter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], '=PROPERTY_'.$arParams['LINK'] => $arParams['ELEMENT_ID'], 'ACTIVE' => 'Y'];
        $arSelect = ['IBLOCK_ID', 'ID', 'NAME', 'ACTIVE', 'DETAIL_PICTURE', 'DETAIL_TEXT', 'PROPERTY_'.$arParams['LINK']];

		$dbItem = CIBlockElement::GetList([], $arFilter, false, ['nPageSize' => $arParams['ELEMENTS_COUNT']], $arSelect);

        $cacheManager = Application::getInstance()->getTaggedCache();
        $cacheManager->startTagCache($this->cacheDir);

        while ($element = $dbItem->Fetch()) {
            $cacheManager->registerTag('iblock_id_'.$element['IBLOCK_ID']);
            $this->arResult['ITEMS'][$element['ID']] = $element;
        }

        $cacheManager->endTagCache();
    }
}