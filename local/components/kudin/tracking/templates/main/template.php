<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); 
?>
<div class="col-md-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Сейчас в работе</h2> 
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?
            if ($arResult['ID']) {
                ?>
                <a href="<?= $arResult['DETAIL_PAGE_URL'] ?>" >#<?= $arResult['ID'] ?> <?= $arResult['NAME'] ?> </a>
                <?
            } else {
                ?>
                <p>Задач не запущено. <a href="/tasks/?filter=open&filter2=my">Начать работать</a></p>
                <?
            }
            ?>
        </div>
    </div>
</div> 