<?php

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getFileIcon($file) { 
    if (is_int($file)) {
        $file = CFile::GetFileArray($file);
    }
    $path_parts = pathinfo($file['SRC']);
    $ext = strtolower($path_parts["extension"]); 
    if (in_array($ext, array('png', 'jpg', 'jpeg', 'gif'))) {
        $icon = CFile::ResizeImageGet($file, array('width' => 256, 'height' => 256), BX_RESIZE_IMAGE_EXACT, true);
        $icon = $icon['src'];
    } 
    if (in_array($ext, array('ac3', 'doc', 'mp3', 'php', 'pyc', 'rpm', 'xcf', 'js', 'docx',
                'tgz', 'xls', 'bmp', 'gz', 'py', 'svg', 'txt', 'tiff',
                'zip', 'c', 'html', 'pdf', 'psd', 'rar', 'tga'))) {
        $icon = '/images/icons/' . $ext . '.png';
    } 
    if (!$icon) {
        $icon = '/images/icons/other.png';
    } 
    return $icon;
}
