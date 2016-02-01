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

$logger = new CrmLog();  


/* projects */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", "DETAIL_PICTURE");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$res->NavStart();
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties(); 
    if($arParams["PROJECT"] && ($arParams["PROJECT"] == $arFields['ID'])) {
        $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE'];
        $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];  
    }
    foreach(array('PROGRAMMER', 'CUSTOMER') as $propCode) {
        foreach ($arProps[$propCode]['VALUE'] as $userid) {
            $arResult['ALL_USERS'][] = $userid;
        } 
    } 
    if(!$arParams["PROJECT"] && $res->NavRecordCount == 1) {
        LocalRedirect(TASKS_LIST_URL . $arFields['ID'] . '/');
    } 
    if($arFields['DETAIL_PICTURE']) {
        $arFields['DETAIL_PICTURE'] = CFile::ResizeImageGet($arFields['DETAIL_PICTURE'], array('width'=>60, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);                
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
$arResult['ALL_USERS'] = array_unique($arResult['ALL_USERS']); 
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arResult['ALL_USERS']);
if($arParams['PROJECT']) { 
    $currentProjectUsers = array();
    $currentProjectUsers = array_merge($currentProjectUsers, $arResult['CUSTOMERS_IDS']);
    $currentProjectUsers = array_merge($currentProjectUsers, $arResult['PROGRAMERS_IDS']);
    $arResult['ALL_USERS'] = array_unique($currentProjectUsers);
}


/* tasks */

if($sortOrder = $_REQUEST['order']) {
    if(in_array($sortOrder, array('asc', 'desc'))) {
        $_SESSION['LIST_SORT_ORDER'] = $sortOrder;
    }
}
if(!$_SESSION['LIST_SORT_ORDER']) {
    $_SESSION['LIST_SORT_ORDER'] = 'desc';
}
$arResult['SORT_ORDER'] = $_SESSION['LIST_SORT_ORDER'];

$sorts = array('date' => 'ID',
               'name' => 'NAME',
               'priority' => 'PROPERTY_PRIORITY',
               'calc' => 'PROPERTY_CALC_COMMENTS',
               'project' => 'PROPERTY_PROJECT',
               'ispolnitel' => 'PROPERTY_PROGRAMMER',
               'comments' => 'PROPERTY_COMMNETS_CNT');
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
                 'open' => array('!PROPERTY_STATUS' => array(STATUS_LIST_ACCEPT, STATUS_LIST_CALC_REJECT)),
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
                 'long' => array('>=PROPERTY_CALC' => 16));
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

$filters2 = array('all' => array(),
                  'my' => array("PROPERTY_PROGRAMMER" => $USER->GetID()),
                  'not_me' => array("!PROPERTY_PROGRAMMER" => $USER->GetID())); 
foreach($arResult['ALL_USERS'] as $userId) {
    $filters2[$userId] = array("PROPERTY_PROGRAMMER" => $userId);
}

if($filter2 = $_REQUEST['filter2']) {
    if(in_array($filter2, array_keys($filters2))) {
        $_SESSION['LIST_FILTER2'] = $filter2;
    }
}
if(!$_SESSION['LIST_FILTER2']) {
    $_SESSION['LIST_FILTER2'] = 'my';
}
$arResult['FILTER2'] = $_SESSION['LIST_FILTER2'];

$arFilter = Array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ACTIVE' => 'Y');  
$arFilter['PROPERTY_PROJECT'] = $arParams["PROJECT"] ? $arParams["PROJECT"] : $projects; 
$statisticFilter = $arFilter = array_merge($USER->GetViewTasksFilter(), $arFilter);
$arFilter = array_merge($arFilter, $filters[$_SESSION['LIST_FILTER']]); 
$arFilter = array_merge($arFilter, $filters2[$_SESSION['LIST_FILTER2']]); 
$res = CIBlockElement::GetList(array($sorts[$_SESSION['LIST_SORT']] => $_SESSION['LIST_SORT_ORDER']), 
                               $arFilter,
                               false,
                               array('nPageSize' => $arParams['COUNT']), 
                               array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", "DATE_CREATE", "CREATED_BY"));
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if (strlen($arFields["DATE_CREATE"]) > 0) {
        $arFields["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($arFields["DATE_CREATE"], CSite::GetDateFormat()));
    }
    $arFields['PROPERTIES'] = $ob->GetProperties();  
    $arFields['NOT_VIEWED'] = $logger->isNotViewed($arFields['ID']); 
    $arFields['NEW_COMMENTS'] = $logger->getNewCommentsCnt($arFields['ID']);  
    $arFields['NEW_STATUS'] = $logger->getStatusField($arFields['ID']);
    $arFields['STATUS'] = $arFields['PROPERTIES']['STATUS']["VALUE_ENUM_ID"];
    $arFields['STATUS_TEXT'] = StatusHelper::getStr($arFields['STATUS']);
    $arResult['TASKS'][] = $arFields;  
}
$arResult["NAV_STRING"] = $res->GetPageNavString(); 
$arResult['USER_ID'] = CUser::GetID();


/* statistic */

$arResult['ALL_TASK_TIME'] = $arResult['TASK_CNT'] = $arResult['ACCEPTED_TASK_TIME'] = $arResult['ACCEPTED_TASK_CNT'] = 0;
$statisticFilter['!PROPERTY_STATUS'] = STATUS_LIST_REJECT;
$res = CIBlockElement::GetList(array(), $statisticFilter, false, array(), array("ID", "PROPERTY_CALC", "PROPERTY_STATUS", "IBLOCK_ID"));
while ($item = $res->Fetch()) {
    if($item["PROPERTY_STATUS_ENUM_ID"] == STATUS_LIST_ACCEPT) { 
        $arResult['ACCEPTED_TASK_TIME'] += $item["PROPERTY_CALC_VALUE"];
        $arResult['ACCEPTED_TASK_CNT'] += 1; 
    }
    $arResult['ALL_TASK_TIME'] += $item["PROPERTY_CALC_VALUE"];
    $arResult['TASK_CNT'] += 1;
}
$arResult['PERCENTS_CNT'] = intval(($arResult['ACCEPTED_TASK_CNT'] / $arResult['TASK_CNT']) * 100);
$arResult['PERCENTS_TIME'] = intval(($arResult['ACCEPTED_TASK_TIME'] / $arResult['ALL_TASK_TIME']) * 100);


$this->IncludeComponentTemplate();