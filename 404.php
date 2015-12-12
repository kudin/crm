<?
define('NEED_AUTH', 'Y');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle('Страница не найдена');
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">   
        <div class="col-middle">
            <div class="text-center text-center">
                <h1 class="error-number">404</h1>
                <h2>Такой страницы не существует</h2>
                <p>Если Вы считаете что это ошибка <a href="mailto:kudinsasha@gmail.com">пишите письма</a>
                </p>  
                <p><a href="/">Вернуться на сайт</a>
                </p> 
            </div>
        </div>  
    </div> 
</div>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>