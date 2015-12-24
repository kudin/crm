<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Отчёт</h2>  
                <div class="clearfix"></div>
            </div> 

            <div class="x_content"> 


                <div class="tasks_filter"> 

                    <div class="f1"><p>Проект</p></div>
                    <div class="f2"><select id="projects_list" class="form-control">
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
                    <div class="f2"><select id="tasks_sort_by" class="form-control">
                            <option>приоритету</option> 
                            <option>статусу</option> 
                        </select>
                    </div>  
                    <div class="f1"><p>Показать: </p></div>
                    <div class="f2"><select id="tasks_show" class="form-control"> 
                            <option>горящие</option>
                            <option>подходят сроки</option>
                            <option>начались</option>
                            <option>ожидают</option> 
                            <option>с превышением</option>
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
 
                        <div class="f3">      

                            <div class="f1"><p>Автор:</p></div>
                            <div class="f2">  <select class="select2_multiple form-control" multiple="multiple" name='CUSTOMER[]'>
                                    <?
                                    foreach ($arResult['CUSTOMERS_IDS'] as $userId) {
                                        $user = $arResult['USERS'][$userId];
                                        ?>
                                        <option value='<?= $user['ID']; ?>'><?= $user['NAME']; ?> <?= $user['LAST_NAME']; ?>  (<?= $user['LOGIN']; ?>) </option>
                                    <? }
                                    ?>
                                </select>
                            </div>   

                        </div>   


                        <div class="f3">      

                            <div class="f1"><p>Исполнитель:</p></div>
                            <div class="f2">
                                <select class="select2_multiple form-control" multiple="multiple" name='CUSTOMER[]'>
                                    <?
                                    foreach ($arResult['PROGRAMERS_IDS'] as $userId) {
                                        $user = $arResult['USERS'][$userId];
                                        ?>
                                        <option value='<?= $user['ID']; ?>'><?= $user['NAME']; ?> <?= $user['LAST_NAME']; ?>  (<?= $user['LOGIN']; ?>) </option>
                                    <? }
                                    ?>
                                </select>
                            </div>   

                        </div>   
                </div>    
                 
                <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th style="width: 20px;"><input type="checkbox" id="check-all" class="flat"></th>
                            <th class="column-title">Задача </th> 
                            <th class="column-title">Статус </th>
                            <th class="column-title">Приоритет </th>
                            <th class="column-title no-link last"><span class="nobr">Action</span></th>
                            <th class="bulk-actions" colspan="4">
                                <span class="antoo" style="color:#fff; font-weight:500;">Действия с задачами (<span class="action-cnt"></span>): <a href="#">Закрыть</a>, <a href="#">Удалить</a></span>
                            </th>
                        </tr>
                    </thead> 
                    <tbody>
                        <? foreach ($arResult['TASKS'] as $key => $task) {
                            ?>
                            <tr class="<?= $key % 2 ? 'even' : 'odd' ; ?> pointer">
                                <td class="a-center ">
                                    <input type="checkbox" class="flat" name="table_records">
                                </td>
                                <td>
                                    <a href='/tasks/<?= $arParams["PROJECT"] ?>/<?= $task['ID'] ?>/'><?= $task['NAME'] ?></a>
                                    <br>
                                    <small>без срока</small></td>  
                                <td><div class="progress progress_sm">
                                        <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 57%;" aria-valuenow="56"></div>
                                    </div>
                                    <small>Выполнена на 57%</small></td>
                                <td class="a-right a-right ">5</td>
                                <td class=" last"><a href='/tasks/<?= $arParams["PROJECT"] ?>/<?= $task['ID'] ?>/'>Просмотр</a>
                                </td>
                            </tr> 
                        <? } ?>
                    </tbody> 
                </table>


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
            </div> 
        </div>
    </div>
</div>