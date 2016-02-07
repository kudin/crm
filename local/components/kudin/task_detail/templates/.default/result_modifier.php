<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as &$file) {
    $f = new fileHelper($file);
    $file['icon'] = $f->getFileIcon(); 
    $file['class'] = $f->getFileClass();
    $file['TRUNCATED_NAME'] = TruncateText($file['ORIGINAL_NAME'], 20);
    $file["FILE_SIZE"] = formatBytes($file["FILE_SIZE"]);
}