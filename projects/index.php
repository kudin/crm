<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Проекты');
 
$APPLICATION->IncludeComponent('kudin:projects_list');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");