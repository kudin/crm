<?php

class ToolTip {  
    
    static $key = 'CRM_TOOLTIPS'; 
    
    static function Add($text) {
        $text = str_replace("'", "", $text);
        $_SESSION[self::$key][] = array('title' => '', 'text' => $text); 
    }  
    
    static function ShowJs($showScriptTag = false) {
        if(count($_SESSION[self::$key])) {
            if($showScriptTag) {
                echo "<script>";
            }
            ?>$(function() { <?
            foreach($_SESSION[self::$key] as $item) { ?>
                new PNotify({<?if($item['title']) {?> title: '<?=$item['title']?>', <?}?>
                            text: '<?=$item['text']?>', 
                            type: 'success'
                });
            <? } ?>});<? 
            if($showScriptTag) {
                echo "</script>";
            }
        self::ClearAll();
        }
    } 
    
    static function ClearAll() {
        unset($_SESSION[self::$key]);
    }
    
}