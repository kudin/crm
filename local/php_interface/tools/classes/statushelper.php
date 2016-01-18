<?php

class StatusHelper {

    static $statusName = array(
        0 => 'Ожидает оценки',
        STATUS_LIST_AGR_CALCED => 'Согласование оценки',
        STATUS_LIST_CALC_REJECT => 'Оценка отклонена', 
        STATUS_LIST_CALC_AGRED => 'Оценка согласована',
        STATUS_LIST_WORK => 'В работе',
        STATUS_LIST_PAUSE => 'Пауза',
        STATUS_LIST_COMPLETE => 'Готово', 
        STATUS_LIST_ACCEPT => 'Закрыта',
        STATUS_LIST_REJECT => 'Отклонена', 
        8 => 'В доработке'
    );

    static function getStr($statusId) {
        $statusId = intval($statusId);
        if (!$statusId) {
            $statusId = 0;
        }
        return self::$statusName[$statusId]; 
    }

}
