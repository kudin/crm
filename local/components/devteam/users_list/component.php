<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                          array('ACTIVE'=>'Y'), 
                          array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME', 'EMAIL')) );    
while($arUser = $rsUsers->Fetch()) {
    $arResult['USERS'][] = $arUser;
}
   
$this->IncludeComponentTemplate();