<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$APPLICATION->SetTitle('Title');
?>

<?php

function processRequests()
{
    $request = Bitrix\Main\Context::getCurrent()->getRequest();
    $id = $request->getQuery('ID');

    return $id;
}

$elementId = processRequests();

$APPLICATION->IncludeComponent(
    'ivekov:comments.list',
    '.default',
    [
        'IBLOCK_ID'            => '16',
        'COMPONENT_TEMPLATE'   => '.default',
        'IBLOCK_TYPE'          => 'rating',
        'PARENT_IBLOCK_TYPE'   => 'DetailingCenter',
        'PARENT_IBLOCK_ID'     => '3',
        'COMPOSITE_FRAME_MODE' => 'A',
        'COMPOSITE_FRAME_TYPE' => 'AUTO',
        'CACHE_TYPE'           => 'A',
        'CACHE_TIME'           => '36000',
        'ELEMENT_ID'           => $elementId,
        'LINK'                 => '97',
        'ELEMENTS_COUNT'       => '5',
    ],
    false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>