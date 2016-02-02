<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

foreach ($arResult['EVENTS'] as &$event) {
    $event['MESSAGE'] = TruncateText($event['MESSAGE'], 150);
}