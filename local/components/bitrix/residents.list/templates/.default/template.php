<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {die();}?>
<ul>
<?php
foreach($arResult["ITEMS"] as $keyItem => $arItem):
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
    <li class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        --<?=$arItem["FIO"];?> - <?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_CITY_VALUE"];?>,<?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_STREET_VALUE"];?>,<?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_NUMBER_VALUE"];?>
    </li>
<?endforeach;?>
</ul>
<? $APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation", 
	"modern",
	array(
        "NAV_OBJECT" => $arResult["NAV"],
        "SEF_MODE" => "N",
	),
	false
);
?>
