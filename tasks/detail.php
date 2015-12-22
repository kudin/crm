<?php

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
 
$APPLICATION->IncludeComponent('devteam:task_detail', '', array('PROJECT' => $_REQUEST['PROJECT'], 'ID' => $_REQUEST['ID']));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 