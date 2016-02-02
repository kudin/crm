<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($_REQUEST['applyConfig']) {
    $GLOBALS['CRM_CONFIG']->setAll($_REQUEST);
    LocalRedirect($APPLICATION->GetCurDir());
}

$arResult['CONF'] = $GLOBALS['CRM_CONFIG']->getAll();

$this->IncludeComponentTemplate();