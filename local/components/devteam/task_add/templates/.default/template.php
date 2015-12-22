<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
 
if(!$arResult['PROGRAMERS_IDS']) {
    ShowError('Нельзя создать задачу в проекте без исполнителей или заказчиков'); 
    return;
}
  
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Новая задача в проекте "<?=$arResult['PROJECT']['NAME'];?>"</h2>
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="<?= TASKS_LIST_URL; ?><?=$arResult['PROJECT']['ID']?>/"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <form class="form-horizontal form-label-left" data-parsley-validate="" id="demo-form2" method="POST"> 
                    <div class="form-group">
                        <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12">Название задачи <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" name="name" class="form-control col-md-7 col-xs-12" required="required" id="name">
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="last-name" class="control-label col-md-3 col-sm-3 col-xs-12">Ответственный <span class="required">*</span>
                        </label> 
                        <div class="col-md-4 col-sm-4 col-xs-12"> 
                            <select class=" form-control"  name='PROGRAMMER'>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Описание </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea class="tiny" name="description"></textarea>
                        </div> 
                    </div> 
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Файлы</label>
                    <a class="btn" data-add-files><i class="fa fa-plus"></i> Вложить файл </a>
                        <div class="col-md-4 col-sm-4 col-xs-12 hiddenfiles" > 
                            <label class="form-control"><input type="file" name="file[]"></label> 
                            <label class="form-control"><input type="file" name="file[]"></label> 
                            <label class="form-control"><input type="file" name="file[]"></label> 
                        </div> 
                    </div>  
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button class="btn btn-success" type="submit" name="addtask" value="Y">Создать задачу</button>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>