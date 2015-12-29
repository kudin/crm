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
                                <div>
                                    <?if($project['DETAIL_PICTURE']){ ?>
                                    <a href='/tasks/<?=$project['ID']?>/'><img src="<?=$project['DETAIL_PICTURE']['src']?>"></a><? } else { ?>
                                    <a href="<?= TASKS_LIST_URL . $project['ID'] . '/' ?>"><?= $project['NAME'] ?></a><? } ?>
                                </div> 
                            </li>
                        <? } ?>
                    </ul> 
                </div>
            <? } ?>
        </div>
    </div>
</div>