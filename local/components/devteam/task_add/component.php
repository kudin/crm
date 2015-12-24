<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
if(!$arParams["PROJECT"]) {
    ShowError('Не передан обязательный параметр');
    die();
}

CModule::IncludeModule('iblock');

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
    $arProjectArray = Array(
        "PROPERTY_VALUES" => array( 
            'PROGRAMMER' => $_REQUEST['PROGRAMMER'],
            'PROJECT' => $arParams["PROJECT"],
            'FILES' => $arFiles
        ),
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => TASKS_IBLOCK_ID,
        "NAME" => $name,
        "DETAIL_TEXT" => $description,
    );
    if ($el->Add($arProjectArray)) {
        ToolTip::Add('Задача "' . $name . '" добавлена');
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
    $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];  
    $rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                               array('ACTIVE'=>'Y',
                                     'ID' => implode(' | ', array_unique(array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS'])))), 
                               array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME')) );    
    while($arUser = $rsUsers->Fetch()) { 
        $arResult['USERS'][$arUser['ID']] = $arUser;
    }
 
    $arResult['PROJECT'] = $arFields;
     
    $this->IncludeComponentTemplate();
} else { 
        ShowError('Такой проект не найден или доступ к нему запрещён'); 
}



