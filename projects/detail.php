<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Проект');
 
$APPLICATION->IncludeComponent('devteam:project_detail', '', array('ID' => $_REQUEST['ID']));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");