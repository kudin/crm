<?php
include 'constants.php';
include 'tools/functions.php';

CModule::AddAutoloadClasses("", array("ToolTip" => "/local/php_interface/tools/classes/tooltip.php", 
                                      "BitrixHelper" => "/local/php_interface/tools/classes/bitrixhelper.php",
                                      "StatusHelper" => "/local/php_interface/tools/classes/statushelper.php"));

class CrmUser extends CUser {

    /* projects handlers */
    
    public function hasRigthsToAddProject() {
         return parent::IsAdmin();
    }

    public function hasRigthsToDeleteProject() {
         return parent::IsAdmin();
    }

    public function hasRigthsToEditProject() {
         return parent::IsAdmin();
    }


    /* tasks handlers */
    
    public function hasRigthsToAddTask($arFields) {
        if(parent::IsAdmin()) {
            return true;
        }  
        return $this->iAmACustomerInProject($arFields["PROPERTY_VALUES"]["PROJECT"]) || 
               $this->iAmAProgrammerInProject($arFields["PROPERTY_VALUES"]["PROJECT"]);
    }
    
    public function hasRigthsToDeleteTask($arFields) {
        if(parent::IsAdmin()) {
            return true;
        }
        if($this->iAmAProgrammerInTask($arFields['ID'])) {
            return true;
        } 
        return false;
    }    
    
    public function hasRigthsToEditTask($arFields) {
        if(parent::IsAdmin()) {
            return true;
        } 
        
    }

    
    /* view rights projects */
    
    public function hasRightsToViewProject($projectId) {
        if(parent::IsAdmin()) {  
            return true; 
        } 
        return $this->iAmACustomerInProject($projectId) || $this->iAmAProgrammerInProject($projectId);
    }

    public function iAmAProgrammerInProject($projectId) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 
                                             'ID' => $projectId,
                                             'PROPERTY_PROGRAMMER' => parent::GetID()),
                                       false, 
                                       false, 
                                       array("ID", "IBLOCK_ID")); 
        if ($res->GetNext()) {
            return true;
        }
        return false;
    }
    
    public function iAmACustomerInProject($projectId) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 
                                             'ID' => $projectId,
                                             'PROPERTY_CUSTOMER' => parent::GetID()),
                                       false, 
                                       false, 
                                       array("ID", "IBLOCK_ID")); 
        if ($res->GetNext()) {
            return true;
        }
        return false;
    }

    
    /* view rights tasks */
    
    public function hasRightsToViewTask($taskId) {
        if(parent::IsAdmin()) {  
            return true; 
        } 
        if($this->iAmACustomerInTask($taskId) || $this->iAmAProgrammerInTask($taskId)) {
            return true;
        } 
        $projectId = crmEntitiesHelper::GetProjectIdByTask($taskId);
        if($this->iAmACustomerInProject($projectId)) {
            return true;
        }
        return false;
    } 
            
    public function iAmAProgrammerInTask($taskId) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => TASKS_IBLOCK_ID, 
                                             'ID' => $taskId,
                                             'PROPERTY_PROGRAMMER' => parent::GetID()),
                                       false, 
                                       false, 
                                       array("ID", "IBLOCK_ID")); 
        if ($res->GetNext()) {
            return true;
        }
        return false;
    }

    public function iAmACustomerInTask($taskId) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => TASKS_IBLOCK_ID, 
                                             'ID' => $taskId,
                                             'CREATED_BY' => parent::GetID()),
                                       false, 
                                       false, 
                                       array("ID", "IBLOCK_ID")); 
        if ($res->GetNext()) {
            return true;
        }
        return false;
    }


    /* Вернёт фильтр, который вытянет проекты, которые можно видеть пользователю
       то что возвращает этот метод нужно мержить к фильтру везде где тянем проект или проекты
    */
    public function GetViewProjectsFilter() {
        if(parent::IsAdmin()) {  
            return array(); 
        }
        $arFilter = array(
                array(
                    "LOGIC" => "OR",
                    array("PROPERTY_PROGRAMMER" => parent::GetID()),
                    array("PROPERTY_CUSTOMER" => parent::GetID())
                )
        );
        return $arFilter;
    }
    
    public function GetViewTasksFilter() {
        if(parent::IsAdmin()) {  
            return array(); 
        }
        $arFilter = array(  
                array(
                    "LOGIC" => "OR",
                    array("CREATED_BY" => parent::GetID()),
                    array("PROPERTY_PROGRAMMER" => parent::GetID())
                )
        );
        return $arFilter;
    }

}


