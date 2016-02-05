<?
define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Последние события');

?>
<div class="row">
<?

$APPLICATION->IncludeComponent('kudin:events', 'main');
        $APPLICATION->IncludeComponent('kudin:user_detail'); 

?>
</div>
<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
