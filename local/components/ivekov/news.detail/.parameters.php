<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
    return;

$arTypes = CIBlockParameters::GetIBlockTypes(['-' => ' ']);

$arIBlocks = [];
if (
    isset($arCurrentValues['IBLOCK_TYPE'])
    && trim($arCurrentValues['IBLOCK_TYPE'])
    && trim($arCurrentValues['IBLOCK_TYPE']) != '-'
) {
    $rsIBlocks = CIBlock::GetList(
        ['SORT' => 'ASC'],
        ['SITE_ID' => $_REQUEST['site'], 'TYPE' => trim($arCurrentValues['IBLOCK_TYPE'])]
    );
    while ($arIBlock = $rsIBlocks->Fetch()) {
        $arIBlocks[$arIBlock['ID']] = '[' . $arIBlock['ID'] . '] ' . $arIBlock['NAME'];
    }
}

$arSections = [];
if (
    isset($arCurrentValues['IBLOCK_ID'])
    && intval($arCurrentValues['IBLOCK_ID']) > 0
) {
    $rsSections = CIBlockSection::GetList(
        [],
        ['IBLOCK_ID' => intval($arCurrentValues['IBLOCK_ID'])]
    );
    while ($arSection = $rsSections->Fetch()) {
        $arSections[$arSection['ID']] = '[' . $arSection['ID'] . '] ' . $arSection['NAME'];
    }
}

$arComponentParameters = [
    'GROUPS' => [
    ],
    'PARAMETERS' => [
        'CACHE_TIME' => [
            'DEFAULT' => '36000'
        ],
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arTypes,
            'DEFAULT' => 'offers',
            'REFRESH' => 'Y'
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '',
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ],
        'ELEMENT_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Элемент',
            'TYPE' => 'STRING'
        ],
        'IMAGE_WIDTH' => [
            'PARENT' => 'BASE',
            'NAME' => 'Ширина изображения',
            'TYPE' => 'STRING'
        ],
        'IMAGE_HEIGHT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Высота изображения',
            'TYPE' => 'STRING'
        ]
    ]
];