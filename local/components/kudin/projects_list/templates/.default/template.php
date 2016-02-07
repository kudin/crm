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
            <?if(count($arResult['ITEMS'])) {
                
                function drawProjectsHeadTh($name, $sort, $order) {
                    $names = array('name' => array('name' => 'Проект', 'desc' => 'Сортировка по названию')); 
                    if(!in_array($name, array_keys($names))) {
                        return;
                    }  
                    $newOrder = 'desc';
                    if($sort == $name) { 
                        ?><i class="fa fa-sort-alpha-<?=$order;?>"></i> <?  
                        $newOrder = ($order == 'asc' ? 'desc' : 'asc');
                    } 
                    ?><a <?if($names[$name]['desc']) { ?> title="<?=$names[$name]['desc'];?>" <? } ?> href="?sort=<?=$name;?>&order=<?=$newOrder;?>"><?=$names[$name]['name'];?></a><?
                }
                
                ?>
            <div class="x_content">  
                <table class="table table-striped projects" id="projects_list">
                    <thead>
                        <tr>  
                            <th>Лого</th> 
                            <th style="width: 20%;"><?drawProjectsHeadTh('name', $arResult['SORT'], $arResult['SORT_ORDER']);?></th> 
                            <th style="width: 40%;">Заказчик</th>
                            <th style="width: 40%;">Исполнитель</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($arResult['ITEMS'] as $project) {?>
                        <tr id="project<?=$project['ID']?>">
                            <td class="logos"> 
                            <? if($project['DETAIL_PICTURE']) { ?>
                                <a href='<?=TASKS_LIST_URL;?><?=$project['ID']?>/'><img src="<?=$project['DETAIL_PICTURE']['src']?>"></a>
                            <? } ?>
                            </td>  
                            <td class="project_name">
                                <a href='<?=TASKS_LIST_URL;?><?=$project['ID']?>/'><?=$project['NAME']?></a>
                                <?if($project['PROPERTIES']["URL"]["VALUE"]) { ?><br>
                                <small><a target="_blank" href="<?=fixUrl($project['PROPERTIES']["URL"]["VALUE"]);?>"><?=$project['PROPERTIES']["URL"]["VALUE"];?></a></small> 
                                <? } ?>
                            </td>
                            <td class="big-avatars circle_avatars">
                                <? if($project['PROPERTIES']['CUSTOMER']['VALUE']) { ?> 
                                <ul class="list-inline">
                                    <?foreach($project['PROPERTIES']['CUSTOMER']['VALUE'] as $userId) {?>
                                    <li>
                                        <a href="<?=USERS_LIST_URL;?><?=$userId;?>/"><img alt="<?=$arResult['USERS'][$userId]['NAME'];?>" title="<?=$arResult['USERS'][$userId]['NAME'];?> <?=$arResult['USERS'][$userId]['LAST_NAME'];?>" class="avatar" src="<?=$arResult['USERS'][$userId]['PERSONAL_PHOTO'];?>"><div class="username"><?=$arResult['USERS'][$userId]['FULL_NAME'];?></div></a>
                                    </li> 
                                    <?}?>
                                </ul>
                                <? } ?>
                            </td>
                            <td class="big-avatars circle_avatars"> 
                               <?if($project['PROPERTIES']['PROGRAMMER']['VALUE']) {?> 
                                <ul class="list-inline">
                                    <?foreach($project['PROPERTIES']['PROGRAMMER']['VALUE'] as $userId) {?>
                                    <li>
                                        <a href="<?=USERS_LIST_URL;?><?=$userId;?>/"><img alt="<?=$arResult['USERS'][$userId]['NAME'];?>" title="<?=$arResult['USERS'][$userId]['NAME'];?> <?=$arResult['USERS'][$userId]['LAST_NAME'];?>" class="avatar" src="<?=$arResult['USERS'][$userId]['PERSONAL_PHOTO'];?>"><div class="username"><?=$arResult['USERS'][$userId]['FULL_NAME'];?></div></a>
                                    </li> 
                                    <?}?>
                                </ul>
                                <? } ?>
                            </td>  
                        </tr> 
                        <? } ?>
                    </tbody>
                </table>
                <?=$arResult["NAV_STRING"];?>
             </div>
            <? } ?>
        </div>
    </div>
</div>