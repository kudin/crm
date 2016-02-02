<?php 

define('NEED_AUTH', 'Y'); 

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); 

$APPLICATION->SetTitle('Пользователь');

?>
<div class="row">
    <?
    if($USER->GetID() == $ID || $USER->IsAdmin()) {
        $APPLICATION->IncludeComponent('devteam:user_detail', '', array('USER_ID' => $ID)); 
        $APPLICATION->IncludeComponent('devteam:events', 'user', array('USER_ID' => $ID)); 
    } else {
        ShowError('Доступ запрешён');
    }
    ?> 
</div>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
 