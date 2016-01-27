<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();  
?> 
<div class="row"> 
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Внешнний вид</h2> 
                <div class="clearfix"></div>
            </div>
            <div class="x_content"> 
                <form class="form-horizontal form-label-left" method="POST"> 
                    <div class="form-group">
                        <label class="col-md-6 col-sm-6 col-xs-12 control-label">Показывать иконки проектов
                        </label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="Y" <?=$arResult['CONF']['show_project_logo_in_list'] ? ' checked ' : '';?> name='show_project_logo_in_list'> В списке задач
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="Y" <?=$arResult['CONF']['show_project_logo_in_titile'] ? ' checked ' : '';?> name='show_project_logo_in_titile'> В заголовке списка задач
                                </label>
                            </div>
                        </div>
                    </div> 
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6"> 
                            <input type='submit' class="btn btn-success" name='applyConfig' value='Применить'>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div> 
</div>