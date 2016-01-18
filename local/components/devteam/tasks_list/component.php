<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($arParams["PROJECT"] && !$USER->hasRightsToViewProject($arParams["PROJECT"])) {
    ShowError('У Вас нет прав на просмотр этого проекта');
    return;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!$arParams['DATE_FORMAT']) {
    $arParams['DATE_FORMAT'] = 'j F Y';
}

if($arParams["COUNT"] <= 0) {
    $arParams["COUNT"] = 20;
}

CModule::IncludeModule('iblock');


/* projects */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields(); 
    if($arParams["PROJECT"] == $arFields['ID']) {
        $arProps = $ob->GetProperties();
        $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE'];
        $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE']; 
        $arResult['USERS'] = BitrixHelper::getUsersArrByIds(array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS']));
    }
    $arResult['PROJECTS'][$arFields['ID']] = $arFields;
}

if(!$arParams["PROJECT"]) {
    $projects = array_keys($arResult['PROJECTS']);
    if(!$projects) {
        ShowError('Вы не привязаны ни к одному проекту');
        return;
    }
} 


/* tasks */

$sorts = array('date' => array('ID' => 'DESC'), 
               'priority' => array('PROPERTY_PRIORITY' => 'DESC'),
               'calc' => array('PROPERTY_CALC' => 'DESC'));
$defaultSort = 'date';
if($sort = $_REQUEST['sort']) { 
    if(in_array($sort, array_keys($sorts))) {
        $_SESSION['LIST_SORT'] = $sort;
    }
} 
if(!$_SESSION['LIST_SORT']) {
    $_SESSION['LIST_SORT'] = $defaultSort;
} 
$arResult['SORT'] = $_SESSION['LIST_SORT'];

$filters = array('all' => array(), 
                 'open' => array('!PROPERTY_STATUS' => STATUS_LIST_ACCEPT),
                 'end' =>  array('PROPERTY_STATUS' => STATUS_LIST_ACCEPT),
                 'nocalc' => array('PROPERTY_STATUS' => false),
                 'agrcalced' => array('PROPERTY_STATUS' => STATUS_LIST_AGR_CALCED),
                 'calcreject' => array('PROPERTY_STATUS' => STATUS_LIST_CALC_REJECT),
                 'calcagred' => array('PROPERTY_STATUS' => STATUS_LIST_CALC_AGRED),
                 'work' => array('PROPERTY_STATUS' => STATUS_LIST_WORK),
                 'pause' => array('PROPERTY_STATUS' => STATUS_LIST_PAUSE),
                 'complete' => array('PROPERTY_STATUS' => STATUS_LIST_COMPLETE),
                 'reject' => array('PROPERTY_STATUS' => STATUS_LIST_REJECT),
                 'short' => array('<=PROPERTY_CALC' => 4), 
                 'norm' => array('>PROPERTY_CALC' => 4, '<PROPERTY_CALC' => 16),
                 'long' => array('>=PROPERTY_CALC' => 16),
    );
$defaultFilter = 'open';
if($filter = $_REQUEST['filter']) { 
    if(in_array($filter, array_keys($filters))) {
        $_SESSION['LIST_FILTER'] = $filter;
    }
}
if(!$_SESSION['LIST_FILTER']) {
    $_SESSION['LIST_FILTER'] = $defaultFilter;
}
$arResult['FILTER'] = $_SESSION['LIST_FILTER'];

$arFilter = Array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ACTIVE' => 'Y'); 
$arFilter = array_merge($arFilter, $filters[$_SESSION['LIST_FILTER']]);  
$arFilter['PROPERTY_PROJECT'] = $arParams["PROJECT"] ? $arParams["PROJECT"] : $projects; 
$arFilter = array_merge($USER->GetViewTasksFilter(), $arFilter); 
$res = CIBlockElement::GetList($sorts[$_SESSION['LIST_SORT']], 
                               $arFilter, 
                               false, 
                               array('nPageSize' => $arParams['COUNT']), 
                               array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", "DATE_CREATE"));
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if (strlen($arFields["DATE_CREATE"]) > 0) {
        $arFields["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($arFields["DATE_CREATE"], CSite::GetDateFormat()));
    }
    $arFields['PROPERTIES'] = $ob->GetProperties();
    $arFields['STATUS'] = $arFields['PROPERTIES']['STATUS']["VALUE_ENUM_ID"];
    $arFields['STATUS_TEXT'] = StatusHelper::getStr($arFields['STATUS']);
    $arResult['TASKS'][] = $arFields;
}
$arResult["NAV_STRING"] = $res->GetPageNavString();
 
$this->IncludeComponentTemplate();