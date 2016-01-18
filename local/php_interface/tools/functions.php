<?php

function selectLinks($text) {
    $pattern = array("'[\w\+]+://[A-z0-9\.\?\+\-/_=&%#:;]+[\w/=]+'si", 
                     "'([^/])(www\.[A-z0-9\.\?\+\-/_=&%#:;]+[\w/=]+)'si");
    $replace = array('<a href="$0" target="_blank" rel="nofollow">$0</a>', 
                     '$1<a href="http://$2" target="_blank" rel="nofollow">$2</a>');
    $text = preg_replace($pattern, $replace, $text);
    return $text;
}

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

function formatTime($time) { 
    $time = trim($time);
    $time = str_replace(array(',', ' '), array('.', ''), $time);
    $time = floatval($time);
    if(!$time || $time > MAX_TASK_TIME) {
        $time = false;
    }
    return $time;
}
