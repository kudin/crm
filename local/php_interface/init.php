<?php

include 'constants.php';

class CrmUser extends CUser {
    
    function hasRightsToWorkWithProjects() {
         return parent::IsAdmin();
    }
    
    function hasRigthsToAddProject() {
        return $this->hasRightsToWorkWithProjects();
    }
    
    function hasRigthsToDeleteProject() {
        return $this->hasRightsToWorkWithProjects();
    }
    
    function hasRigthsToEditProject() {
        return $this->hasRightsToWorkWithProjects();
    }
    
    /* Вернёт фильтр, который вытянет проекты, которые можно видеть пользователю */
    function GetViewProjectsFilter() {
        
    }
     
}
 
AddEventHandler("iblock", "OnBeforeIBlockElementAdd",    Array("ProjectsRightsHandler", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("ProjectsRightsHandler", "OnBeforeIBlockElementDelete"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("ProjectsRightsHandler", "OnBeforeIBlockElementUpdate"));

class ProjectsRightsHandler { 
    
    static $errorMessage = "У вас недостаточно прав на эту операцию";
            
    function OnBeforeIBlockElementAddHandler(&$arFields) { 
        if($arFields["IBLOCK_ID"] == PROJECTS_IBLOCK_ID) {
            global $USER, $APPLICATION;
            if(!$USER->hasRigthsToAddProject()) { 
                $APPLICATION->throwException(self::$errorMessage);
                return false;
            }
        } 
    } 
    
    function OnBeforeIBlockElementDelete(&$arFields) { 
        if($arFields["IBLOCK_ID"] == PROJECTS_IBLOCK_ID) { 
            global $USER, $APPLICATION;
            if(!$USER->hasRigthsToDeleteProject()) { 
                $APPLICATION->throwException(self::$errorMessage);
                return false;
            }
        } 
    } 
     
    function OnBeforeIBlockElementUpdate(&$arFields) { 
        if($arFields["IBLOCK_ID"] == PROJECTS_IBLOCK_ID) {
            global $USER, $APPLICATION;
            if(!$USER->hasRigthsToEditProject()) { 
                $APPLICATION->throwException(self::$errorMessage);
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

CModule::AddAutoloadClasses("",array("ToolTip" => "/local/php_interface/tools/tooltip.php"));