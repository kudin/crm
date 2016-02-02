<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as &$project) { 
    $project['PREVIEW_TEXT'] = TruncateText($project['PREVIEW_TEXT'], 100);
}