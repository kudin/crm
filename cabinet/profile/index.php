<?

define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <?$APPLICATION->IncludeComponent(
                    "bitrix:main.profile",
                    "",
                    Array(
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "CHECK_RIGHTS" => "N",
                            "COMPONENT_TEMPLATE" => ".default",
                            "SEND_INFO" => "N",
                            "SET_TITLE" => "Y",
                            "USER_PROPERTY" => array(),
                            "USER_PROPERTY_NAME" => ""
                    )
            );?> 
        </div>
    </div>
</div>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>