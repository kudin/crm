<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Сотрудники');
 
$APPLICATION->IncludeComponent('devteam:users_list');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 