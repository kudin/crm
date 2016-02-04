<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
foreach(array('ID', 'PROJECT') as $code) {
    if(!$arParams[$code]) {
        ShowError('Не передан обязательный параметр');
        return;
    }
}

if(!$USER->hasRightsToViewTask($arParams["ID"])) {
    ShowError('У Вас нет прав на просмотр этой задачи');
    return;
}
 
if(!$arParams['DATE_FORMAT']) {
    $arParams['DATE_FORMAT'] = 'j F в H:i';
}

CModule::IncludeModule('iblock');

$logger = new CrmLog('task');  

/* project */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "CREATED_BY");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 'ID' => $arParams['PROJECT']);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter); 
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect); 
if ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties(); 
    $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE'];
    $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];
    $arResult['PROJECT'] = $arFields;
} else { 
    ShowError('Проект не найден');
    return;
} 


/* task */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", 'DETAIL_TEXT', "DATE_CREATE", "CREATED_BY");
$arFilter = Array(
    "IBLOCK_ID" => TASKS_IBLOCK_ID,
    'ACTIVE' => 'Y',  
    'ID' => $arParams['ID']);
$userFilter = $USER->GetViewTasksFilter();
$arFilter = array_merge($userFilter, $arFilter); 
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect); 
if ($ob = $res->GetNextElement()) {
    $arResult['TASK'] = $ob->GetFields();
    $arResult['TASK']['PROPS'] = $ob->GetProperties();
    if($arResult['TASK']['PROPS']['FILES']['VALUE']) { 
        foreach($arResult['TASK']['PROPS']['FILES']['VALUE'] as $fileId) {
            $files[] = CFile::GetFileArray($fileId);
        }
        $arResult['TASK']['PROPS']['FILES']['VALUE'] = $files;
    }
    if (strlen($arResult['TASK']["DATE_CREATE"]) > 0) {
        $arResult['TASK']["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($arResult['TASK']["DATE_CREATE"], CSite::GetDateFormat()));
    } 
    $arResult['STATUS'] = $arResult['TASK']['PROPS']['STATUS']["VALUE_ENUM_ID"]; 
    $arResult['STATUS_TEXT'] = StatusHelper::getStr($arResult['STATUS']);
} else {
    ShowError('Ошибка доступа к задаче');
    return;
}


/* rights */

$arResult['USER_ID'] = $USER->GetID();  
if(($arResult['USER_ID'] == $arResult['TASK']['PROPS']['CUSTOMER']['VALUE']) && 
   ($arResult['USER_ID'] == $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE'])) {
    $arResult['IS_PROGRAMMER_AND_CUSTOMER'] = true;
} elseif ($arResult['USER_ID'] == $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']) {
    $arResult['IS_PROGRAMMER'] = true;
} elseif(in_array($arResult['USER_ID'], $arResult['CUSTOMERS_IDS'])) {
    $arResult['IS_CUSTOMER'] = true; 
} 

$arResult['CAN_EDIT'] = (($arResult['USER_ID'] == $arResult['TASK']['PROPS']['CUSTOMER']['VALUE']) || $USER->IsAdmin());


/* comments */

if ($_REQUEST['add_comment']) {
    $_REQUEST['commment'] = strip_tags($_REQUEST['commment']); 
    if (!$_REQUEST['comment']) {
        ToolTip::AddError('Не введён комментарий'); 
    } else {
        $el = new CIBlockElement;
        if ($commentId = $el->Add(
            array("MODIFIED_BY" => $USER->GetID(),
                  "IBLOCK_SECTION_ID" => false,
                  "IBLOCK_ID" => COMMENTS_IBLOCK_ID,
                  "DATE_ACTIVE_FROM" => ConvertTimeStamp(false, 'FULL'),
                  "PROPERTY_VALUES" => array('TASK' => $arParams['ID']),
                  "NAME" => TruncateText(strip_tags($_REQUEST['comment']), 100),
                  "ACTIVE" => "Y",
                  "PREVIEW_TEXT" => TruncateText($_REQUEST['comment'], COMMENT_MAX_LENGHT)))) {
            crmEntitiesHelper::recalcCommentsCnt($arParams['ID']);  
            $logger->add(array($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'], $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']), 
                        $arParams['ID'], 
                        'comment',
                        $_REQUEST['comment']); 
            crmEntitiesHelper::RecalcLastCommentDateTime($arParams['ID']);
            LocalRedirect(TASKS_LIST_URL . $arParams['PROJECT'] . '/' . $arParams['ID'] . '/#comment' . $commentId);
        } else {
            ToolTip::AddError($el->LAST_ERROR);
        }
    }
}

$created_by = array($arResult['TASK']['CREATED_BY']);
$res = CIBlockElement::GetList(
    array("DATE_ACTIVE_FROM" => "ASC"), 
    array("PROPERTY_TASK" => $arParams['ID'], 
          "IBLOCK_ID" => COMMENTS_IBLOCK_ID, 
          "ACTIVE" => "Y"), 
    false,
    false,  
    array('DATE_ACTIVE_FROM', 'PREVIEW_TEXT', 'CREATED_BY', 
          'ID', 'IBLOCK_ID', 'DATE_CREATE', 'PROPERTY_STATUS', 'PROPERTY_CALC')); 
while ($ar_fields = $res->GetNext()) {  
    $created_by[] = $ar_fields['CREATED_BY'];
    $ar_fields['STATUS'] = $ar_fields["PROPERTY_STATUS_ENUM_ID"];
    $arComments[$ar_fields['ID']] = $ar_fields['STATUS']; 
    if (strlen($ar_fields["DATE_CREATE"]) > 0) {
        $ar_fields["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($ar_fields["DATE_CREATE"], CSite::GetDateFormat()));
    }
    $arResult['COMMENTS'][] = $ar_fields; 
}


/* users */

$usersIds = array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS'], $created_by);
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($usersIds);  


/* actions */  

$newStatus = NULL;

if($action = $_REQUEST['action']) {
    if($arResult['IS_PROGRAMMER'] || $arResult['IS_PROGRAMMER_AND_CUSTOMER']) {
        switch ($action) { 

            /* comments */
            
            case 'cancel_status':
                $commentId = intval($_REQUEST["id"]);
                if($arComments[$commentId] != false && in_array($commentId, array_keys($arComments))) {    
                    CIBlockElement::SetPropertyValuesEx($commentId, COMMENTS_IBLOCK_ID, array('CALC' => 0, 'STATUS' => false)); 
                }
                break; 
            case 'calccomment': 
                $commentId = intval($_REQUEST["commentId"]);
                $time = formatTime($_REQUEST["timeComment"]);
                if($arResult['USER_ID'] == $arResult['TASK']['PROPS']['CUSTOMER']['VALUE']) { 
                    $commentStatus = STATUS_COMMENT_CONFIRM;
                    $recalcTime = true; 
                } else { 
                    $commentStatus = STATUS_COMMENT_CALCED;
                }
                if(in_array($commentId, array_keys($arComments)) && 
                        $time && 
                        ($arComments[$commentId] == false)) {  
                    CIBlockElement::SetPropertyValuesEx($commentId, COMMENTS_IBLOCK_ID, array('CALC' => $time, 'STATUS' => $commentStatus)); 
                }
                break; 
            
            /* tasks */
                
            case 'closeTask':
                if(($arResult['STATUS'] == STATUS_LIST_COMPLETE) && ($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS'])) {
                    $newStatus = STATUS_LIST_ACCEPT;
                }
                break;
            case 'start':
                if(($arResult['STATUS'] == STATUS_LIST_AGR_CALCED) && ($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS'])) {
                    $newStatus = STATUS_LIST_WORK;
                }
                if(in_array($arResult['STATUS'], array(STATUS_LIST_CALC_AGRED, STATUS_LIST_PAUSE, STATUS_LIST_COMPLETE, STATUS_LIST_REJECT, STATUS_LIST_ACCEPT, STATUS_LIST_AGR_CALCED))) {
                    $newStatus = STATUS_LIST_WORK;
                }
                if($newStatus == STATUS_LIST_WORK) {
                    $res = CIBlockElement::GetList(array(), 
                                                   array("IBLOCK_ID" => TASKS_IBLOCK_ID,
                                                         'ACTIVE' => 'Y',  
                                                         '!ID' => $arParams['ID'],
                                                         "PROPERTY_PROGRAMMER" => CUser::GetID(),
                                                         "PROPERTY_STATUS" => STATUS_LIST_WORK),
                                                   false, false, array('ID')); 
                    while($taskArr = $res->Fetch()) {
                        CIBlockElement::SetPropertyValuesEx($taskArr['ID'], TASKS_IBLOCK_ID, array('STATUS' => STATUS_LIST_PAUSE)); 
                    } 
                }
                break; 
            case 'stop':
                if($arResult['STATUS'] == STATUS_LIST_WORK) {
                    $newStatus = STATUS_LIST_PAUSE;
                }
                break;
            case 'complete':
                if(in_array($arResult['STATUS'], array(STATUS_LIST_WORK, STATUS_LIST_PAUSE, STATUS_LIST_REJECT))) {
                    if($arResult['IS_PROGRAMMER_AND_CUSTOMER']) {
                        $newStatus = STATUS_LIST_ACCEPT;
                    } else {
                        $newStatus = STATUS_LIST_COMPLETE;
                    }
                }
                break;
            case 'docalc':  
                if($arResult['STATUS'] == false || $arResult['STATUS'] == STATUS_LIST_CALC_REJECT) {
                    if($time = formatTime($_REQUEST['time'])) {
                        CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, array('CALC' => $time, 'CALC_COMMENTS' => $time));
                        if($arResult['IS_PROGRAMMER_AND_CUSTOMER']) {
                            $newStatus = STATUS_LIST_CALC_AGRED;
                        } else {
                            $newStatus = STATUS_LIST_AGR_CALCED;
                        }
                    } else {
                        ToolTip::AddError('Введено некорректное значение оценки');
                    }
                }
                break;
            case 'fact': 
                if($arResult['STATUS'] == false || $arResult['STATUS'] == STATUS_LIST_CALC_REJECT) {
                    CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, array('CALC' => 0, 'CALC_COMMENTS' => 0));
                    $newStatus = STATUS_LIST_AGR_CALCED;
                }
                break;
            default:
                break;
        }
    } elseif($arResult['IS_CUSTOMER']) {
        switch ($action) {
            
            /* comments */
            
            case 'commentStatus':
                $commentId = intval($_REQUEST["commentId"]);
                if($_REQUEST['reject']) {
                    $commentStatus = STATUS_COMMENT_REJECT;
                } elseif($_REQUEST['accept']) {
                    $commentStatus = STATUS_COMMENT_CONFIRM;
                } 
                if($commentStatus && 
                    in_array($commentId, array_keys($arComments)) && 
                    $arComments[$commentId] == STATUS_COMMENT_CALCED) {
                        CIBlockElement::SetPropertyValuesEx($commentId, COMMENTS_IBLOCK_ID, array('STATUS' => $commentStatus));
                }
                break;
            
            /* tasks */
            case 'start':
                    $newStatus = STATUS_LIST_WORK;
                break;
            case 'calcAgr':
                if($arResult['STATUS'] == STATUS_LIST_AGR_CALCED) {
                    $newStatus = STATUS_LIST_CALC_AGRED;
                }
                break;
            case 'calcReject': 
                if($arResult['STATUS'] == STATUS_LIST_AGR_CALCED) {
                    $newStatus = STATUS_LIST_CALC_REJECT;
                }
                break;
            case 'rejectTask':
                if($arResult['STATUS'] == STATUS_LIST_COMPLETE) { 
                    $newStatus = STATUS_LIST_REJECT;
                }
                break;
            case 'closeTask':
                if($arResult['STATUS'] == STATUS_LIST_COMPLETE) { 
                    $newStatus = STATUS_LIST_ACCEPT;
                }
                break;
            case 'getnewcalc':
                if($arResult['STATUS'] == STATUS_LIST_CALC_REJECT)
                    $newStatus = 0;
                break;
            default:
                break;
        } 
    }
    
    if(!is_null($newStatus)) {
        CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, array('STATUS' => $newStatus));  
        if(!in_array($newStatus, array(STATUS_LIST_PAUSE, STATUS_LIST_WORK))) { 
            $logger->add(array($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'], $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']),
                        $arParams['ID'], 'status', 
                        StatusHelper::getStr($newStatus) . ' #' . $arResult['TASK']['ID'] . ' ' . $arResult['TASK']['NAME']);
        }
    }

    if($commentStatus == STATUS_COMMENT_CONFIRM || $newStatus == STATUS_LIST_AGR_CALCED) {
        crmEntitiesHelper::recalcTaskTime($arParams['ID']); 
    }

    LocalRedirect($APPLICATION->GetCurDir());
}   


