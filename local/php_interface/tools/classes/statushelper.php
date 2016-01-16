<?php

class StatusHelper {

    static $arr = array(
        0 => 'Ожидает оценки'
    );

    static function getStr($statusId) {
        $statusId = intval($statusId);
        if (!$statusId) {
            $statusId = 0;
        }
        return self::$arr[$statusId]; 
    }

}
