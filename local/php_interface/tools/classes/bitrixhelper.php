<?php
 
class BitrixHelper { 
    
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
        $rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), array('ACTIVE'=>'Y', 'ID' => implode(' | ', $usersIds), array('FIELDS' => array('ID', 'NAME', 'LOGIN', 'LAST_NAME', 'PERSONAL_PHOTO'))));  
        while($arUser = $rsUsers->Fetch()) {  
            if($arUser['PERSONAL_PHOTO']) {
                $arUser['PERSONAL_PHOTO'] = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, true);       
            } 
            $arUser['FULL_NAME'] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
            $users[$arUser['ID']] = $arUser;
        }
        return $users;
    }
    
}
