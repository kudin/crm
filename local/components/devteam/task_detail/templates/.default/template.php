<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-9">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= $arResult['TASK']['NAME']; ?>
                    <div class="priorb prior<?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>" title="Приоритет: <?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?>"><?= $arResult['TASK']['PROPS']['PRIORITY']['VALUE'] ?></div>
                </h2> 
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="/tasks/<?= $arResult['PROJECT']['ID']; ?>/"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                <div class="clearfix"></div> 
            </div>
            <div class="x_content bootom_border">  
                <div class="row"> 
                    <div class="col-md-9"> 
                        <div class="inbox-body">   
                            <div class="view-mail"> 
                                <?= $arResult['TASK']['~DETAIL_TEXT']; ?> 
                            </div>
                            <?
                            if ($arResult['TASK']['PROPS']['FILES']['VALUE']) {
                                ?>
                                <div class="attachment"> 
                                    <ul>
                                        <? foreach ($arResult['TASK']['PROPS']['FILES']['VALUE'] as $file) { ?>
                                            <li>
                                                <a class="atch-thumb" href="<?= $file['SRC'] ?>">
                                                    <img alt="img" src="<?= $file['icon'] ?>">
                                                </a>
                                                <br>
                                                <div class="file-name">
                                                    <?= $file["ORIGINAL_NAME"]; ?>
                                                </div>
                                                <br>
                                                <span class="file-size"><?= $file["FILE_SIZE"] ?></span> 
                                            </li> 
                                        <? } ?>
                                    </ul>
                                </div>
                            <? } ?> 
                            <div class="taskcontrol"> 
                                <?if($arResult['IS_PROGRAMMER']) { ?>
                                
                                <a class="btn btn-app">
                                    <i class="fa fa-play"></i> Начать
                                </a>
                                
                                <a class="btn btn-app">
                                    <i class="fa fa-pause"></i> Пауза
                                </a> 
                                
                                <a class="btn btn-app">
                                    <i class="fa fa-flag"></i> Завершить
                                </a>  
                                
                                <? } ?>
                                <? if($arResult['IS_CUSTOMER']) {
                                ?>
                                
                                <button class="btn btn-success" type="button">Принять</button>
                                <button class="btn btn-danger" type="button">Отклонить</button>
                                 
                                <?
                                } 
                                ?>
                            </div> 
                        </div>  
                    </div>  
                </div>    
            </div>
            <div class="x_content">  
                <div class="row"> 
                    <? if (count($arResult['COMMENTS'])) { ?>                    
                        <div class="col-md-12"> 
                            <h4>Комментарии:</h4>
                        </div> 
                    <? } ?>   
                    <? foreach ($arResult['COMMENTS'] as $comment) { ?> 
                        <div class="col-md-12 comment">  
                            <strong class="name"><?= $arResult['USERS'][$comment['CREATED_BY']]['NAME']; ?></strong>
                            <span class="date"><?= $comment['DATE_CREATE']; ?></span> 
                            <div><?= $comment['~PREVIEW_TEXT']; ?></div> 
                        </div>  
                    <? } ?> 
                    <div class="col-md-12"> <?
                        if ($arResult['ERROR']) {
                            ShowMessage($arResult['ERROR']);
                        }
                        ?>
                        <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" enctype="multipart/form-data"> 
                            <h4>Новый комментарий</h4>
                            <textarea class="tiny" name="comment"></textarea> 
                            <div class="compose-btn pull-right">
                                <input type="submit" class="btn btn-sm btn-success" value="Отправить" name="add_comment"> 
                            </div>
                        </form>
                    </div>  
                </div>    
            </div> 

        </div>  
    </div> 
    <div class="col-md-3">
        <div class="x_panel">
            <div class="x_title">
                <h2>Информация</h2>  
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12"> 
                    <p class="date">Создана: <?= $arResult['TASK']['DATE_CREATE'] ?></p>
                    <p>Постановщик: <?= $arResult['USERS'][$arResult['PROJECT']['CREATED_BY']]['NAME']; ?></p> 
                    <p>Исполнитель: <?= $arResult['USERS'][$arResult['TASK']['PROPS']['PROGRAMMER']['VALUE']]['NAME']; ?></p> 
                    <p class="status">Статус: <span class="label label-success"><?=$arResult['STATUS_TEXT'];?></span></p> 
                </div>  
            </div>    
        </div>
    </div>
</div>