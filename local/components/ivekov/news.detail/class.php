<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

class CVekovNewsDetail extends CBitrixComponent
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
            $this->cacheDir = '/news_detail/'.$this->arParams['ELEMENT_ID'].'/';
            $this->cacheID = serialize([[$this->arParams['IBLOCK_ID'], $this->arParams['ELEMENT_ID']], false]);
            $cache = Cache::createInstance();
            if ($cache->initCache(86400, $this->cacheID, $this->cacheDir)) {
                $vars = $cache->getVars();
                $this->arResult = $vars['arResult'];
            } else {
                if ($cache->startDataCache(86400, $this->cacheID, $this->cacheDir)) {
                    $this->checkModules();
                    $this->prepareData();
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

    protected function prepareData()
    {
        if (!$this->arParams['ELEMENT_ID']) {
            \Bitrix\Iblock\Component\Tools::process404('', true, true, true, '');
        }

        $this->arResult['IBLOCK'] = [];
        if ($this->arParams['IBLOCK_ID']) {
            $this->arResult['IBLOCK'] = CIBlock::GetByID($this->arParams['IBLOCK_ID'])->Fetch();
        }
        if (!$this->arResult['IBLOCK']) {
            throw new Exception('Инфоблок не найден');
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
        $arFilter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], '=ID' => $arParams['ELEMENT_ID'], 'ACTIVE' => 'Y'];
        $arSelect = ['IBLOCK_ID', 'ID', 'NAME', 'ACTIVE', 'DETAIL_PICTURE', 'DETAIL_TEXT'];

        $dbItem = \Bitrix\Iblock\ElementTable::getList([
            'select' => $arSelect,
            'filter' => $arFilter,
            'limit'  => 1,
        ]);

        $cacheManager = Application::getInstance()->getTaggedCache();
        $cacheManager->startTagCache($this->cacheDir);

        if ($element = $dbItem->Fetch()) {
            $cacheManager->registerTag('iblock_id_'.$element['IBLOCK_ID']);
            $this->arResult = $element;
            $this->getImageUrl();
        } else {
            \Bitrix\Iblock\Component\Tools::process404('', true, true, true, '');
        }
        $cacheManager->endTagCache();
    }

    protected function getImageUrl()
    {
        if ($this->arParams['IMAGE_HEIGHT'] && $this->arParams['IMAGE_WIDTH']) {
            $this->arResult['RESIZED_PICTURE_URL'] = $this->resizeImage();
        } else {
            $this->arResult['RESIZED_PICTURE_URL'] = \CFile::GetPath($this->arResult['DETAIL_PICTURE']);
        }
    }

    protected function resizeImage()
    {
        $resizedPictureUrl = \CFile::ResizeImageGet(
            $this->arResult['DETAIL_PICTURE'],
            ['width' => $this->arParams['IMAGE_WIDTH'], 'height' => $this->arParams['IMAGE_HEIGHT']],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            false,
            false,
            false,
            false
        );

        return $resizedPictureUrl['src'];
    }
}
