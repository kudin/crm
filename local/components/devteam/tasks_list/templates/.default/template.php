<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="row">
<div class="col-md-12">
<div class="x_panel">
    <div class="x_title">
        <? if($arParams["PROJECT"]) { ?>
            <h2><?= $arResult['PROJECTS'][$arParams["PROJECT"]]['NAME']; ?></h2>
            <ul class="nav navbar-right panel_toolbox"> 
                <li><a href="add/"><i class="fa fa-plus"></i> Поставить новую задачу</a></li> 
            </ul> 
        <? } else {
            ?>
            <h2>Задачи по всем проектам</h2>
            <ul class="nav navbar-right panel_toolbox"> 
                <li><a href="#" data-target=".bs-example-modal-sm" data-toggle="modal"><i class="fa fa-plus"></i> Поставить новую задачу</a></li> 
            </ul>  
            <div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade bs-example-modal-sm" style="display: none;">
                <div class="modal-dialog modal-sm">
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
            <div class="f1"><p>Показать: </p></div>
            <div class="f2"><select id="tasks_show" class="form-control"> 
                <? foreach(array('all' => 'Все',  
                                 'open' => 'Открытые', 
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
            <? if(!in_array($arResult['FILTER'], array('all', 'open'))) { ?>
            <button class="btn btn-default" type="button" id="reset_list_filter">Сбросить фильтр</button>
            <? } ?>
        </div>     
        <? if (count($arResult['TASKS'])) {
            
            function drawHeadTh($name, $sort, $order) {  
                $names = array('priority' => 'Приоритет',
                               'calc' => 'Оценка, часы',
                               'date' => 'Дата создания',
                               'name' => 'Задача');
                if(!in_array($name, array_keys($names))) {
                    return;
                }  
                $newOrder = 'desc';
                if($sort == $name) { 
                    ?><i class="fa fa-sort-<?=$name == 'name' ? 'alpha' : 'amount';?>-<?=$order;?>"></i><?  
                    $newOrder = ($order == 'asc' ? 'desc' : 'asc');
                }
                ?>
                    <a href="?sort=<?=$name;?>&order=<?=$newOrder;?>"><?=$names[$name];?></a>
                <?
            }
            
            ?>
            <table class="table table-striped responsive-utilities jambo_table bulk_action" id="tasks_list">
                <thead>
                    <tr class="headings">
                        <th style="width: 20px;"></th>
                        <th class="column-title">
                            <?drawHeadTh('name', $arResult['SORT'], $arResult['SORT_ORDER']);?>
                        </th>  
                        <th class="column-title">Статус </th>
                        <th class="column-title">
                            <?drawHeadTh('calc', $arResult['SORT'], $arResult['SORT_ORDER']);?>
                        </th>
                        <th class="column-title">
                            <?drawHeadTh('date', $arResult['SORT'], $arResult['SORT_ORDER']);?>
                        </th>
                        <th class="column-title last" style="width: 120px;">
                            <?drawHeadTh('priority', $arResult['SORT'], $arResult['SORT_ORDER']);?>
                        </th>
                        <th class="bulk-actions" colspan="6">
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
                        <td>
                            <a href='<?=TASKS_LIST_URL;?><?= $task['PROPERTIES']["PROJECT"]['VALUE'] ?>/<?= $task['ID'] ?>/'>#<?=$task['ID'];?> <?= $task['NAME'] ?></a>
                            <br>
                            <small><?= $task['DATE_CREATE'] ?></small>
                        </td>   
                        <td>
                            <p><?=$task['STATUS_TEXT'];?></p>
                        </td>   
                        <td><? if($task['PROPERTIES']['CALC']['VALUE']) { ?>
                            <span title="Оценка задачи"><?= $task['PROPERTIES']['CALC']['VALUE'] ?></span>
                            <? if($task['PROPERTIES']['CALC_COMMENTS']['VALUE']) { ?> 
                                + <span title="Оценка комментариев"><?=$task['PROPERTIES']['CALC_COMMENTS']['VALUE'];?></span> = <span title="Суммарная оценка"><?= $task['PROPERTIES']['CALC']['VALUE'] + $task['PROPERTIES']['CALC_COMMENTS']['VALUE']?></span>
                            <? } ?>
                        <? } ?>
                        </td> 
                        <td>
                            <?= $task['DATE_CREATE'] ?>
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
                    <p><b>0%</b> выполнено 0 из 3 заданий</p >
                    <div class="progress progress_sm">
                        <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 57%;" aria-valuenow="56"></div>
                    </div>
                    <?if($arResult['FILTER'] != 'end') {?>
                        <a href="?filter=end">Показать выполненные</a>
                    <? } else {
                        ?>
                        <a href="?filter=open">Показать открытые</a>
                        <?
                    }?>
                </div>  
                <div class="col-md-6">
                    <p><b>0%</b> 0 из 112 часов</p >
                    <div class="progress progress_sm">
                        <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 7%;" aria-valuenow="56"></div>
                    </div> 
                    <h3>0:00 ч.</h3>
                </div>   
            </div>  
        <? } else {
            if(!in_array($arResult['FILTER'], array('all', 'open'))) { ?>
                <div class="row"><div class="col-md-6" style="padding-top: 10px;">
                <p>Результат фильтрации не вернул ни одной задачи. <a href="?filter=open">Сбросить фильтр</a></p>
                </div></div>
            <? }  
        }?>
    </div> 
</div>
</div>
</div>