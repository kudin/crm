<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?> 
<div class="row taskrow">
    <div class="col-md-9">
        <div class="x_panel">
            <div class="x_title">
                <h2 class="editable_show"><?= $arResult['TASK']['NAME']; ?>
                    <div class="priorb prior<?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>" title="Приоритет: <?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>"><?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?></div>
                </h2> 
                <input data-edittask="Y" name="NAME_NEW" type="text" class="form-control editable_hidden" value="<?= $arResult['TASK']['NAME']; ?>" style="width: 66%"> 
                <select data-edittask="Y" name="priority" class="form-control prior<?=$arResult['TASK']['PROPS']['PRIORITY']['VALUE'];?> editable_hidden" name="priory" id="priory"><?
                for($prior = 0; $prior <= MAX_PRIORITY; $prior++) {
                    ?><option class="prior<?=$prior;?>" <?if($prior == $arResult['TASK']['PROPS']['PRIORITY']['VALUE']) {?> selected="selected" <? } ?>><?=$prior;?></option><?
                }
                ?></select>
                <ul class="nav navbar-right panel_toolbox">
                    <?if($arResult['CAN_EDIT']) {?>    
                        <li><a href="#" class="edit_task"><i class="fa fa-edit"></i> Редактировать</a></li> 
                    <? } ?>
                    <li><a href="<?=TASKS_LIST_URL;?><?= $arResult['PROJECT']['ID']; ?>/"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                <div class="clearfix"></div> 
            </div>
            <div class="x_content bootom_border ">  
                <div class="row">  
                    <div class="col-md-12">
                        <? if($arResult['CAN_EDIT']) { ?> 
                        <form class="edit_task_form" action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" enctype="multipart/form-data">  
                            <textarea name="new_task"><?= $arResult['TASK']['~DETAIL_TEXT'] ? $arResult['TASK']['~DETAIL_TEXT'] : $arResult['TASK']['NAME']; ?></textarea> 
                            <div class="row editfilerow">  
                                <div class="col-md-4 col-sm-4 col-xs-12 hiddenfiles" style="display: block;"> 
                                    <p><a href="#" onclick="$('.add_files').show(); return false;">Добавить файлы</a></p>
                                    <div class="add_files">
                                    <label class="form-control"><input type="file" name="attach[]"></label> 
                                    <label class="form-control"><input type="file" name="attach[]"></label> 
                                    <label class="form-control"><input type="file" name="attach[]"></label> 
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">  
                                    <? if ($arResult['TASK']['PROPS']['FILES']['VALUE']) { ?>  
                                        <p>Удалить файлы</p>
                                        <? foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as $file) { ?>
                                        <p title="<?= $file['ORIGINAL_NAME'] ?>"><input type="checkbox" name="deletefile[]" value="<?=$file['ID'];?>"> <?= $file["TRUNCATED_NAME"]; ?></p> 
                                        <? } ?>
                                     <? } ?> 
                                </div>  
                                <div class="col-md-4 col-sm-4 col-xs-12"> 
                                    <div class="compose-btn pull-right">
                                        <button class="btn btn-sm btn-danger edit_cancel">Отмена</button>
                                        <button class="btn btn-sm btn-success" name="edit_complete">Изменить</button>
                                    </div>
                                </div>  
                            </div>     
                        </form>
                        <? } ?>
                    </div>
                    <div class="col-md-12 task_content" >  
                        <div class="inbox-body">   
                            <div class="view-mail"><?= $arResult['TASK']['~DETAIL_TEXT'] ? $arResult['TASK']['~DETAIL_TEXT'] : $arResult['TASK']['NAME']; ?></div>
                            <?
                            if ($arResult['TASK']['PROPS']['FILES']['VALUE']) { ?>
                            <div class="attachment"> 
                                <ul><? 
                                    foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as $file) { ?>
                                        <li>
                                            <a class="atch-thumb" href="<?= $file['SRC'] ?>"><img title="<?=$file['ORIGINAL_NAME']?>" src="<?= $file['icon'] ?>"></a>
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
                                if($arResult['IS_PROGRAMMER'] || $arResult['IS_PROGRAMMER_AND_CUSTOMER']) {
                                    switch ($arResult['STATUS']) {
                                        case STATUS_LIST_CALC_AGRED:
                                            ?> 
                                            <p>В очереди на выполнение</p> 
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Начать</a>
                                            <? 
                                            break; 
                                        case STATUS_LIST_ACCEPT:
                                            ?>
                                            <p>Задача закрыта</p> 
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Возобновить</a>
                                            <?
                                            break;
                                        case false:
                                            ?>
                                            <form method="POST">
                                                <div class="col-md-6 col-sm-6 col-xs-12 form-group calcblock"> 
                                                    <input type="text" autocomplete="off" name="time" placeholder="Оценка в часах" class="form-control ">
                                                    <div class="btn-group">
                                                        <button type="submit" class="btn btn-success" name="docalc"><i class="fa fa-clock-o"></i> Оценить</button>
                                                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-success dropdown-toggle" type="button">
                                                            <span class="caret"></span> 
                                                        </button>
                                                        <ul role="menu" class="dropdown-menu"><li><a href="?action=fact">Оценить по факту</a></li></ul>
                                                    </div>
                                                    <input type="hidden" name="action" value="docalc">
                                                </div>
                                            </form>
                                            <?
                                            break;
                                        case STATUS_LIST_AGR_CALCED:
                                            if($arResult['TASK']['PROPS']['CALC']['VALUE']) { ?>
                                                <p>Задача оценена в <?=$arResult['TASK']['PROPS']['CALC']['VALUE']?> ч.</p>
                                            <? } else { ?><p>Задача оценена по факту</p><? } 
                                            if($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS']) { ?>
                                                <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Начать</a>
                                            <? } ?>
                                              <a href="?action=start" class="btn btn-success" type="button">Подтвердить оценку самостоятельно</a>
                                            <?
                                            break; 
                                        case STATUS_LIST_WORK:
                                            ?>
                                            <a class="btn btn-app" href="?action=stop"><i class="fa fa-pause"></i> Пауза</a>  
                                            <a class="btn btn-app" href="?action=complete"><i class="fa fa-flag"></i> Завершить</a>  
                                            <?
                                            break;
                                       case STATUS_LIST_REJECT:
                                            ?>
                                            <p>Задача отклонена</p>
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Доработать</a> 
                                            <a class="btn btn-app" href="?action=complete"><i class="fa fa-flag"></i> Завершить</a>
                                            <?
                                            break;
                                        case STATUS_LIST_PAUSE: 
                                            ?>
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Продолжить</a>
                                            <a class="btn btn-app" href="?action=complete"><i class="fa fa-flag"></i> Завершить</a>
                                            <?
                                            break;
                                        case STATUS_LIST_COMPLETE:
                                            ?>
                                            <p>Задача завершена</p>
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Возобновить</a>
                                            <? if($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS']) { ?>
                                                <a href="?action=closeTask" class="btn btn-success" type="button">Закрыть задачу</a>
                                            <? }
                                            break; 
                                        default:
                                            ?>
                                            <p><?=StatusHelper::getStr($arResult['STATUS'])?></p>
                                            <?
                                            break;
                                    }
                                }
                                if($arResult['IS_CUSTOMER']) {
                                    switch ($arResult['STATUS']) { 
                                        case STATUS_LIST_ACCEPT:
                                            ?>
                                            <p>Задача закрыта</p> 
                                            <a class="btn btn-app" href="?action=start"><i class="fa fa-play"></i> Возобновить</a>
                                            <?
                                            break;
                                        case STATUS_LIST_CALC_AGRED:
                                            ?>
                                            <p>В очереди на выполнение</p>
                                            <?
                                            break;
                                        case STATUS_LIST_CALC_REJECT:
                                            ?>
                                            <p>Оценка отклонена</p>
                                            <a href="?action=getnewcalc" class="btn btn-success" type="button">Запрость новую оценку</a>   
                                            <?
                                            break;
                                        case false:
                                            ?>
                                            <p>Ожидает оценки от <?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['FULL_NAME']; ?></p>  
                                            <?
                                            break;
                                        case STATUS_LIST_COMPLETE:
                                            ?>
                                            <p>Задача выполнена</p>
                                            <a href="?action=closeTask" class="btn btn-success" type="button">Принять задачу</a>
                                            <a href="?action=rejectTask" class="btn btn-danger" type="button">Отклонить задачу</a> 
                                            <? 
                                            break; 
                                        case STATUS_LIST_AGR_CALCED:
                                            if($arResult['TASK']['PROPS']['CALC']['VALUE']) { ?>
                                                <p>Задача оценена в <?=$arResult['TASK']['PROPS']['CALC']['VALUE']?> ч.</p>
                                            <? } else { ?><p>Задача оценена по факту</p><? } ?>
                                            <a href="?action=calcAgr" class="btn btn-success" type="button">Принять оценку</a>
                                            <a href="?action=calcReject" class="btn btn-danger" type="button">Отклонить оценку</a> 
                                            <?    
                                            break;
                                        default:
                                            ?>
                                            <p><?=StatusHelper::getStr($arResult['STATUS'])?></p>
                                            <? 
                                            break;
                                    } 
                                } 
                                ?>
                            </div> 
                        </div>  
                    </div>  
                </div>    
            </div>
            <?php
            include 'template_comments.php';
            ?>
        </div>  
    </div> 
    <div class="col-md-3"> 
        <div class="row">
            <?php
            include 'template_inform.php';
            include 'template_traking.php';
            ?> 
        </div> 
    </div>
</div>