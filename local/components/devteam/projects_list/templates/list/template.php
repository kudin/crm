<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Выберите проект:</h2>  
                <div class="clearfix"></div>
            </div>
            <? if (count($arResult['ITEMS'])) { ?>
                <div class="x_content"> 
                    <ul class="to_do">
                        <? foreach ($arResult['ITEMS'] as $project) { ?>
                            <li> 
                                <div><a href="<?= TASKS_LIST_URL . $project['ID'] . '/' ?>"><?= $project['NAME'] ?></a> 
                            </li>
                        <? } ?>
                    </ul> 
                </div>
            <? } ?>
        </div>
    </div>
</div>