<?php

class ToolTip {  
    
    static $key = 'CRM_TOOLTIPS'; 

    static function Add($text) {
        $text = str_replace("'", "", $text);
        $_SESSION[self::$key][] = array('text' => $text, 'type' => 'success'); 
    }

    static function AddError($text) {
        $text = str_replace("'", "", $text);
        $_SESSION[self::$key][] = array('text' => $text, 'type' => 'error'); 
    }

    static function ShowJs($showScriptTag = false) {
        if(count($_SESSION[self::$key])) {
            if($showScriptTag) {
                echo "<script>";
            }
            ?>$(function() { <?
            foreach($_SESSION[self::$key] as $item) { ?>
                new PNotify({text: '<?=$item['text']?>', type: '<?=$item['type']?>'});
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