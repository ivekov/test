<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<?

function processRequests()
{
	$request = Bitrix\Main\Context::getCurrent()->getRequest();
	$id = $request->getQuery('ID');
	return $id;
}

$elementId = processRequests();

$APPLICATION->IncludeComponent(
	"ivekov:news.detail", 
	".default", 
	array(
		"IBLOCK_ID" => "5",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "DetailingCenter",
		"PARENT_IBLOCK_TYPE" => "DetailingCenter",
		"PARENT_IBLOCK_ID" => "3",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000",
		"ELEMENT_ID" => $elementId,
		"IMAGE_HEIGHT" => "800",
		"IMAGE_WIDTH" => "800"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>