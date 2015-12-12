<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$rsUser = CUser::GetByID(CUser::GetID());
$arResult = $rsUser->Fetch();
 
$this->IncludeComponentTemplate();