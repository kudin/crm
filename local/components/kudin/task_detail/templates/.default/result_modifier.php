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

foreach($arResult['COMMENTS'] as &$comment) {
    foreach($comment['FILES'] as &$file) {
        $f = new fileHelper($file);
        $file['icon'] = $f->getFileIcon(); 
        $file['class'] = $f->getFileClass();
        $file['TRUNCATED_NAME'] = TruncateText($file['ORIGINAL_NAME'], 20);
        $file["FILE_SIZE"] = formatBytes($file["FILE_SIZE"]);
    }
}
 /*
$reg = "/[^>\"']((ftp|https?):\/\/)(www\.)?([a-z0-9:\/?&=%#_.-]+)(<\/a|['\"])/i";
$replace = "<a href='\$1\$3\$4' target='_blank'>\$1\$3\$4</a>";
$te = preg_replace($reg, $replace, " df df  
  https://na.com</a 
  https://na.com'dsf fs  
  https://ne.com$dsf fs  
  https://na.com\"dsf fs  
  https://ne.com</b df df ");

echo $te;*/