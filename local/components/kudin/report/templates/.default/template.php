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
                        <form method="POST">
                            <select class="form-control" id="userid">
                                <?
                                foreach ($arResult['USERS'] as $user) {
                                    ?>
                                    <option value="<?= $user['ID']; ?>"><?= $user['FULL_NAME']; ?></option>
                                    <?
                                }
                                ?> 
                            </select>    
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="reservation" id="reservation" class="form-control" value="<?= date('d.m.Y'); ?> - <?= date('d.m.Y'); ?>" />
                            </div> 
                            <select class="form-control" id="userprojects" multiple="multiple"></select>  
                            <button type="submit" class="btn btn-success"><i class="fa fa-table"></i> Создать отчёт</button>                        
                        </form>
                    </div>    
                </div>
            </div> 
        </div>
    </div> 
    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_title">
                <h2>Отчёт</h2>  
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <div class="row">
                    <div class="col-md-12 col-sm-12">

                    </div>    
                </div>
            </div> 
        </div>
    </div> 
</div>