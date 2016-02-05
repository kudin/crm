<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!$arParams['USER_ID']) {
    $arParams['USER_ID'] = CUser::GetID();
}
 
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arParams['USER_ID']);
if(!count($arResult['USERS'])) { 
    ShowError('Пользователь не найден');
    return;
}

/* programmer */

$res = CIBlockElement::GetList(array(),
                               array("IBLOCK_ID" => TASKS_IBLOCK_ID, 
                                     'ACTIVE' => 'Y',
                                     "PROPERTY_PROGRAMMER" => $arParams['USER_ID']), 
                               false, 
                               false,
                               array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_PROJECT', 'PROPERTY_CALC', 'PROPERTY_TRACKING',
                                     'PROPERTY_STATUS_DATE', 'PROPERTY_STATUS', 'PROPERTY_CALC_COMMENTS'));
while ($task = $res->GetNext()) { 
    if(!$task['PROPERTY_STATUS_ENUM_ID']) {
        $task['PROPERTY_STATUS_ENUM_ID'] = 0;
    } 
    switch ($task['PROPERTY_STATUS_ENUM_ID']) {
        case STATUS_LIST_WORK:
            $task['DETAIL_PAGE_URL'] = TASKS_LIST_URL . $task["PROPERTY_PROJECT_VALUE"] . '/' . $task['ID'] . '/';
            $date = new DateTime($task["PROPERTY_STATUS_DATE_VALUE"]);  
            $curdate = new DateTime();
            $diff = $date->diff($curdate); 
            $task['TIME'] = $diff->format('%H:%I:%S');
            $arResult['CURRENT_TASK'] = $task;  
        default: 
            $arResult['COUNTERS'][$task['PROPERTY_STATUS_ENUM_ID']]['TRACKING'] += $task['PROPERTY_TRACKING_VALUE'];
            $arResult['COUNTERS'][$task['PROPERTY_STATUS_ENUM_ID']]['CALC_COMMENTS'] += $task['PROPERTY_CALC_COMMENTS_VALUE'];
            $arResult['COUNTERS'][$task['PROPERTY_STATUS_ENUM_ID']]['COUNT']++;
            break;
    } 
} 
        
foreach($arResult['COUNTERS'] as $status => $arr) {
    switch ($status) {
        case STATUS_LIST_ACCEPT:
            $arResult['ALL_PROGR_COMPLETE_TASKS_CNT'] += $arr['COUNT'];
            break;
        case STATUS_LIST_CALC_REJECT:
            
            break;
        default: 
            $arResult['ALL_PROGR_TASKS_CNT'] += $arr['COUNT']; 
            break;
    } 
}
        

/* customer */

$res = CIBlockElement::GetList(array(),
                               array("IBLOCK_ID" => TASKS_IBLOCK_ID, 
                                     'ACTIVE' => 'Y',
                                     "PROPERTY_CUSTOMER" => $arParams['USER_ID'],
                                     'PROPERTY_STATUS' => array(STATUS_LIST_AGR_CALCED, STATUS_LIST_COMPLETE )),
                               false, 
                               false,
                               array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_STATUS'));
while ($task = $res->GetNext()) { 
    $arResult['CUSTOMERS_COUNTERS_SUMM']++;
    if(!$task['PROPERTY_STATUS_ENUM_ID']) {
        $task['PROPERTY_STATUS_ENUM_ID'] = 0;
    }
    $arResult['CUSTOMERS_COUNTERS'][$task['PROPERTY_STATUS_ENUM_ID']]['COUNT']++;    
} 
        
$this->IncludeComponentTemplate();