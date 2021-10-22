<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?php if ($arResult["ITEMS"]):?>
    <div class="table">
        <div class="row header green">
            <div class="cell">
                <?=Loc::getMessage("CT_BNL_CRM_TASK_ID");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_NAME");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_NUMBER");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_PRICE");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_CLIENT");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_MANAGER");?>
            </div>
            <div class="cell">
				<?=Loc::getMessage("CT_BNL_CRM_TASK_STATUS");?>
            </div>
        </div>

		<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
            <div class="row">
                <?foreach ($arItem as $key => $cellVal):?>
                    <div class="cell" data-title="Product">
                        <?=$cellVal;?>
                    </div>
                <?endforeach;?>
            </div>
		<?endforeach;?>
    </div>
<? endif;?>