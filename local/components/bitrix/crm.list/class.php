<?
use Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class CRMListComponent extends CBitrixComponent
{
    /**
     * @var array
     */
    protected $arRes = [];
    protected $arSelect = [];
	protected $userIDs = [];

	/**
	 * @param array $userIDs
	 * @return array|void
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	protected function getUsers(array $userIDs)
	{
		return \Bitrix\Main\UserTable::getList(array(
			'select' => ['ID','NAME'],
			'filter' => ['ID' => $userIDs],
			'cache' => ['ttl' => $this->arParams['CACHE_TIME']],
		))->fetchAll();
	}

	/**
	 * @param array $arCRM
	 * @param int $returnName
	 * @param array $users
	 * @return array
	 */
	private function checkArr(array $arCRM, int $returnName, array $users)
	{
		$arr = [];
		foreach ($arCRM as $keyString => $val)
		{
			if (preg_match('/CLIENT_VALUE|MANAGER_VALUE/', $keyString))
			{
				if ($returnName)
				{
					foreach ($users as $arUser)
					{
						if ((int)$val === (int)$arUser['ID'])
						{
							$arr[$keyString] = $arUser['NAME'];
						}
					}
				} else {
					$this->userIDs[] = (int)$val;
					$arr[$keyString] = $val;
				}
			} elseif (preg_match('/CLIENT*|MANAGER*/', $keyString)) {
				unset($arCRM[$keyString]);
			} else {
				$arr[$keyString] = $val;
			}
		}
		return $arr;
	}

    /**
     *
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getCRM(int $returnUserIds)
	{
        $classCRM = \Bitrix\Iblock\Iblock::wakeUp($this->arParams['IBLOCK_ID'])->getEntityDataClass();
        $rsCRM = $classCRM::getList(array(
            'select' => ['ID', 'NAME', 'NUMBER.VALUE', 'PRICE.VALUE', 'CLIENT', 'MANAGER', 'STATUS.ITEM.VALUE'],
            'filter' => ['=ACTIVE' =>  'Y'],
            'order' => ['TIMESTAMP_X' => 'DESC'],
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']],
        ));
        foreach ($rsCRM->fetchAll() as $arCRM)
		{
			$this->arRes['ITEMS'][$arCRM['ID']] = $this->checkArr($arCRM, false, array());
        }
		$this->userIDs = array_unique($this->userIDs);
		if ($returnUserIds)
		{
			return false;
		}
		foreach ($this->arRes['ITEMS'] as $key => $arCRM)
		{
			$this->arRes['ITEMS'][$key] = $this->checkArr($arCRM, true, $this->getUsers($this->userIDs));
		}
        return $this->arRes;
    }

    /**
     * @return array|mixed|void|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $this->arResult = $this->getCRM(false);
        $this->includeComponentTemplate();
        return $this->arResult;
    }
}