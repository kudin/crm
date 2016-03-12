<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<script>
    projects = {
        <? foreach($arResult['PROJECTS'] as $project) { ?>
           <?=$project['ID']?>: {name: '<?=$project['NAME'];?>'} ,
        <? } ?>
    };
    usersToProjects = {
        <? foreach($arResult['USER_TO_PROJECT'] as $user => $projects) { ?>
            <?=$user;?>: [<?=implode(',', $projects)?>] , 
        <? } ?>
    };
</script>
<div class="row report">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Отчёт</h2>  
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control" id="userid">
                            <?
                            foreach($arResult['USERS'] as $user) {
                                ?>
                                <option value="<?=$user['ID'];?>"><?=$user['FULL_NAME'];?></option>
                                <?
                                }
                            ?> 
                        </select>   

                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" style="width: 200px" name="reservation" id="reservation" class="form-control" value="03/18/<?=date('Y');?> - 03/23/<?=date('Y');?>" />
                        </div>

                    </div> 
                    <div class="col-md-4 col-sm-4">
                        <select class="form-control" id="userprojects" multiple="multiple"></select> 
                    </div> 
                    <div class="col-md-4 col-sm-4">
          
                    </div> 
                </div>    
                
                <div class="row">
                    <div class="col-md-12 col-sm-12">
 
                    </div> 
                </div>
            </div> 
        </div>
    </div>
</div>