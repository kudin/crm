<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Поставить новую задачу');
 
$APPLICATION->IncludeComponent('devteam:task_add', '', array('PROJECT' => false));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 