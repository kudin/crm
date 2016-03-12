<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<script>
    projects = {
    <? foreach ($arResult['PROJECTS'] as $project) { ?>
        <?= $project['ID'] ?>: {name: '<?= $project['NAME']; ?>'},
    <? } ?>
    };
    usersToProjects = {
    <? foreach ($arResult['USER_TO_PROJECT'] as $user => $projects) { ?>
        <?= $user; ?>: [<?= implode(',', $projects) ?>],
    <? } ?>
    };
</script>
<div class="row report">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>Данные для отчёта</h2>  
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <form method="POST" id="reportform" target="_blank">
                            <select class="form-control" id="userid" name="user">
                                <? foreach ($arResult['USERS'] as $user) { ?>
                                <option <?if($arParams['USER_ID'] == $user['ID']) { ?> selected <? } ?> value="<?= $user['ID']; ?>"><?= $user['FULL_NAME']; ?></option>
                                <? } ?> 
                            </select>    
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="reservation" id="reservation" class="form-control" value="<?= date('1.m.Y'); ?> - <?= date('d.m.Y'); ?>" />
                            </div> 
                            <select class="form-control" id="userprojects" name="projects[]" multiple="multiple"></select>  
                            <button class="btn btn-success makereport"><i class="fa fa-table"></i> Создать отчёт</button>                  
                        </form>
                    </div>    
                </div>
            </div> 
        </div>
    </div> 
    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_title">
                <h2 class="report-title">Отчёт</h2>  
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="#" class="html_link"><i class="fa fa fa-file-o"></i></a></li> 
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <div class="row">
                    <div class="col-md-12 col-sm-12" id="report-area"> 
                        <?
                        if ($arResult['IS_REPORT']) {
                            foreach ($arResult['TASKS'] as $project => $tasks) {
                                $summ = 0;
                                ?> 
                                <table class="table table-bordered"> 
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
                                                <th width="10%" scope="row"><a target="_blank" href="<?= TASKS_LIST_URL ?><?= $project; ?>/<?= $task['ID']; ?>/"><?= $task['ID']; ?></a></th>
                                                <td><?= $task['NAME']; ?></td>
                                                <td><?= $task['TIME']; ?></td> 
                                            </tr> 
                                        <? } ?>   
                                        <tr>
                                            <td colspan="2"></td>
                                            <td><b><?= $summ; ?></b></td> 
                                        </tr> 
                                    </tbody>
                                </table> 
                            <? }
                            if ($arResult['ALLSUMM']) { ?>
                                <p>Всего времени: <b><?= $arResult['ALLSUMM']; ?></b></p>
                            <? } else { ?>
                                <p>Задач по выбраному фильтру не найдено</p>
                                <? }  
                            } ?>
                    </div>    
                </div>
            </div> 
        </div>
    </div> 
</div>