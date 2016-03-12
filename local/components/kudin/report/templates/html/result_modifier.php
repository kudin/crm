<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $arResult['USERS'][$arResult['USER']]['FULL_NAME'] . ' ' . $arResult['RESERVATION'] . '.html"');