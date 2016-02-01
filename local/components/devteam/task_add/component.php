<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
if(!$arParams["PROJECT"]) {
    ShowError('Не передан обязательный параметр');
    return;
}

if(!$USER->hasRightsToViewProject($arParams["PROJECT"])){
    ShowError('У Вас нет прав на просмотр этого проекта');
    return;
} 

CModule::IncludeModule('iblock');

$arResult['USER_ID'] = CUser::GetID();

if($_REQUEST['addtask']) {
    $el = new CIBlockElement;
    $name = trim($_REQUEST['name']);
    $description = trim($_REQUEST['description']);  
    foreach ($_FILES['attach'] as $code => $values) { 
        foreach ($values as $key => $value) { 
            if($_FILES['attach']["tmp_name"][$key]) {
                $arFiles[$key][$code] = $value;
            } 
        } 
    }
    
    $priority = intval($_REQUEST['priority']);
    if(!in_array($priority, range(0, MAX_PRIORITY))) {
        $priority = DEFAULT_PRIORITY;
    }

    $programmer = $_REQUEST['PROGRAMMER'];
    $customer = $_REQUEST['CUSTOMER'] ? $_REQUEST['CUSTOMER'] : CUser::GetID();
 
    $arProjectArray = Array(
        "PROPERTY_VALUES" => array( 
            'PROGRAMMER' => $programmer,
            'PROJECT' => $arParams["PROJECT"],
            'FILES' => $arFiles,
            'PRIORITY' => $priority,
            'CUSTOMER' => $customer
        ),
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => TASKS_IBLOCK_ID,
        "NAME" => $name,
        "DETAIL_TEXT" => $description,
    ); 
    if ($newTaskId = $el->Add($arProjectArray)) {
        ToolTip::Add('Задача "' . $name . '" добавлена'); 
        $logger = new CrmLog('task');
        $logger->add(array($programmer, $customer), $newTaskId, 'add', $description);
        LocalRedirect(TASKS_LIST_URL . $arParams["PROJECT"] . '/'); 
    } else {
        $arResult['ERROR'] = $el->LAST_ERROR;
    } 
} 

 
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 'ID' => $arParams["PROJECT"]);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
 
if ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();  
    $arProps = $ob->GetProperties();
    $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE']; 
    if(!in_array($arResult['USER_ID'], $arResult['CUSTOMERS_IDS'])) {
        $arResult['CUSTOMERS_IDS'][] = $arResult['USER_ID'];
    } 
    $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];   
    $arResult['USERS'] = BitrixHelper::getUsersArrByIds(array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS']));
    $arResult['PROJECT'] = $arFields;

    $this->IncludeComponentTemplate();
} else { 
    ShowError('Такой проект не найден или доступ к нему запрещён'); 
}

