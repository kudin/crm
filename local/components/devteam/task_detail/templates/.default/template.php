<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-9">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= $arResult['TASK']['NAME']; ?>
                    <div class="priorb prior<?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>" title="Приоритет: <?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>"><?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?></div>
                </h2> 
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="<?=TASKS_LIST_URL?><?= $arResult['PROJECT']['ID']; ?>/"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                <div class="clearfix"></div> 
            </div>
            <div class="x_content bootom_border">  
                <div class="row"> 
                    <div class="col-md-9"> 
                        <div class="inbox-body">   
                            <div class="view-mail"> 
                                <?= $arResult['TASK']['~DETAIL_TEXT'] ? $arResult['TASK']['~DETAIL_TEXT'] : $arResult['TASK']['NAME']; ?> 
                            </div>
                            <?
                            if ($arResult['TASK']['PROPS']['FILES']['VALUE']) { ?>
                            <div class="attachment"> 
                                <ul><? 
                                    foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as $file) { ?>
                                        <li>
                                            <a class="atch-thumb" href="<?= $file['SRC'] ?>">
                                                <img title="<?=$file['ORIGINAL_NAME']?>" src="<?= $file['icon'] ?>">
                                            </a>
                                            <br>
                                            <div class="file-name">
                                                <a title="<?=$file['ORIGINAL_NAME']?>" href="<?= $file['SRC'] ?>"><?= $file["TRUNCATED_NAME"]; ?></a>
                                            </div>
                                            <br>
                                            <span class="file-size"><?= $file["FILE_SIZE"] ?></span> 
                                        </li> 
                                    <? } ?>
                                </ul>
                            </div>
                            <? } ?> 
                            <div class="taskcontrol"> 
                                <?  
                                if($arResult['IS_PROGRAMMER']) {
                                    switch ($arResult['STATUS']) {
                                        case STATUS_LIST_ACCEPT:
                                            ?>
                                            <p>Задача закрыта</p>
                                            <?
                                            break;
                                        case false:
                                            ?>
                                            <form method="POST">
                                                <div class="col-md-6 col-sm-6 col-xs-12 form-group calcblock">
                                                    <input type="text" name="time" placeholder="Оценка в часах" class="form-control "> 
                                                    <button type="submit" class="btn btn-success" name="docalc"><i class="fa fa-clock-o"></i> Оценить</button>
                                                    <input type="hidden" name="action" value="docalc">
                                                </div>  
                                            </form>
                                            <?
                                            break;
                                        case STATUS_LIST_AGR_CALCED:
                                            ?> 
                                            <p>Задача оценена в <?=$arResult['TASK']['PROPS']['CALC']['VALUE']?> ч.</p>
                                            <?
                                            if($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS']) { ?>
                                                <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Начать</a>
                                            <?
                                            }
                                            break; 
                                        case STATUS_LIST_WORK:
                                            ?>
                                            <a class="btn btn-app" href="?action=stop"><i class="fa fa-pause"></i> Пауза</a>  
                                            <a class="btn btn-app" href="?action=complete"><i class="fa fa-flag"></i> Завершить</a>  
                                            <?
                                            break;
                                        case STATUS_LIST_PAUSE:
                                            ?>
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Продолжить</a>
                                            <?
                                            break;
                                        case STATUS_LIST_COMPLETE:
                                            ?>
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Возобновить</a>
                                            <? if($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS']) { ?>
                                                <a href="?action=closeTask" class="btn btn-success" type="button">Закрыть задачу</a>
                                            <? }
                                            break;
                                        default:
                                            break;
                                    }
                                }
                                if($arResult['IS_CUSTOMER']) {
                                    switch ($arResult['STATUS']) {
                                        case STATUS_LIST_COMPLETE:
                                            ?> 
                                            <a href="?action=closeTask" class="btn btn-success" type="button">Принять задачу</a>
                                            <button class="btn btn-danger" type="button">Отклонить задачу</button> 
                                            <? 
                                            break; 
                                        case STATUS_LIST_AGR_CALCED:
                                            ?> 
                                            <button class="btn btn-success" type="button">Принять оценку</button>
                                            <button class="btn btn-danger" type="button">Отклонить оценку</button> 
                                            <?    
                                            break;
                                        default:
                                            break;
                                    } 
                                } 
                                ?>
                            </div> 
                        </div>  
                    </div>  
                </div>    
            </div>
            <div class="x_content">  
                <div class="row"> 
                    <? if (count($arResult['COMMENTS'])) { ?>                    
                        <div class="col-md-12"> 
                            <h4>Комментарии:</h4>
                        </div> 
                    <? }    
                    foreach ($arResult['COMMENTS'] as $comment) { ?> 
                        <div class="col-md-12 comment">  
                            <strong class="name"><?= $arResult['USERS'][$comment['CREATED_BY']]['NAME']; ?></strong>
                            <span class="date"><?= $comment['DATE_CREATE']; ?></span> 
                            <div><?= $comment['~PREVIEW_TEXT']; ?></div> 
                        </div>  
                    <? } ?> 
                    <div class="col-md-12">
                        <?
                        if ($arResult['ERROR']) {
                            ShowMessage($arResult['ERROR']);
                        }
                        ?>
                        <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" enctype="multipart/form-data"> 
                            <h4>Новый комментарий</h4>
                            <textarea class="tiny" name="comment"></textarea> 
                            <div class="compose-btn pull-right">
                                <input type="submit" class="btn btn-sm btn-success" value="Отправить" name="add_comment"> 
                            </div>
                        </form>
                    </div>  
                </div>    
            </div>  
        </div>  
    </div> 
    <div class="col-md-3">
        <div class="x_panel">
            <div class="x_title">
                <h2>Информация</h2>  
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12">  
                    <p>Проект: <a href="<?=TASKS_LIST_URL?><?= $arResult['PROJECT']['ID']; ?>/"><?=$arResult['PROJECT']['NAME']?></a></p>
                    <p class="date">Создана: <?= $arResult['TASK']['DATE_CREATE'] ?></p>
                    <p>Постановщик: <?= $arResult['USERS'][$arResult['PROJECT']['CREATED_BY']]['NAME']; ?> <?= $arResult['USERS'][$arResult['PROJECT']['CREATED_BY']]['LAST_NAME']; ?></p> 
                    <p>Исполнитель: <?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['NAME']; ?> <?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['LAST_NAME']; ?></p> 
                    <? if($arResult['TASK']['PROPS']['CALC']['VALUE']) { ?>
                        <p>Оценка: <?=$arResult['TASK']['PROPS']['CALC']['VALUE'];?> ч.</p>
                    <? } ?>
                    <p class="status">Статус: <span class="label label-success"><?=$arResult['STATUS_TEXT'];?></span></p> 
                </div>  
            </div>    
        </div>
    </div>
</div>