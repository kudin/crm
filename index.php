<?
define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Последние события');

?>
<div class="row">
<?

$APPLICATION->IncludeComponent('kudin:events', 'main');
$APPLICATION->IncludeComponent('kudin:tracking', 'main');

?>
</div>
<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
