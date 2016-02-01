<?php
include 'constants.php';
include 'tools/functions.php'; 

foreach(array('ToolTip', 'CrmLog', 'BitrixHelper', 'StatusHelper') as $className) {
    $arrClasses[$className] = "/local/php_interface/tools/classes/" . strtolower($className). ".php";
}
CModule::AddAutoloadClasses("", $arrClasses);

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
        return in_array(crmEntitiesHelper::GetProjectIdByTask($taskId), $this->getMyProjects()); 
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
    
    private function getMyProjects() {
        return crmEntitiesHelper::getProjectsByThisUser();
    }
    
    public function GetViewTasksFilter() {
        if(parent::IsAdmin()) {  
            return array(); 
        }
        $arFilter = array('PROPERTY_PROJECT' => $this->getMyProjects()); 
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
            } elseif($action == 'Delete') {
                $log = new CrmLog();
                $log->delete($arFields['ID']);
            }
        }
    }
}

class CrmConfig { 

    private static $defaultConf = array(
        'show_project_logo_in_list' => true,
        'show_project_logo_in_titile' => true,
        'project_icon_click_href' => 'task',
    );

    function __construct() {
        session_start();
        if(!$_SESSION['CRM_CONFIG']) {
            $this->read();
        }
    }

    public function get($name) {
        return $_SESSION['CRM_CONFIG'][$name];
    } 

    public function getBool($name) { 
        return $this->get($name) == 'Y';
    }

    public function set($name, $value) {
        if(in_array($name, array_keys(self::$defaultConf))) { 
            $_SESSION['CRM_CONFIG'][$name] = $value;
        }
        $this->save();
    }

    public function getAll() { 
        return $_SESSION['CRM_CONFIG'];
    }

    public function setAll($arrConf) {
        $keys = array_keys(self::$defaultConf);
        foreach($keys as $key) {
            if($arrConf[$key]) {
                $_SESSION['CRM_CONFIG'][$key] = $arrConf[$key];
            } else {
                $_SESSION['CRM_CONFIG'][$key] = false;
            } 
        } 
        $this->save();
    }

    private function read() { 
        $rsUser = CUser::GetList(($by="id"), ($order="desc"), array('ID' => CUser::GetID()), array('SELECT' => array('UF_CONFIG'), 'FIELDS' => array('ID')));  
        if($data = $rsUser->Fetch()) {
            $arr = unserialize($data['~UF_CONFIG']);
        }
        if(!$arr) {
            $_SESSION['CRM_CONFIG'] = self::$defaultConf;
            $this->save();
        } else {
            $_SESSION['CRM_CONFIG'] = $arr;
        }
    }

    public function save() {
        $user = new CUser; 
        $user->Update(CUser::GetID(), array('UF_CONFIG' => serialize($_SESSION['CRM_CONFIG'])));
    }

}

class crmEntitiesHelper {

    public static function recalcCommentsCnt($taskId) {
        CModule::IncludeModule('iblock');
        $num = 0;
        $commentres = CIBlockElement::GetList(array(), array("PROPERTY_TASK" => $taskId, "IBLOCK_ID" => COMMENTS_IBLOCK_ID, "ACTIVE" => "Y"), false, false, array('ID', 'IBLOCK_ID')); 
        while ($comment = $commentres->GetNext()) {
            $num++;
        }
        CIBlockElement::SetPropertyValuesEx($taskId, TASKS_IBLOCK_ID, array('COMMNETS_CNT' => $num)); 
        return $num;
    }

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
    
    public static function GetTaskUrl($taskId) {
        return TASKS_LIST_URL . self::GetProjectIdByTask($taskId) . '/' . $taskId . '/';
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
    
    static $thisUserProjects = false;

    public static function getProjectsByThisUser() {
        if(self::$thisUserProjects === false) {
            self::$thisUserProjects = array();
            CModule::IncludeModule('iblock');  
            global $USER;
            $arrFilter = array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
            $arrFilter = array_merge($USER->GetViewProjectsFilter(), $arrFilter);
            $res = CIBlockElement::GetList(array(), $arrFilter, false, false, array("ID")); 
            while ($project = $res->GetNext()) {
                self::$thisUserProjects[] = $project["ID"];
            } 
        }
        return self::$thisUserProjects;
    }
    
    public static function isProject($id) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 'ID' => $id), false, false, Array("ID", "IBLOCK_ID"));
        if($ob = $res->GetNext()) { 
            return true;
        }
        return false;
    }
    
    public static function isTask($id) {
        CModule::IncludeModule('iblock');  
        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ID' => $id), false, false, Array("ID", "IBLOCK_ID"));
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
            
$GLOBALS['CRM_CONFIG'] = new CrmConfig();