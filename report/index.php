<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Отчёты');
 
$APPLICATION->IncludeComponent('devteam:report');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 