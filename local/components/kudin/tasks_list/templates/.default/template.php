<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="row">
<div class="col-md-12">
<div class="x_panel">
    <div class="x_title">
        <? if($arParams["PROJECT"]) { ?>
            <h2<?
            if($GLOBALS['CRM_CONFIG']->getBool('show_project_logo_in_titile') && $icon = $arResult['PROJECTS'][$arParams["PROJECT"]]["DETAIL_PICTURE"]["src"]) {
                ?> class="vith-icon" style="background-image: url('<?=$icon;?>');" <?
            }
            ?>><?= $arResult['PROJECTS'][$arParams["PROJECT"]]['NAME']; ?></h2>
            <ul class="nav navbar-right panel_toolbox"> 
                <li><a href="add/"><i class="fa fa-plus"></i> Поставить новую задачу</a></li> 
            </ul> 
        <? } else { ?>
            <h2>Задачи по всем проектам</h2>
            <ul class="nav navbar-right panel_toolbox"> 
                <li><a href="#" data-target=".bs-example-modal-sm" data-toggle="modal"><i class="fa fa-plus"></i> Поставить новую задачу</a></li> 
            </ul>  
            <div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade bs-example-modal-sm" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content"> 
                        <div class="modal-header"><h4 id="myModalLabel2" class="modal-title">Поставить задачу в проекте:</h4></div>
                        <div class="modal-body"><?
                            foreach ($arResult['PROJECTS'] as $project) { ?>
                                <div class="proj"><a href="<?=TASKS_LIST_URL;?><?= $project['ID'] ?>/add/"><?= $project['NAME'] ?></a></div>
                            <? } ?> 
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Отмена</button> 
                        </div> 
                    </div>
                </div>
            </div>
        <? } ?>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">  
        <div class="tasks_filter">  
            <div class="fgroup">
                <div class="f1"><p>Проект</p></div>
                <div class="f2"><select id="projects_list" class="form-control" <? if(count($arResult['PROJECTS']) == 1) { ?> disabled="disabled" <? } ?>>
                        <? if(count($arResult['PROJECTS']) > 1) { ?>
                            <option value="0">Все</option>
                        <? } 
                        foreach ($arResult['PROJECTS'] as $project) {
                            ?>
                            <option <? if ($arParams["PROJECT"] == $project['ID']) { ?> selected="selected" <? } ?> value="<?= $project['ID'] ?>"><?= $project['NAME'] ?></option>  
                            <?
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="fgroup">
                <div class="f1"><p>Статус: </p></div>
                <div class="f2"><select id="tasks_show" class="form-control"> 
                    <? foreach(array('open' => 'Открытые', 
                                     'all' => 'Все',  
                                     'end' => 'Закрытые',
                                     false,
                                     'nocalc' => 'Ожидают оценки',
                                     'agrcalced' => StatusHelper::getStr(STATUS_LIST_AGR_CALCED),
                                     'calcreject' => StatusHelper::getStr(STATUS_LIST_CALC_REJECT),
                                     'calcagred' => 'Запущено в работу (оценка принята)',
                                     'work' => StatusHelper::getStr(STATUS_LIST_WORK),
                                     'pause' => 'В паузе',
                                     'complete' => 'Готово (не закрытые)',
                                     'reject' => 'Задача отклонена',
                                     false,
                                     'short' => 'Короткие ( <4ч. )',
                                     'norm' => 'Средние ( 4-16ч. )',
                                     'long' => 'Большие ( >16ч. )'
                                     ) as $code => $value) { 
                        if(!$value) {
                            ?><option disabled=""><?=str_repeat('-', 20);?></option>
                        <? } else { ?>
                            <option <?if($code == $arResult['FILTER']) { ?> selected="selected" <? } ?> value="<?=$code;?>"><?=$value;?></option> 
                        <? }  
                    } ?> 
                </select></div>  
            </div>
            <div class="fgroup">
                <div class="f1"><p>Задачи: </p></div>
                <div class="f2">
                <select id="tasks_show2" class="form-control"> 
                    <?
                    foreach(array( 
                        'my' => 'Мои (' . $arResult['USERS'][$arResult['USER_ID']]['FULL_NAME'] . ')',
                        'all' => 'Все',
                        'not_me' => 'Не мои',
                        false,
                    ) as $code => $value) {
                        if(!$value) {
                            ?><option disabled=""><?=str_repeat('-', 20);?></option>
                        <? } else { ?> 
                            <option <?if($code == $arResult['FILTER2']) { ?> selected="selected" <? } ?> value="<?=$code?>"><?=$value;?></option>
                        <?
                        }
                    } 
                    foreach($arResult['ALL_USERS'] as $userId) {
                        if($userId == $arResult['USER_ID']) {
                            continue;
                        }
                        ?>
                        <option <?if($userId == $arResult['FILTER2']) { ?> selected="selected" <? } ?> value="<?=$userId;?>"><?=$arResult['USERS'][$userId]['FULL_NAME'];?></option>
                        <?
                        }
                    ?>
                </select>
                </div>  
            </div> 
            <? if((!in_array($arResult['FILTER'], array('all', 'open'))) || ($arResult['FILTER2'] != 'my')) { ?>
            <button class="btn btn-default" type="button" id="reset_list_filter">Сбросить фильтр</button>
            <? } ?>
        </div>     
        <? if (count($arResult['TASKS'])) {
            
            function drawHeadTh($name, $sort, $order) {
                $names = array('priority' => array('name' => 'Приоритет'),
                               'calc' =>  array('name' => 'Оценка, часы'),
                               'date' => array('name' =>  'Задача', 'desc' => 'по дате создания задачи'),
                               'tracking' =>  array('name' => 'Затрачено'),
                               'project' =>  array('name' => 'Проект'),
                               'status' => array('name' => 'Статус', 'desc' => 'по дате изменения статуса'),
                               'ispolnitel' =>  array('name' => 'Исполнитель'),
                               'comments' =>  array('name' => 'Комментарии', 'desc' => 'по дате последнего комментария')
                    );
                if(!in_array($name, array_keys($names))) {
                    return;
                }  
                $newOrder = 'desc';
                if($sort == $name) { 
                    ?><i class="fa fa-sort-amount-<?=$order;?>"></i> <?  
                    $newOrder = ($order == 'asc' ? 'desc' : 'asc');
                } 
                ?><a <?if($names[$name]['desc']) { ?> title="<?=$names[$name]['desc'];?>" <? } ?> href="?sort=<?=$name;?>&order=<?=$newOrder;?>"><?=$names[$name]['name'];?></a><?
            }
            
            ?>
            <table class="table table-striped responsive-utilities jambo_table bulk_action" id="tasks_list">
                <thead>
                    <tr class="headings">
                        <th style="width: 20px;"></th>
                        <? if($GLOBALS['CRM_CONFIG']->getBool('show_project_logo_in_list') && !$arParams["PROJECT"]) { 
                            $colspan = 7; ?>
                        <th class="column-title column-project-ico"><?drawHeadTh('project', $arResult['SORT'], $arResult['SORT_ORDER']);?></th>  
                        <? } else {
                            $colspan = 6;
                        } ?>
                        <th class="column-title"><?drawHeadTh('date', $arResult['SORT'], $arResult['SORT_ORDER']);?></th> 
                        <th class="column-title" style="width: 130px;"><?drawHeadTh('ispolnitel', $arResult['SORT'], $arResult['SORT_ORDER']);?></th>
                        <th class="column-title" style="width: 160px;"><?drawHeadTh('status', $arResult['SORT'], $arResult['SORT_ORDER']);?></th>
                        <th class="column-title" style="width: 165px;"><?drawHeadTh('calc', $arResult['SORT'], $arResult['SORT_ORDER']);?></th> 
                        <th class="column-title" style="width: 115px;"><?drawHeadTh('tracking', $arResult['SORT'], $arResult['SORT_ORDER']);?></th> 
                        <th class="column-title" style="width: 130px;"><?drawHeadTh('comments', $arResult['SORT'], $arResult['SORT_ORDER']);?></th> 
                        <th class="column-title" style="width: 120px;"><?drawHeadTh('priority', $arResult['SORT'], $arResult['SORT_ORDER']);?></th>
                        <th class="bulk-actions" colspan="<?=$colspan;?>">
                            <span class="antoo" style="color:#fff; font-weight:500;">Действия с задачами (<span class="action-cnt"></span>):
                            <a href="#" data-mass-close>Закрыть</a>, <a href="#" data-mass-delete>Удалить</a></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($arResult['TASKS'] as $key => $task) { ?>
                    <tr class="pointer" id="task<?=$task['ID']?>">
                        <td class="a-center">
                            <input type="checkbox" value="<?=$task['ID']?>" class="flat" name="table_records">
                        </td>
                        <? if($GLOBALS['CRM_CONFIG']->getBool('show_project_logo_in_list') && !$arParams["PROJECT"]) { ?>
                            <td class="project-icon">
                                <?if($arResult['PROJECTS'][$task['PROPERTIES']['PROJECT']['VALUE']]["DETAIL_PICTURE"]["src"]) {?>
                                <a href='<?=TASKS_LIST_URL;?><?= $task['PROPERTIES']["PROJECT"]['VALUE'] ?>/<?if($GLOBALS['CRM_CONFIG']->get('project_icon_click_href') != 'list') { ?><?= $task['ID'] ?>/<? } ?>'><img src="<?=$arResult['PROJECTS'][$task['PROPERTIES']['PROJECT']['VALUE']]["DETAIL_PICTURE"]["src"];?>"></a>
                                <? } ?>
                            </td>  
                        <? } ?>
                        <td>
                            <a href='<?=TASKS_LIST_URL;?><?= $task['PROPERTIES']["PROJECT"]['VALUE'] ?>/<?= $task['ID'] ?>/'>#<?=$task['ID'];?> <?= $task['NAME'] ?></a>
                            <br>
                            <div class="small"><?= $task['DATE_CREATE'];?></div> 
                            <? if($task['NEW_STATUS']) { ?>
                               <div class="small red"><?=$task['NEW_STATUS'];?></div> 
                            <? } ?>
                        </td>    
                        <td>
                            <a title="<?=$arResult['USERS'][$task['PROPERTIES']['CUSTOMER']['VALUE']]['FULL_NAME'];?> &rarr; <?=$arResult['USERS'][$task['PROPERTIES']['PROGRAMMER']['VALUE']]['FULL_NAME'];?>" href="/users/<?=$task['PROPERTIES']['PROGRAMMER']['VALUE'];?>/"><?=$arResult['USERS'][$task['PROPERTIES']['PROGRAMMER']['VALUE']]['FULL_NAME'];?></a>
                        </td>
                        <td><?  $color = false;
                                if(($task['PROPERTIES']['PROGRAMMER']['VALUE'] == $arResult['USER_ID']) 
                                        && $task['PROPERTIES']['CUSTOMER']['VALUE'] == $arResult['USER_ID']) {
                                    switch ($task['STATUS']) {
                                        case STATUS_LIST_PAUSE:
                                        case STATUS_LIST_WORK:
                                        case STATUS_LIST_CALC_AGRED:
                                        case NULL:
                                            $color = 'green';
                                            break; 
                                        case STATUS_LIST_ACCEPT:
                                        case STATUS_LIST_CALC_REJECT:
                                            $color = 'gray'; 
                                            break;
                                    }     
                                } elseif ($task['PROPERTIES']['PROGRAMMER']['VALUE'] == $arResult['USER_ID']) { 
                                    switch ($task['STATUS']) {
                                        case STATUS_LIST_PAUSE:
                                        case STATUS_LIST_WORK:
                                        case STATUS_LIST_CALC_AGRED:
                                        case STATUS_LIST_REJECT:
                                        case NULL:
                                            $color = 'green';
                                            break; 
                                        case STATUS_LIST_ACCEPT:
                                        case STATUS_LIST_CALC_REJECT:
                                            $color = 'gray';
                                            break;
                                    }
                                } elseif ($task['PROPERTIES']['CUSTOMER']['VALUE'] == $arResult['USER_ID']) {
                                    switch ($task['STATUS']) {
                                        case STATUS_LIST_COMPLETE:
                                        case STATUS_LIST_AGR_CALCED:
                                            $color = 'green';
                                            break; 
                                        case STATUS_LIST_ACCEPT:
                                        case STATUS_LIST_CALC_REJECT:
                                            $color = 'gray';
                                            break;
                                    }
                                } else {
                                    switch ($task['STATUS']) { 
                                        case STATUS_LIST_ACCEPT:
                                        case STATUS_LIST_CALC_REJECT:
                                            $color = 'gray';
                                            break;
                                    }
                                } ?>
                            <p<?if($color) {
                                ?> class="status_<?=$color;?>" <?
                            }?>><?=$task['STATUS_TEXT'];?></p>
                            <? if($task['PROPERTIES']['STATUS_DATE']['VALUE']) {?>
                                <div class="small"><?=$task['PROPERTIES']['STATUS_DATE']['VALUE'];?></div>
                            <? } ?>
                        </td>   
                        <td><?
                        if($task['PROPERTIES']['CALC_COMMENTS']['VALUE']) { ?>
                            <span title="Суммарная оценка"><?= $task['PROPERTIES']['CALC_COMMENTS']['VALUE'] ?></span>
                            <? if($task['PROPERTIES']['CALC_COMMENTS']['VALUE'] && 
                                  $task['PROPERTIES']['CALC']['VALUE'] != $task['PROPERTIES']['CALC_COMMENTS']['VALUE']) { ?> 
                                (<span title="Оценка задачи"><?=$task['PROPERTIES']['CALC']['VALUE'] ? $task['PROPERTIES']['CALC']['VALUE'] : 'По факту';?> + <span title="Оценка комментариев"><?=$task['PROPERTIES']['CALC_COMMENTS']['VALUE'] - $task['PROPERTIES']['CALC']['VALUE'];?></span>) </span> 
                            <? } ?>
                        <? } elseif($task['STATUS']) {
                            ?>
                            <span title="Оценка задачи">по факту</span>
                            <?
                        } ?>
                        </td>
                        <td>
                            <?if($task['PROPERTIES']['TRACKING']['VALUE']) { ?>
                                <?=$task['PROPERTIES']['TRACKING']['VALUE']?>
                            <? } ?>
                        </td>    
                        <td>
                            <p>
                            <?if($task['NEW_COMMENTS']) {
                                ?>
                                <?=$task['PROPERTIES']['COMMNETS_CNT']['VALUE'];?>
                                <b>(<?=$task['NEW_COMMENTS'];?>)</b>
                                <?
                            } else {
                                ?>
                                <?= $task['PROPERTIES']['COMMNETS_CNT']['VALUE'] ? $task['PROPERTIES']['COMMNETS_CNT']['VALUE'] : '';?>
                                <?
                            }?></p>
                            <?if($task['PROPERTIES']['COMMENT_DATE']['VALUE']) {
                                ?><div class="small"><?=$task['PROPERTIES']['COMMENT_DATE']['VALUE'];?></div>
                            <?
                            }?>
                        </td>
                        <td class="last">
                            <div class="priorb prior<?= $task['PROPERTIES']['PRIORITY']['VALUE'] ?>" title="Приоритет: <?= $task['PROPERTIES']['PRIORITY']['VALUE'] ?>"><?= $task['PROPERTIES']['PRIORITY']['VALUE'] ?></div>
                        </td>
                    </tr>
                <? } ?>
                </tbody> 
            </table> 
            <?=$arResult["NAV_STRING"];?>
            <div class="row">
                <div class="col-md-6">
                    <p><b><?=$arResult['PERCENTS_CNT'];?>%</b> выполнено <?=$arResult['ACCEPTED_TASK_CNT'];?> из <?=$arResult['TASK_CNT'];?> заданий</p >
                    <div class="progress progress_sm">
                        <div role="progressbar" class="progress-bar bg-green" style="width: <?=$arResult['PERCENTS_CNT'];?>%;"></div>
                    </div>
                    <?if($arResult['FILTER'] != 'end') { ?>
                        <a href="?filter=end">Показать выполненные</a>
                    <? } else { ?>
                        <a href="?filter=open">Показать открытые</a>
                    <? } ?>
                </div>  
                <div class="col-md-6">
                    <p><b><?=$arResult['PERCENTS_TIME'];?>%</b> <?=$arResult['ACCEPTED_TASK_TIME'];?> из <?=$arResult['ALL_TASK_TIME'];?> часов</p >
                    <div class="progress progress_sm">
                        <div role="progressbar" class="progress-bar bg-green" style="width: <?=$arResult['PERCENTS_TIME'];?>%;"></div>
                    </div> 
                    <h3><?=$arResult['ACCEPTED_TASK_TIME'];?> ч.</h3>
                </div>   
            </div>  
        <? } else { ?>
            <div class="row">
                <div class="col-md-6 bottomtext"><p>
                    <? if(!in_array($arResult['FILTER'], array('all', 'open')) && $arResult['TASK_CNT']) { ?>
                        Результат фильтрации не вернул ни одной задачи. <a href="?filter=open&filter2=my">Сбросить фильтр</a>
                    <? } elseif($arResult['FILTER'] == 'open' && $arResult['TASK_CNT']) { ?> 
                        Открытых задач нет. <a href="?filter=all&filter2=my">Показать все мои задачи</a> 
                    <? } elseif($arParams['PROJECT']) { ?>
                        Задач нет. <a href="add/">Создать задачу</a> 
                    <? } else {
                        ?>
                        Задач нет. <a href="#" data-target=".bs-example-modal-sm" data-toggle="modal">Создать задачу</a>
                        <?
                    } ?>
                </p></div>
           </div>
        <? } ?>
    </div>
</div>
</div>
</div>