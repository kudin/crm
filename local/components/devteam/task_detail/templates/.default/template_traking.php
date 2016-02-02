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
            <? if ($arResult['IS_PROGRAMMER']) { ?>
                <div class="col-md-12" id="trackingcol" data-task-id="<?= $arParams['ID'] ?>">   
                    <?
                    foreach ($arResult['TRACKING'] as $track) {
                        ?>
                        <div class="row trackingrow" data-track="<?= $track['ID']; ?>"> 
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <a href="#" class="removetrack"><i class="fa fa-remove"></i></a>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="#"><?= $track["PROPERTY_HOURS_VALUE"] ?> ч.</a>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-12"> 
                                <?= $track["DETAIL_TEXT"] ?>
                            </div>  
                        </div> 
                    <? } ?>
                    <div class="row trackingadd"> 
                        <div class="col-md-12 col-sm-12 col-xs-12 alltrackingsumm"> 
                            <? if($arResult['TASK']['PROPS']['TRACKING']['VALUE']) {?>
                            <p>Всего затрачено: <?=$arResult['TASK']['PROPS']['TRACKING']['VALUE'];?> ч.</p>
                            <? } else {
                                ?>
                                <p>Затраты не внесены</p>    
                                <?
                            } ?>
                        </div> 
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text" placeholder="часов" id="trackh" class="form-control">
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12"> 
                            <input type="text" placeholder="что сделано" id="trackdesc" class="form-control">
                        </div>
                        <div class="col-md-12"> 
                            <button class="btn btn-success" id="trackTime" type="submit"><i class="fa fa-clock-o"></i> Добавить</button>
                        </div>
                    </div>   
                </div>  
            <? } else { ?>
                <div class="col-md-12" id="trackingcol">  
                <?
                if ($arResult['TRACKING']) { 
                    foreach ($arResult['TRACKING'] as $track) { ?>
                        <div class="row trackingrow">  
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?= $track["PROPERTY_HOURS_VALUE"] ?> ч.
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12"> 
                                <?= $track["DETAIL_TEXT"] ?>
                            </div>  
                        </div> 
                        <? } 
                        if($arResult['TASK']['PROPS']['TRACKING']['VALUE']) {
                        ?>   
                        <div class="row">   
                            <div class="col-md-12 col-sm-12 col-xs-12"> 
                                Всего затрачено: <?=$arResult['TASK']['PROPS']['TRACKING']['VALUE'];?> ч.
                            </div>  
                        </div> <?
                        }
                    } else {
                        ?>
                        <div class="row">   
                            <div class="col-md-12 col-sm-12 col-xs-12"> 
                                Затраты не внесены
                            </div>  
                        </div>  
                        <? } ?>
                </div>  
                <? } ?>
        </div>
    </div>
</div> 
