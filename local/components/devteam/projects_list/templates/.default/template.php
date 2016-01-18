<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Проекты</h2> 
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="add/"><i class="fa fa-plus"></i> Добавить проект</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div>
            <?if(count($arResult['ITEMS'])) {?>
            <div class="x_content">  
                <table class="table table-striped projects" id="projects_list">
                    <thead>
                        <tr> 
                            <th style="width: 20%;">Проект</th> 
                            <th>Лого</th> 
                            <th>Заказчик</th>
                            <th>Исполнитель</th> 
                            <th style="width: 140px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($arResult['ITEMS'] as $project) {?>
                        <tr id="project<?=$project['ID']?>">  
                            <td class="project_name">
                                <a href='<?=TASKS_LIST_URL;?><?=$project['ID']?>/'><?=$project['NAME']?></a>
                                <br>
                                <small><?=$project['PREVIEW_TEXT'];?></small> 
                            </td>
                            <td class="logos"> 
                            <? if($project['DETAIL_PICTURE']) { ?>
                                <a href='/tasks/<?=$project['ID']?>/'><img src="<?=$project['DETAIL_PICTURE']['src']?>"></a>
                            <? } ?>
                            </td>  
                            <td class="big-avatars">
                                <? if($project['PROPERTIES']['CUSTOMER']['VALUE']) { ?> 
                                <ul class="list-inline">
                                    <?foreach($project['PROPERTIES']['CUSTOMER']['VALUE'] as $userId) {?>
                                    <li>
                                        <img alt="<?=$arResult['USERS'][$userId]['NAME'];?>" title="<?=$arResult['USERS'][$userId]['NAME'];?> <?=$arResult['USERS'][$userId]['LAST_NAME'];?>" class="avatar" src="<?=$arResult['USERS'][$userId]['PERSONAL_PHOTO']['src'] ? $arResult['USERS'][$userId]['PERSONAL_PHOTO']['src'] : '/images/user.png';?>">
                                    </li> 
                                     <?}?>
                                </ul>
                                <? } ?>
                            </td>
                            <td class="big-avatars"> 
                               <?if($project['PROPERTIES']['PROGRAMMER']['VALUE']) {?> 
                                <ul class="list-inline">
                                    <?foreach($project['PROPERTIES']['PROGRAMMER']['VALUE'] as $userId) {?>
                                    <li>
                                        <img alt="<?=$arResult['USERS'][$userId]['NAME'];?>" title="<?=$arResult['USERS'][$userId]['NAME'];?> <?=$arResult['USERS'][$userId]['LAST_NAME'];?>" class="avatar" src="<?=$arResult['USERS'][$userId]['PERSONAL_PHOTO']['src'] ? $arResult['USERS'][$userId]['PERSONAL_PHOTO']['src'] : '/images/user.png';?>">
                                    </li> 
                                     <?}?>
                                </ul>
                                <? } ?>
                            </td>  
                            <td><? if($arResult['HAS_RIGHTS_TO_DELETE_PROJECT']) { ?>
                                    <a href="#" data-deleteproject='<?=$project['ID']?>'><i class="fa fa-trash-o"></i> Удалить проект</a> 
                                <? } ?>
                            </td>
                        </tr> 
                        <? } ?>
                    </tbody>
                </table> 
            </div>
            <? } ?>
        </div>
    </div>
</div>