<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(count($arResult['ITEMS']) == 1) {
    LocalRedirect(TASKS_LIST_URL . $arResult['ITEMS'][0]['ID'] . '/');
}