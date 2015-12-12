<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Задачи');
 
$APPLICATION->IncludeComponent('devteam:projects_tasks');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 