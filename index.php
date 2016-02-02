<?
define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Последние события');
  
$APPLICATION->IncludeComponent('kudin:events', 'main');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
