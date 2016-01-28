<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Затраты</h2>  
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-12">  
                <?
                if ($arResult['IS_PROGRAMMER']) {
                    ?>
                    <div class="row trackingadd"> 
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text" placeholder="часов" class="form-control">
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12"> 
                            <input type="text" placeholder="что сделано" class="form-control">
                        </div>
                        <div class="col-md-12"> 
                            <button class="btn btn-success" type="submit"><i class="fa fa-clock-o"></i> Добавить</button>
                        </div>
                    </div>   
                <? } ?>
            </div>  
        </div>
    </div>
</div>