/* edit task */

$new_task = $_REQUEST["new_task"];
if($arResult['CAN_EDIT'] && isset($new_task)) {
    $el = new CIBlockElement; 
    $updated = $el->Update($arParams['ID'], array("DETAIL_TEXT" => $new_task, "NAME" => $_REQUEST['NAME_NEW']));
    if($updated) {
        $propsUpdate['PRIORITY'] = validatePriority($_REQUEST['priority']);
        $calc = formatTime($_REQUEST['NEW_CALC']);
        if($calc != $arResult['TASK']['PROPS']['CALC']['VALUE']) {
            $propsUpdate['CALC'] = $calc;
        }
        if($newCustomer = intval($_REQUEST['CUSTOMER_NEW'])) {
            if(in_array($newCustomer, $arResult['CUSTOMERS_IDS'])) {
                $propsUpdate['CUSTOMER'] = $newCustomer;
            }
        } 
        if($newProgrammer = intval($_REQUEST['PROGRAMMER_NEW'])) {
            if(in_array($newProgrammer, $arResult['PROGRAMERS_IDS'])) {
                $propsUpdate['PROGRAMMER'] = $newProgrammer;
            }
        }    
        $deletefiles = $_REQUEST['deletefile'];
        if(is_array($deletefiles) && count($deletefiles)) { 
            foreach ($deletefiles as $delFileID) {  
                // удалить файл
            }
        }
        CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, $propsUpdate);
        $logger->add(array($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'], $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']), $arParams['ID'], 'edit', $new_task, true);
        if(isset($propsUpdate['CALC'])) {
            crmEntitiesHelper::recalcTaskTime($arParams['ID']); 
        }
    } else {
        ToolTip::AddError($el->LAST_ERROR);
    }
    LocalRedirect($APPLICATION->GetCurDir()); 
}


