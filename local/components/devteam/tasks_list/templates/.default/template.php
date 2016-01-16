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
                            <div class="modal-header"> 
                                <h4 id="myModalLabel2" class="modal-title">Поставить задачу в проекте:</h4>
                            </div>
                            <div class="modal-body">  
                                <? 
                                foreach ($arResult['PROJECTS'] as $project) {
                                    ?>
                                <div class="proj"><a href="/tasks/<?= $project['ID'] ?>/add/"><?= $project['NAME'] ?></a></div>
                                    <?
                                }
                                ?> 
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default" type="button">Отмена</button> 
                            </div>

                        </div>
                    </div>
                </div>
            <?
        } ?>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">  
        <div class="tasks_filter">  
            <div class="f1"><p>Проект</p></div>
            <div class="f2"><select id="projects_list" class="form-control">
                    <option value="0">Все</option>
                    <? 
                    foreach ($arResult['PROJECTS'] as $project) {
                        ?>
                        <option <? if ($arParams["PROJECT"] == $project['ID']) { ?> selected="selected" <? } ?> value="<?= $project['ID'] ?>"><?= $project['NAME'] ?></option>  
                        <?
                    }
                    ?>
                </select>
            </div>  
            <div class="f1"><p>Сортировать по:</p></div>
            <div class="f2">
                <select id="tasks_sort_by" class="form-control">
                    <option value="date">Дате создания</option> 
                    <option value="priority">Приоритету</option>
                </select>
            </div>  
            <div class="f1"><p>Показать: </p></div>
            <div class="f2"><select id="tasks_show" class="form-control"> 
                    <option>горящие</option>
                    <option>подходят сроки</option>
                    <option>начались</option>
                    <option>ожидают</option>  
                </select></div> 
            <button class="btn btn-default adv_filterbtn" type="button">Расширеный фильтр</button> 
        </div> 
        <div class="tasks_advanced_filter"> 

            <div class="f3">                 
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="" checked="checked"> Открытые
                    </label>
                </div>       
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="" checked="checked"> Выполненные
                    </label>
                </div> 
            </div>        
        </div>    
        <? if (count($arResult['TASKS'])) { ?>
            <table class="table table-striped responsive-utilities jambo_table bulk_action" id="tasks_list">
                <thead>
                    <tr class="headings">
                        <th style="width: 20px;"><input type="checkbox" id="check-all" class="flat"></th>
                        <th class="column-title">Задача </th>  
                        <th class="column-title">Статус </th>
                        <th class="column-title last" style="width: 100px;">Приоритет </th> 
                        <th class="bulk-actions" colspan="4">
                            <span class="antoo" style="color:#fff; font-weight:500;">Действия с задачами (<span class="action-cnt"></span>):
                                <a href="#" data-mass-close>Закрыть</a>, <a href="#" data-mass-delete>Удалить</a></span>
                        </th>
                    </tr>
                </thead> 
                <tbody>
                    <? foreach ($arResult['TASKS'] as $key => $task) {
                        ?>
                        <tr class="pointer" id="task<?=$task['ID']?>">
                            <td class="a-center ">
                                <input type="checkbox" value="<?=$task['ID']?>" class="flat" name="table_records">
                            </td>
                            <td>
                                <a href='/tasks/<?= $arParams["PROJECT"] ?>/<?= $task['ID'] ?>/'><?= $task['NAME'] ?></a>
                                <br>
                                <small><?= $task['DATE_CREATE'] ?></small></td>   
                            <td><div class="progress progress_sm">
                                    <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 57%;" aria-valuenow="56"></div>
                                </div>
                                <small>Выполнена на 57%</small></td>
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
                    <a href="#">Показать выполненные</a> 
                </div>  
                <div class="col-md-6">
                    <p><b>0%</b> 0 из 112 часов</p >
                    <div class="progress progress_sm">
                        <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 7%;" aria-valuenow="56"></div>
                    </div> 
                    <h3>0:00 ч.</h3>
                </div>   
            </div>  
        <? } ?>
    </div> 
</div>
</div>
</div>