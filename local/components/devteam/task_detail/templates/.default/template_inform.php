<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Информация</h2>  
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-12">  
                <p>Проект: <a href="<?= TASKS_LIST_URL ?><?= $arResult['PROJECT']['ID']; ?>/"><?= $arResult['PROJECT']['NAME'] ?></a></p>
                <p class="date">Создана: <?= $arResult['TASK']['DATE_CREATE'] ?></p>
                <p>Постановщик: <?= $arResult['USERS'][$arResult['TASK']['CREATED_BY']]['FULL_NAME']; ?></p> 
                <p>Исполнитель: <?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['FULL_NAME']; ?></p> 
                <p class="status">Статус: <span class="label label-success"><?= $arResult['STATUS_TEXT']; ?></span></p> 
                <? if ($arResult['TASK']['PROPS']['CALC']['VALUE']) { ?>
                    <p>Оценка: <?= $arResult['TASK']['PROPS']['CALC']['VALUE']; ?> ч.</p>
                    <?
                } elseif($arResult['STATUS']) {
                    ?><p>Оценка: по факту</p><?
                }
                foreach ($arResult['COMMENTS'] as $comment) {
                    if ($comment['STATUS'] != STATUS_COMMENT_CONFIRM) {
                        continue;
                    }
                    ?>
                    <p>+<?= $comment['PROPERTY_CALC_VALUE']; ?> ч. <a href="#comment<?= $comment['ID'] ?>">Комментарий #<?= $comment['ID'] ?></a></p>
                    <?
                }
                if ($arResult['TASK']['PROPS']['CALC_COMMENTS']['VALUE'] &&
                        $arResult['TASK']['PROPS']['CALC']['VALUE'] != $arResult['TASK']['PROPS']['CALC_COMMENTS']['VALUE']) {
                    ?>
                    <p>Всего: <?= $arResult['TASK']['PROPS']['CALC_COMMENTS']['VALUE']; ?> ч. </p>
                <? } ?>
            </div>  
        </div>
    </div>
</div>