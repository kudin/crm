<?php

define('NEED_AUTH', 'Y'); 
 
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent('kudin:report', 'html');