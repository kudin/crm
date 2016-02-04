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
                <?if($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'] != $arResult['TASK']['CREATED_BY']) {
                    ?>
                <p>Создатель: <?= $arResult['USERS'][$arResult['TASK']['CREATED_BY']]['FULL_NAME']; ?></p>
                <?
                }?>
                <p>Постановщик: <span class="editable_show"><?= $arResult['USERS'][$arResult['TASK']['PROPS']['CUSTOMER']['VALUE']]['FULL_NAME']; ?></span>
                    <select data-edittask="Y" class="editable_hidden" name="CUSTOMER_NEW"><?
                    foreach($arResult['CUSTOMERS_IDS'] as $id) {
                        ?>
                        <option value="<?=$id?>"<?if($arResult['TASK']['PROPS']['CUSTOMER']['VALUE'] == $id) { ?> selected <? } ?>><?=$arResult['USERS'][$id]['FULL_NAME'];?></option>
                        <?
                    }
                    ?></select>
                </p> 
                <p>Исполнитель: <span class="editable_show"><?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['FULL_NAME']; ?></span>
                    <select data-edittask="Y" class="editable_hidden" name="PROGRAMMER_NEW"><?
                    foreach($arResult['PROGRAMERS_IDS'] as $id) {
                        ?>
                        <option value="<?=$id?>"<?if($arResult['TASK']['PROPS']['PROGRAMMER']['VALUE'] == $id) { ?> selected <? } ?> ><?=$arResult['USERS'][$id]['FULL_NAME'];?></option>
                        <? 
                    }
                    ?></select>
                </p> 
                <p class="status" <?  
                    if($arResult['TASK']['PROPS']['STATUS_DATE']['VALUE']) { 
                        ?> title="Статус установлен <?=$arResult['TASK']['PROPS']['STATUS_DATE']['VALUE'];?>"<? 
                    } 
                ?>>Статус: <span class="label label-success"><?= $arResult['STATUS_TEXT']; ?></span></p> 
                <? if ($arResult['TASK']['PROPS']['CALC']['VALUE']) { 
                    ?>
                    <p>Оценка: <span class="editable_show"><?=$arResult['TASK']['PROPS']['CALC']['VALUE']; ?></span>
                    <input name="NEW_CALC" data-edittask="Y" title="Пустое значение для оценки по факту" type="text" class="editable_hidden editcalc" value="<?= $arResult['TASK']['PROPS']['CALC']['VALUE']; ?>"> ч.</p>
                    <?
                } elseif($arResult['STATUS']) {
                    ?>
                    <p>Оценка: <span class="editable_show">по факту</span>
                    <input name="NEW_CALC" data-edittask="Y" title="Пустое значение для оценки по факту" type="text" class="editable_hidden editcalc" value=""><span class="editable_hidden"> ч.</span></p> 
                    <?
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