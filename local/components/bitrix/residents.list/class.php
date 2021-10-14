<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

\Bitrix\Main\Loader::includeModule('iblock');

class ResidentsListComponent extends CBitrixComponent {
    /**
     * @var array
     */
    protected $arRes = [];

    /**
     * Get Fields from Property
     * @param array $prop
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getFieldsByProp(array $prop) {
        $rsHouse = \Bitrix\Iblock\ElementTable::getById(
            $prop["IBLOCK_ELEMENTS_ELEMENT_RESIDENTS_HOME_IBLOCK_GENERIC_VALUE"]
        )->fetch();
        $classHouse = \Bitrix\Iblock\Iblock::wakeUp($rsHouse["IBLOCK_ID"])->getEntityDataClass();
        return $classHouse::getByPrimary($prop["IBLOCK_ELEMENTS_ELEMENT_RESIDENTS_HOME_IBLOCK_GENERIC_VALUE"], [
            "select" => [
                "CITY.VALUE",
                "STREET.VALUE",
                "NUMBER.VALUE",
            ]
        ])->fetch();
    }

    /**
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getResidents() {
        $nav = new \Bitrix\Main\UI\PageNavigation("nav-more-news");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams["NEWS_COUNT"])
            ->initFromUri();

        $classResid = \Bitrix\Iblock\Iblock::wakeUp($this->arParams["IBLOCK_ID"])->getEntityDataClass();
        $rsResid = $classResid::getList(array(
            "select" => ["ID", "HOME", "FIO"],
            "filter" => ["=ACTIVE" => "Y"],
            "order" => ["TIMESTAMP_X" => "DESC"],
            "cache" => [
                "ttl" => $this->arParams["CACHE_TIME"],
            ],
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
        ));
        $nav->setRecordCount($rsResid->getCount());

        foreach ($rsResid->fetchAll() as $arResid) {
            $this->arRes["ITEMS"][$arResid["ID"]] = $this->getFieldsByProp($arResid);
            $this->arRes["ITEMS"][$arResid["ID"]]["FIO"] = $arResid["IBLOCK_ELEMENTS_ELEMENT_RESIDENTS_FIO_VALUE"];
        }
        $this->arRes["NAV"] = $nav;
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
        $this->arResult = $this->getResidents();
        $this->includeComponentTemplate();
        return $this->arResult;
    }
};