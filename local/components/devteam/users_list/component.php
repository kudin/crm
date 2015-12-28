<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                          array('ACTIVE'=>'Y'), 
                          array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHOTO')) );    
while($arUser = $rsUsers->Fetch()) { 
    if($arUser['PERSONAL_PHOTO']) {
        $arUser['PERSONAL_PHOTO'] = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>200, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);       
    } 
    $arResult['USERS'][] = $arUser;
}

$this->IncludeComponentTemplate();