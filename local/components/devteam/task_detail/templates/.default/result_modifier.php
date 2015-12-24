<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
 
foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as &$file) {
    $file['icon'] = getFileIcon($file);
    $file["FILE_SIZE"] = formatBytes($file["FILE_SIZE"]);
}
