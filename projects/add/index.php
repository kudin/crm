<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Добавление проекта');
 
$APPLICATION->IncludeComponent('devteam:projects_add');
 
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");