<?php

class FileHelper {
    
    var $file = false;
    var $path_parts = false;
    static $iconsPath = '/images/icons/';
    
    function __construct($file) {
        if (is_int($file)) {
            $file = CFile::GetFileArray($file);
        }
        $this->file = $file;
        $this->path_parts = pathinfo($this->file['SRC']);
    }
    
    public function getFileIcon() {  
        $ext = strtolower($this->path_parts["extension"]);  
        if (in_array($ext, array('png', 'jpg', 'jpeg', 'gif'))) {
            $icon = CFile::ResizeImageGet($this->file, array('width' => 256, 'height' => 256), BX_RESIZE_IMAGE_EXACT, true);
            $icon = $icon['src'];
        }
        if (in_array($ext, array('ac3', 'doc', 'mp3', 'php', 'pyc', 'rpm', 'xcf', 'js',
                                 'tgz', 'xls', 'bmp', 'gz', 'py', 'svg', 'txt', 'tiff',
                                 'zip', 'c', 'html', 'pdf', 'psd', 'rar', 'tga', 'docx'))) {
            $icon = self::$iconsPath . $ext . '.png';
        } 
        if (!$icon) { 
            $icon =  self::$iconsPath . 'other.png';
        } 
        return $icon;
    }
    
    public function getFileClass() {  
        $ext = strtolower($this->path_parts["extension"]);  
        switch ($ext) {
            case 'rar': case 'zip': case 'tar': case 'gz': case 'tgz':
                $class = 'fa-file-archive-o';
                break; 
            case 'mp3': case 'waw': case 'midi': case 'flac':
                $class = 'fa-file-audio-o';
                break; 
            case 'xls': case 'xlsx': case 'ods':
                $class = 'fa-file-excel-o';
                break; 
            case 'png': case 'jpg': case 'jpeg': case 'gif': case 'bmp': case 'tiff': case 'psd':
                $class = 'fa-file-image-o';
                break;
            case 'mp4': case 'mpg': case 'mpg4':
                $class = 'fa-file-movie-o';
                break;
            case 'pdf': 
                $class = 'fa-file-pdf-o';
                break;
            case 'odp': case 'ppt': 
                $class = 'fa-file-powerpoint-o';
                break; 
            case 'odp': case 'ppt': 
                $class = 'fa-file-powerpoint-o';
                break; 
            case 'doc': case 'docx': 
                $class = 'fa-file-word-o';
                break;
            case 'php': case 'js': case 'html': 
                $class= 'fa-file-code-o';
                break;
            case 'txt':
                $class = 'fa-file-text-o';
                break;
            default:
                $class = 'fa-file-o';
                break;
        } 
        return $class;
    }

}