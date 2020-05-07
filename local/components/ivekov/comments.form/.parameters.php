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

$arProps = [];
if (
    isset($arCurrentValues['IBLOCK_ID'])
    && $arCurrentValues['IBLOCK_ID'] > 0
) {
    $res = CIBlock::GetProperties($arCurrentValues['IBLOCK_ID'], [], []);
    while ($arProp = $res->Fetch()) {
		$arProps[$arProp['ID']] = '[' . $arProp['ID'] . '] ' . $arProp['NAME'];
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
            'NAME' => 'Тип инфоблока с комментариями',
            'TYPE' => 'LIST',
            'VALUES' => $arTypes,
            'DEFAULT' => 'offers',
            'REFRESH' => 'Y'
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок комментариев',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '',
            'REFRESH' => 'Y'
        ],
        'ELEMENT_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Элемент',
            'TYPE' => 'STRING'
        ],
        'LINK' => [
            'PARENT' => 'BASE',
            'NAME' => 'Свойство привязки',
            'TYPE' => 'LIST',
            'VALUES' => $arProps,
            'DEFAULT' => '',
            'REFRESH' => 'Y'
        ],
    ]
];