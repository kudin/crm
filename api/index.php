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
    case 'deleteTime':
        $track = intval($_REQUEST['track']);
        if(!$track) {
            die(json_encode(array('error' => 'Не передан обязательный параметр')));
        }
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => TRACKING_IBLOCK_ID, 
                                             'ID' => $track,
                                             'CREATED_BY' => CUser::GetID()),
                                       false, 
                                       false, 
                                       array("ID", "IBLOCK_ID", 'PROPERTY_TASK')); 
        if ($result = $res->GetNext()) { 
            CIBlockElement::Delete($track); 
            $summ = crmEntitiesHelper::recalcTaskTracking($result['PROPERTY_TASK_VALUE']);
            die(json_encode(array('summ' => $summ)));
        } else {
            die(json_encode(array('error' => 'Ошибка доступа к таймингу')));
        }
        break;
    case 'trackTime':
        $task = intval($_REQUEST['task']);
        if($USER->iAmAProgrammerInTask($task)) {  
            $desc = trim(TruncateText($_REQUEST['desc'], 250));
            $time = formatTime($_REQUEST['h'], MAX_TRACKING_TIME); 
            if(!$time) {
                die(json_encode(array('error' => 'Введено неверное время')));
            } 
            $el = new CIBlockElement;  
            if($newId = $el->Add(array(
                "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "SHORT"),
                "MODIFIED_BY"       => $USER->GetID(),  
                "IBLOCK_SECTION_ID" => false, 
                "IBLOCK_ID"         => TRACKING_IBLOCK_ID, 
                "NAME"              => (strlen($desc) ? $desc : 'Без названия') . ' (' . $time . 'ч.)',
                "DETAIL_TEXT"       => $desc,
                "ACTIVE"            => "Y",
                "PROPERTY_VALUES"   => array("HOURS" => $time, 
                                             "TASK" => $task)))) {
                $summ = crmEntitiesHelper::recalcTaskTracking($task);
                die(json_encode(array('ok' => $newId, 'summ' => $summ)));
            } else { 
                die(json_encode(array('error' => $el->LAST_ERROR)));
            } 
        } else {
            die(json_encode(array('error' => 'Ошибка доступа к задаче')));
        } 
        break;
    default:
        die(json_encode(array('error' => 'передан неверный параметр')));
        break;
}