/* edit comment */

if($editComment = intval($_REQUEST['editcomment'])) {
    $arResult['EDIT_COMMENT'] = $editComment;
}

if($_REQUEST['cancel_edit_comment']) {
    LocalRedirect($APPLICATION->GetCurDir() . '#comment' . intval($_REQUEST['id']));
}

if(($_REQUEST['edit_comment']) && ($id = intval($_REQUEST['id']))) {
    foreach($arResult['COMMENTS'] as $comment) {
        if($comment['ID'] == $id) {
            if($comment['CREATED_BY'] == $arResult['USER_ID']) { 
                $el = new CIBlockElement;  
                $res = $el->Update($id, array("PREVIEW_TEXT" => TruncateText($_REQUEST['comment_text'], COMMENT_MAX_LENGHT)));  
                $logger->add(array($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'], $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']), 
                            $arParams['ID'], 'edit_comment', $_REQUEST['comment_text']);
            } else {
                ToolTip::AddError('Ошибка доступа к комментарию');
            }
            break;
        }
    }
    LocalRedirect($APPLICATION->GetCurDir() . '#comment' . intval($_REQUEST['id']));  
}


/* delete comment */

if($delete_comment = $_REQUEST['delete_comment']) {
    foreach($arResult['COMMENTS'] as $comment) {
        if($comment['ID'] == $delete_comment) {
            if($comment['CREATED_BY'] == $arResult['USER_ID']) {  
                $el = new CIBlockElement;  
                $res = $el->Update($delete_comment, array("ACTIVE" => 'N'));  
                $logger->add(array($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'], $arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']), 
                            $arParams['ID'], 'delete_comment', strip_tags($comment['~PREVIEW_TEXT'])); 
                ToolTip::Add('Комментарий удалён');
                crmEntitiesHelper::RecalcLastCommentDateTime($arParams['ID']);
            } else { 
                ToolTip::AddError('Ошибка доступа к комментарию');
            }
            break;
        }
    }
    LocalRedirect($APPLICATION->GetCurDir());  
}


/* tracking time */

$res = CIBlockElement::GetList(
    array("ID" => "ASC"), 
    array("PROPERTY_TASK" => $arParams['ID'], "IBLOCK_ID" => TRACKING_IBLOCK_ID, "ACTIVE" => "Y"), 
    false,
    false,  
    array('DETAIL_TEXT', 'ID', 'DATE_CREATE', 'PROPERTY_HOURS')); 
while ($ar_fields = $res->GetNext()) {  
    $arResult['TRACKING'][] = $ar_fields;
}


$logger->view($arParams['ID']);

$this->IncludeComponentTemplate();