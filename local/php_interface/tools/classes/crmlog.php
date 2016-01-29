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
    private $actions = array('add' => 1, 'comment' => 2, 'status' => 3, 'edit' => 4, 'edit_comment' => 5);
    private $module;
    private static $cache; 
    static $notViewedIds = array();
    static $commentsCnt = array();
    private $newCnt = 0;
                
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
    
    public function isNotViewed($id) {  
        return in_array($id, self::$notViewedIds);
    }
                
    public function getStatusField($id) {
        foreach(self::$cache as $key => $el) {
            if($el['VIEW']) {
                continue; 
            } 
            if($el['ITEM_ID'] == $id) {
                $ar[$el['TEXT_ACTION']]++; 
            }
        } 
        foreach ($ar as $statusName => $statusCnt) {
            if($statusCnt > 1) {
                $statuses[] = "$statusName ($statusCnt)";
            } else {
                $statuses[] = $statusName;
            }
        }
        return implode(', ', $statuses);
    }
                
    public function getNewCommentsCnt($id) {   
        return self::$commentsCnt[$id];
    }

    private function collectEventsToArr($res, $collectNotViewed = false) {
        while ($item = $res->Fetch()) {
                if($collectNotViewed && !$item['VIEW']) {
                $this->newCnt++;
                self::$notViewedIds[] = $item['ITEM_ID'];
                if($item['ACTION'] == 2) {
                    self::$commentsCnt[$item['ITEM_ID']]++; 
                }
            }
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
                case 3:
                    $item['TEXT_ACTION'] = 'Изменён статус';
                    break;
                case 4:
                    $item['TEXT_ACTION'] = 'Изменена задача';
                    break;
                case 5:
                    $item['TEXT_ACTION'] = 'Изменён комментарий';
                    break;
            }
            $result[] = $item;
        } 
        return $result;
    }

    public function getUserEvents($userID) {
        $userID = intval($userID);
        if(!$userID) {
            return;
        }
        $query = "SELECT * FROM `crmlog` WHERE `FROM_USER` = {$userID} ORDER BY  ID DESC  LIMIT 20;";
        global $DB; 
        return $this->collectEventsToArr($DB->Query($query));  
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
        $query = "SELECT * FROM `crmlog` WHERE `TO_USER` = {$userId}  ORDER BY VIEW ASC , ID DESC  LIMIT {$limit} ;";
        global $DB;
        $res = $DB->Query($query);
        $result = $this->collectEventsToArr($res, true);
        self::$cache = $result;
        return $result;
    }
    
    public function getNewCnt() {
        return $this->newCnt;
    }
 
    public function add($users, $item, $action = 'add', $message = '') {
        if(!is_array($users)) {
            $users = array($users);
        }
        $users = array_unique($users);
        foreach($users as $user) {
            $this->addRow($user, $item, $action, $message);
        }
    }

    public function addRow($user, $item, $action, $message) {
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
        $itemid = intval($itemid);
        if(!$itemid) {
            return;
        }
        global $DB;
        $user = CUser::GetID();
        $q = "UPDATE `crmlog` SET `VIEW` = 1 WHERE (`TO_USER` = {$user} AND `ITEM_ID` = {$itemid});"; 
        $DB->Query($q);  
    }
    
    public function viewAll() {
        $userId = CUser::GetID();
        global $DB;
        $DB->Query("UPDATE `crmlog` SET `VIEW` = 1 WHERE `TO_USER` = {$userId}");  
    }
 
}