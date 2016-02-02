<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Поставить новую задачу');
 
$APPLICATION->IncludeComponent('kudin:task_add', '', array('PROJECT' => $_REQUEST['ID']));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 