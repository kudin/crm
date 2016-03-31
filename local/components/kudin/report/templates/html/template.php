<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!$arResult['IS_REPORT']) {
    return;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$arResult['USERS'][$arResult['USER']]['FULL_NAME'];?> <?=$arResult['RESERVATION'];?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            body {
                font-family: Verdana;
                font-size: 12px;
            }
            .table-bordered {
                border-spacing: 0;
                margin-bottom: 20px;
            } 
            .table-bordered > thead > tr > th,
            .table-bordered > tbody > tr > th,
            .table-bordered > tfoot > tr > th,
            .table-bordered > thead > tr > td,
            .table-bordered > tbody > tr > td,
            .table-bordered > tfoot > tr > td {
                border: 1px solid #eee; 
                padding: 2px 11px;
            }
        </style>    
    <p><?=$arResult['USERS'][$arResult['USER']]['FULL_NAME'];?><br>
    <?=$arResult['RESERVATION'];?></p>  
    <?
    foreach ($arResult['TASKS'] as $project => $tasks) {
        $summ = 0;
        ?> 
            <table class="table-bordered"> 
            <thead>
                <tr>
                    <th colspan="2"><?= $arResult['PROJECTS'][$project]['NAME']; ?></th>
                    <th width="10%">Затраты</th> 
                </tr>
            </thead>
            <tbody> 
                <?
                foreach ($tasks as $task) {
                    $summ += $task['TIME'];
                    ?> 
                    <tr>
                        <th scope="row" width="10%"><a target="_blank" href="http://<?=$_SERVER["SERVER_NAME"];?><?= TASKS_LIST_URL ?><?= $project; ?>/<?= $task['ID']; ?>/"><?= $task['ID']; ?></a></th>
                        <td><?= $task['NAME']; ?></td>
                        <td title="<?= $task['TIME_NAME']; ?>"><?= $task['TIME']; ?></td> 
                    </tr> 
                <? } ?>
                <tr>
                    <td colspan="2">Всего по проекту:</td>
                    <td><b><?= $summ; ?> ч.</b></td> 
                </tr> 
            </tbody>
        </table>
    <? }
    if ($arResult['ALLSUMM']) { ?>
        <p>Всего времени: <b><?= $arResult['ALLSUMM']; ?> ч.</b></p>
    <? } else { ?>
        <p>Задач по выбраному фильтру не найдено</p>
    <? } ?>
    </body>
</html>