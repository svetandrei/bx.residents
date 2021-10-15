<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {die();}?>
<ul>
<?php
foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
    <li class="news-item" id="<?=$this->GetEditAreaId($keyItem);?>">
        <?=$arItem["FIO"];?> - <?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_CITY_VALUE"];?>,<?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_STREET_VALUE"];?>,<?=$arItem["IBLOCK_ELEMENTS_ELEMENT_HOUSES_NUMBER_VALUE"];?>
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
