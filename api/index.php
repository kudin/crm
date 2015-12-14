<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$id = intval($_REQUEST['id']);
$action = trim($_REQUEST['action']);
if(!($id && $action)) {  
    die(json_decode(array('error' => 'не передан обязательный параметр')));
}

// это всё чутка ужасно ... 

CModule::IncludeModule('iblock');

function isProject() {
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>PROJECTS_IBLOCK_ID), false, false, Array("ID", "IBLOCK_ID"));
    if($ob = $res->GetNext()) { 
        return true;
    }
    return false;
}

switch ($action) {
    case 'deleteProject': 
        if(isProject($id)) {
            CIBlockElement::Delete($id);
        } 
        break; 
    default:
        die(json_decode(array('error' => 'передан неверный параметр')));
        break;
}