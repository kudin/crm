<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$rsUser = CUser::GetByID(CUser::GetID());
$arResult = $rsUser->Fetch();
 
if($arResult['PERSONAL_PHOTO']) {
    $arResult['PERSONAL_PHOTO'] = CFile::ResizeImageGet($arResult['PERSONAL_PHOTO'], array('width'=>200, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);       
}

$this->IncludeComponentTemplate();