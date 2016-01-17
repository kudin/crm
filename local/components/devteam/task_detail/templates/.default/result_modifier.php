<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as &$file) {
    $file['icon'] = getFileIcon($file);
    $file['ORIGINAL_NAME'] = TruncateText($file['ORIGINAL_NAME'], 50);
    $file["FILE_SIZE"] = formatBytes($file["FILE_SIZE"]);
}

$arResult['TASK']['~DETAIL_TEXT'] = selectLinks($arResult['TASK']['~DETAIL_TEXT']);

foreach ($arResult['COMMENTS'] as &$comment) {
    $comment['~PREVIEW_TEXT'] = selectLinks($comment['~PREVIEW_TEXT']);
}