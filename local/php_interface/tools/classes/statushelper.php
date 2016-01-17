<?php

class StatusHelper {

    static $statusName = array(
        0 => 'Ожидает оценки',
        6 => 'Согласование оценки',
        7 => 'Оценка отклонена', 
        STATUS_LIST_CALC_AGRED => 'Оценка согласована',
        STATUS_LIST_WORK => 'В работе',
        STATUS_LIST_PAUSE => 'Пауза',
        STATUS_LIST_COMPLETE => 'Готово', 
        STATUS_LIST_ACCEPT => 'Принята',
        STATUS_LIST_REJECT => 'Отклонена', 
        8 => 'В доработке', 
        9 => 'Закрыта'
    );

    static function getStr($statusId) {
        $statusId = intval($statusId);
        if (!$statusId) {
            $statusId = 0;
        }
        return self::$statusName[$statusId]; 
    }

}