foreach(array('Add', 'Delete', 'Update') as $action) {
    AddEventHandler("iblock", "OnBeforeIBlockElement" . $action, array("RightsHandler", "OnBeforeIBlockElement" . $action));
}
 
class RightsHandler { 
    function __callStatic($name, $arguments) {
        switch ($name) {
            case 'OnBeforeIBlockElementAdd':
                $action = 'Add';
                break;
            case 'OnBeforeIBlockElementDelete': 
                $action = 'Delete';
                break;
            case 'OnBeforeIBlockElementUpdate': 
                $action = 'Edit';
                break;
            default:
                return;
                break;
        }

        $arFields = $arguments[0]; 
        if(!is_array($arFields)) {
            CModule::IncludeModule('iblock');
            $res = CIBlockElement::GetByID($arFields);
            if($ar_res = $res->GetNext()){
                $arFields = $ar_res;
            } 
        } 
        
        switch ($arFields["IBLOCK_ID"]) {
            case PROJECTS_IBLOCK_ID:
                $enitity = 'Project';
                break;
            case TASKS_IBLOCK_ID:
                $enitity = 'Task';
                break;
            default:
                return;
                break;
        }
            
        $method = 'hasRigthsTo' . $action . $enitity; 
        global $USER, $APPLICATION;
        if(method_exists($USER, $method)) {
            if(!$USER->$method($arFields)) {
                $APPLICATION->throwException("У вас недостаточно прав на эту операцию");
                return false;
            }
        }
    }
}


class crmEntitiesHelper {

    public static function recalcTaskTime($taskId) {
        CModule::IncludeModule('iblock');
        $summ = 0;
        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_CALC");
        $arFilter = Array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ACTIVE' => 'Y', 'ID' => $taskId); 
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect); 
        if ($item = $res->GetNext()) {
            $summ += $item['PROPERTY_CALC_VALUE']; 
            $commentres = CIBlockElement::GetList(array(), 
                                                  array("PROPERTY_TASK" => $taskId, 
                                                        "IBLOCK_ID" => COMMENTS_IBLOCK_ID, 
                                                        "ACTIVE" => "Y",
                                                        "PROPERTY_STATUS" => STATUS_COMMENT_CONFIRM), 
                                                  false, 
                                                  false, 
                                                  array('ID', 'IBLOCK_ID', 'PROPERTY_STATUS', 'PROPERTY_CALC')); 
            while ($comment = $commentres->GetNext()) {
                $summ += $comment['PROPERTY_CALC_VALUE']; 
            }
            CIBlockElement::SetPropertyValuesEx($taskId, TASKS_IBLOCK_ID, array('CALC_COMMENTS' => $summ)); 
        }
        return $summ; 
    }
    
    public static function GetProjectIdByTask($taskId) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(array(),
                                       array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ID' => $taskId),
                                       false, 
                                       false, 
                                       array("PROPERTY_PROJECT", "IBLOCK_ID")); 
        if ($task = $res->GetNext()) {
            return $task["PROPERTY_PROJECT_VALUE"];
        } 
    }
    
    public static function isProject($id) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>PROJECTS_IBLOCK_ID, 'ID' => $id), false, false, Array("ID", "IBLOCK_ID"));
        if($ob = $res->GetNext()) { 
            return true;
        }
        return false;
    }
    
    public static function isTask($id) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>TASKS_IBLOCK_ID, 'ID' => $id), false, false, Array("ID", "IBLOCK_ID"));
        if($ob = $res->GetNext()) { 
            return true;
        }
        return false;
    }

}


AddEventHandler("main", "OnBeforeProlog", "ChangeCUserToCrmUser");

function ChangeCUserToCrmUser() {
    global $USER;
    $USER = new CrmUser();
}