<?php

include 'constants.php';
include 'tools/functions.php';

class CrmUser extends CUser {

    function hasRigthsToAddProject() {
         return parent::IsAdmin();
    }
    
    function hasRigthsToDeleteProject() {
         return parent::IsAdmin();
    }
    
    function hasRigthsToEditProject() {
         return parent::IsAdmin();
    }

    /* Вернёт фильтр, который вытянет проекты, которые можно видеть пользователю
       то что возвращает этот метод нужно мержить к фильтру везде где тянем проект или проекты
    */
    function GetViewProjectsFilter() {
        if(parent::IsAdmin()) {  
            return array(); 
        }
        $arFilter = array( // остальные видят проекты где они или исполнители или заказчики
                array(
                    "LOGIC" => "OR",
                    array("PROPERTY_PROGRAMMER" => parent::GetID()),
                    array("PROPERTY_CUSTOMER" => parent::GetID())
                )
        );
        return $arFilter;
    }

}
 
AddEventHandler("iblock", "OnBeforeIBlockElementAdd",    Array("RightsHandler", "OnBeforeIBlockElementAdd"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("RightsHandler", "OnBeforeIBlockElementDelete"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("RightsHandler", "OnBeforeIBlockElementUpdate"));

class RightsHandler { 
    
    static $noRightsMessage = "У вас недостаточно прав на эту операцию";
    
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
            default:
                return;
                break;
        }
            
        $method = 'hasRigthsTo' . $action . $enitity; 
        global $USER, $APPLICATION;
        if(method_exists($USER, $method)) {
            if(!$USER->$method()) { 
                $APPLICATION->throwException(self::$noRightsMessage);
                return false;
            }
        }
                
    } 
     
}
 
AddEventHandler("main", "OnBeforeProlog", "ChangeCUserToCrmUser"); 
function ChangeCUserToCrmUser() {
    global $USER;
    $USER = new CrmUser();
}

CModule::AddAutoloadClasses("", array("ToolTip" => "/local/php_interface/tools/tooltip.php"));
