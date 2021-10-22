<?php
use Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

Loc::loadMessages(__FILE__);
CBitrixComponent::includeComponentClass("bitrix:crm.list");

class CRMUsersComponent extends CRMListComponent
{
    /**
     * @var array
     */
    protected $arUsers = [];
	protected $tasks = [];
	protected $sumP = '';
	protected $sumF = '';
	protected $skip = false;

    /**
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getUsersCRM()
	{
		$this->getCRM(true);

		foreach ($this->userIDs as $keyUser => $user)
		{
			foreach ($this->arRes['ITEMS'] as $keyItem => $item)
			{
				if ((int)$user === (int)$item['IBLOCK_ELEMENTS_ELEMENT_CRM_MANAGER_VALUE']) {
					$this->skip = true;
					continue;
				}
				$this->arUsers['ITEMS'][$keyUser]['ID'] = $user;
				foreach ($this->getUsers($this->userIDs) as $valUser)
				{
					if ((int)$user === (int)$valUser['ID'])
					{
						$this->arUsers['ITEMS'][$keyUser]['NAME'] = $valUser['NAME'];
					}
				}
				if ((int)$user === (int)$item['IBLOCK_ELEMENTS_ELEMENT_CRM_CLIENT_VALUE'])
				{
					if ($item['IBLOCK_ELEMENTS_ELEMENT_CRM_STATUS_ITEM_VALUE']
						=== Loc::getMessage('CT_BNL_CRM_TASK_STATUS_P'))
					{
						$this->sumP += $item['IBLOCK_ELEMENTS_ELEMENT_CRM_PRICE_VALUE'];
					} elseif ($item['IBLOCK_ELEMENTS_ELEMENT_CRM_STATUS_ITEM_VALUE']
						=== Loc::getMessage('CT_BNL_CRM_TASK_STATUS_F')) {
						$this->sumF += $item['IBLOCK_ELEMENTS_ELEMENT_CRM_PRICE_VALUE'];
					}
					$this->tasks[] = $keyItem;
				}
			}
			if ($this->skip)
			{
				$this->skip = false;
				continue;
			}
			$this->arUsers['ITEMS'][$keyUser]['SUM_P'] = Loc::GetMessage('CT_BNL_CRM_TASK_PRICE_CURRENCY',
				array(
					'#PRICE#' => ($this->sumP > 0)? $this->sumP: 0
				)
			);
			$this->arUsers['ITEMS'][$keyUser]['SUM_F'] = Loc::GetMessage('CT_BNL_CRM_TASK_PRICE_CURRENCY',
				array(
					'#PRICE#' => ($this->sumF > 0)? $this->sumF: 0
				)
			);
			$this->arUsers['ITEMS'][$keyUser]['COUNT_TASK'] = count($this->tasks);
			unset($this->tasks);
		}
        return $this->arUsers;
    }

    /**
     * @return array|mixed|void|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $this->arResult = $this->getUsersCRM();
        $this->includeComponentTemplate();
        return $this->arResult;
    }
}