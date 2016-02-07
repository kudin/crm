<?php
 
class BitrixHelper { 
    
    static $usersCache = array();
    
    public static function getUsersArrByIds($usersIds) {   
        if(!$usersIds) {
            return false;
        }
        if(!is_array($usersIds)) {
            $usersIds = array($usersIds);
        }
        if(!count($usersIds)) {
            return;
        }
        $usersIds = array_unique($usersIds);
        $otherUsersIds = array(); 
        foreach($usersIds as $id) {
            $id = intval($id);
            if(!$id) {
                continue;
            }
            if(isset(self::$usersCache[$id])) {
                $users[$id] = self::$usersCache[$id];
            } else {
                $otherUsersIds[] = $id;
            }
        }  
        if(count($otherUsersIds)) {
            $rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                                      array('ACTIVE'=>'Y', 'ID' => implode(' | ', $otherUsersIds)), 
                                      array('FIELDS' => array('ID', 'NAME', 'LOGIN', 'LAST_NAME', 'PERSONAL_PHOTO')));  
            while($arUser = $rsUsers->Fetch()) {  
                if($arUser['PERSONAL_PHOTO']) {
                    $arimg = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, true);       
                    $src = $arimg['src']; 
                } else {
                    $src = '/images/user.png';
                }
                $arUser['PERSONAL_PHOTO'] = $src; 
                $arUser['FULL_NAME'] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
                self::$usersCache[$arUser['ID']] = $arUser; 
                $users[$arUser['ID']] = $arUser;   
            }
        }    
        return $users;
    }
    
}
