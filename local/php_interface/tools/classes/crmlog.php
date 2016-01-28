<?php 
/*  
    CREATE TABLE IF NOT EXISTS `crmlog` (
      `ID` int(4) NOT NULL,
      `FROM_USER` int(4) NOT NULL,
      `TO_USER` int(4) NOT NULL,
      `MODULE` int(4) NOT NULL,
      `ITEM_ID` int(4) NOT NULL,
      `ACTION` int(4) NOT NULL,
      `MESSAGE` varchar(250) NOT NULL,
      `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `VIEW` int(1) NOT NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=5; 
   
    ALTER TABLE `crmlog` ADD PRIMARY KEY (`ID`); 
 
    ALTER TABLE `crmlog` MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5; */

class CrmLog {
     
    private static $msgMaxLen = 250;
    private $actions = array('add' => 1, 'comment' => 2);
    private $module;
    private static $cache; 

    public function delete($id) {
        $id = intval($id);
        if(!$id) {
            return;
        }
        global $DB;
        $DB->Query("DELETE FROM `crmlog` WHERE `ITEM_ID` = {$id}");                
    }

    public function __construct($module = 'task') {
        if(!is_numeric($module)) {
            $modules = array('task' => TASKS_IBLOCK_ID, 'comment' => COMMENTS_IBLOCK_ID); 
            $module = $modules[$module]; 
        }
        $this->module = $module;
    }

    public function getMyEvents($limit) {
        $limit = intval($limit);
        if(!$limit) { 
            $limit = 20;
        }
        if(self::$cache) {
           return self::$cache; 
        }
        $userId = CUser::GetID();
        $query = "SELECT * FROM `crmlog`  WHERE `TO_USER` = {$userId}  ORDER BY ID DESC  LIMIT {$limit} ;";
        global $DB;
        $res = $DB->Query($query);
        while ($item = $res->Fetch()) {
            switch ($item['MODULE']) {
                case TASKS_IBLOCK_ID:
                    $item['LINK'] = crmEntitiesHelper::GetTaskUrl($item['ITEM_ID']);
                    break; 
                case COMMENTS_IBLOCK_ID:
                    $item['LINK'] = '#';
                    break; 
            }
            switch ($item['ACTION']) {
                case 1:
                    $item['TEXT_ACTION'] = 'Создана задача';
                    break;
                case 2:
                    $item['LINK'] .= '#bottom';
                    $item['TEXT_ACTION'] = 'Добавлен комментарий';
                    break;
            }
            $result[] = $item;
        } 
        self::$cache = $result;
        return $result;
    }
 
    public function add($user, $item, $action = 'add', $message = '') {
        $user = intval($user);
        $userFrom = CUser::GetID(); 
        if($user == $userFrom) {
            return;
        } 
        global $DB;
        $item = intval($item);
        $action = $this->actions[$action];
        $message = trim(strip_tags($message));
        $message = $DB->ForSql($message, self::$msgMaxLen); 
        $query = "INSERT INTO `crmlog` (`ID`, `FROM_USER`, `TO_USER`, `MODULE`, "
               . "`ITEM_ID`, `ACTION`, `MESSAGE`, `DATE`, `VIEW`) "
               . "VALUES (NULL, '{$userFrom}', '{$user}', '{$this->module}', "
               . "'{$item}', '{$action}', '{$message}', CURRENT_TIMESTAMP, '0');"; 
        $DB->Query($query);
    }

    public function view($itemid) { 
        $user = CUser::GetID();
        
    }
    
    public function viewAll() {
        $userId = CUser::GetID();
        global $DB;
        $DB->Query("UPDATE `crmlog` SET `VIEW` = 1 WHERE `TO_USER` = {$userId}");  
    }
 
}