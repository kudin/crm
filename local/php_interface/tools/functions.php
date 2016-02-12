<?php

function fixUrl($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    } 
    return $url;
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function formatTime($time, $maxtime = MAX_TASK_TIME) { 
    $time = trim($time);
    if(strpos($time, ":")) {
        $ar = explode(':', $time);
        if(count($ar) == 2) {
            $time = $ar[0] + ($ar[1] / 60);
            $time = round($time, 2);
        } 
    }
    $time = str_replace(array(',', ' '), array('.', ''), $time);
    $time = floatval($time);
    if($time < 0) {
        $time = false;
    }
    if(!$time || $time > $maxtime) {
        $time = false;
    }
    return $time;
} 

function validatePriority($priority) {
    $priority = intval($priority);
    if(!in_array($priority, range(0, MAX_PRIORITY))) {
        $priority = DEFAULT_PRIORITY;
    }
    return $priority;
}

function trackStartedTask($stopStartedTask = true) {
    CModule::IncludeModule('iblock');
    $res = CIBlockElement::GetList(
            array(), 
            array("IBLOCK_ID" => TASKS_IBLOCK_ID,
                'ACTIVE' => 'Y', 
                "PROPERTY_PROGRAMMER" => CUser::GetID(),
                "PROPERTY_STATUS" => STATUS_LIST_WORK), 
            false, 
            false,
            array('ID', 'NAME', 'PROPERTY_PROJECT', 'PROPERTY_STATUS_DATE'));
    if ($taskArr = $res->Fetch()) {
        if($stopStartedTask) {
            CIBlockElement::SetPropertyValuesEx($taskArr['ID'], TASKS_IBLOCK_ID, array('STATUS' => STATUS_LIST_PAUSE));
        }
        $link = TASKS_LIST_URL . $taskArr["PROPERTY_PROJECT_VALUE"] . '/' . $taskArr['ID'] . '/';
        $date = new DateTime($taskArr["PROPERTY_STATUS_DATE_VALUE"]);
        $curdate = new DateTime();
        $diff = $date->diff($curdate);
        $h = $diff->format('%h');
        $i = $diff->format('%i'); 
        if ($h || $i) {
            $timingText = '';
            if ($h) {
                $timingText = "{$h} ч, ";
            }
            if ($i) {
                $timingText = $timingText . "{$i} мин. ";
            }
            $decTime = $h + ($i / 60);
            $decTime = round($decTime, 2);
            $el = new CIBlockElement;
            if ($el->Add(array(
                        "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
                        "MODIFIED_BY" => CUser::GetID(),
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => TRACKING_IBLOCK_ID,
                        "NAME" => 'Без названия' . ' (' . $decTime . 'ч.)',
                        "ACTIVE" => "Y",
                        "PROPERTY_VALUES" => array("HOURS" => $decTime, "TASK" => $taskArr['ID'])))) {
                crmEntitiesHelper::recalcTaskTracking($taskArr['ID']);
                ToolTip::Add("+ {$timingText} в трекер задачи \"<a target=\"_blank\" href=\"{$link}\">{$taskArr['ID']} {$taskArr['NAME']}</a>\"");
            } else {
                ToolTip::AddError($el->LAST_ERROR);
            }
        } 
    }
}

function sortArrByNameKey($a, $b) {
    if ($a['NAME'] == $b['NAME']) {
        return 0;
    }
    return ($a['NAME'] < $b['NAME']) ? -1 : 1;
} 