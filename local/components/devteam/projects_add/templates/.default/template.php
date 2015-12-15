<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Добавление проекта</h2>
            <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="/projects/"><i class="fa fa-arrow-left"></i> К списку проектов</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?if($arResult['ERROR']) {
                    ShowMessage($arResult['ERROR']);
                };?>
                <br>
                <form class="form-horizontal form-label-left" method="POST"> 
                    <div class="form-group">
                        <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12">Название <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control col-md-7 col-xs-12" required="required" id="first-name" name="name" > 
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Описание</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea name="description" class="form-control col-md-7 col-xs-12" ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Заказчик</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <select class="select2_multiple form-control" multiple="multiple" name='CUSTOMER[]'>
                                <?
                                foreach ($arResult['USERS'] as $user) {
                                    ?>
                                    <option value='<?=$user['ID'];?>'><?=$user['NAME'];?> <?=$user['LAST_NAME'];?>  (<?=$user['LOGIN'];?>) </option>
                                    <?
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Исполнитель</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <select class="select2_multiple form-control" multiple="multiple" name='PROGRAMMER[]'>
                                <? 
                                foreach ($arResult['USERS'] as $user) {
                                    ?>
                                    <option value='<?=$user['ID'];?>'><?=$user['NAME'];?> <?=$user['LAST_NAME'];?>  (<?=$user['LOGIN'];?>) </option>
                                    <?
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <input class="btn btn-success" type="submit" value="Добавить" name="add">
                        </div>
                    </div> 
            </div>
        </div>
       </form>
    </div>
</div>