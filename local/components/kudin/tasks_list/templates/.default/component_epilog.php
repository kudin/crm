<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!$arParams["PROJECT"] && count($arResult['PROJECTS']) == 1) {
    $project = array_pop($arResult['PROJECTS']);
    LocalRedirect(TASKS_LIST_URL . $project["ID"] . '/');
}