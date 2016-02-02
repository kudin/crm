<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Задачи');
 
$APPLICATION->IncludeComponent('kudin:tasks_list', '', array('PROJECT' => $_REQUEST['ID']));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 