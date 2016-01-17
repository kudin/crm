<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$action = trim($_REQUEST['action']);
if(!$action) {  
    die(json_decode(array('error' => 'не передан обязательный параметр')));
} 

$id = $_REQUEST['id'];
if(!is_array($id)) {
    $id = intval($id);
}

CModule::IncludeModule('iblock');

switch ($action) {
    case 'deleteProject': 
        if(crmEntitiesHelper::isProject($id)) {
            CIBlockElement::Delete($id); // всё нормально, вся проверка прав на удаление есть в обработчиках
        } 
        break;
    case 'deleteTasks':
        if(!is_array($id)) {
            $id = array($id);
        }
        foreach ($id as $taskId) {
            if(crmEntitiesHelper::isTask($taskId)) {
                if(!CIBlockElement::Delete($taskId)) {
                    $errIds[] = $taskId;
                }
            } 
        }  
        if(count($errIds)) { 
            echo json_encode(array('error' => 'Не удалось удалить задачу ' . implode(', ', $errIds)));
        } else {
            echo json_encode(array('ok'));
        }
        break;
    default:
        die(json_encode(array('error' => 'передан неверный параметр')));
        break;
}