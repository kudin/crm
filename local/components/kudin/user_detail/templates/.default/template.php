<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!function_exists('drawProgressBar')) {
    function drawProgressBar($arr) {
        $all = $arr['CALC_COMMENTS'];
        $track = $arr['TRACKING']; 
        if(!($all || $track)) {
            return;
        } 
        $class = "progress-bar-info";  
        $pr = round(($track / $all) * 100);
        if($pr > 100) { 
            $pr = 100;
            $class = 'progress-bar-danger';
        }  
        if(!$all) {
            $pr = 100;
        } 
        ?>
        <div class="progress" title="затрачено <?=$track; ?> из <?=$all; ?> ч.">
            <div class="progress-bar <?=$class;?>" style="width: <?=$pr;?>%;" aria-valuenow="<?=$pr;?>"></div>
        </div>
        <span class="small"><span title="Тайм-трекинг"><?=$track; ?></span> из <span title="Оценка"><?=$all; ?> ч.</span></span>
        <?
    } 
}
?>
<div class="col-md-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Статистика <?= $arResult['USERS'][$arParams['USER_ID']]['FULL_NAME']; ?></h2> 
            <div class="clearfix"></div>
        </div>
        <div class="x_content"> 
            <div data-example-id="togglable-tabs" role="tabpanel" class="">
                <ul role="tablist" class="nav nav-tabs bar_tabs" id="myTab">
                    <li class="active" role="presentation"><a aria-expanded="true" data-toggle="tab" role="tab" id="home-tab" href="#tab_content1">Задачи для <?= $arResult['USERS'][$arParams['USER_ID']]['FULL_NAME']; ?></a>
                    </li>
                    <li class="" role="presentation"><a aria-expanded="false" data-toggle="tab" id="profile-tab" role="tab" href="#tab_content2">Ожидают реакции постановщика <?= $arResult['USERS'][$arParams['USER_ID']]['FULL_NAME']; ?></a>
                    </li> 
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div aria-labelledby="home-tab" id="tab_content1" class="tab-pane fade active in" role="tabpanel"> 
                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr> 
                                    <th style="width: 190px;">
                                        <a href="/tasks/?filter=open&filter2=<?=$arParams['USER_ID']?>">Всего открытых задач:</a>
                                    </th>
                                    <th>
                                        <a href="/tasks/?filter=open&filter2=<?=$arParams['USER_ID']?>">
                                           <?=$arResult['ALL_PROGR_TASKS_CNT']?>
                                        </a>
                                    </th>
                                    <th class="hidden-phone" style="width: 240px;"> </th> 
                                </tr>
                            </thead>
                            <tbody> 
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=nocalc&filter2=<?=$arParams['USER_ID']?>">Ожидает оценки:</a>
                                    </td>
                                    <td colspan="2">
                                        <? if($arResult['COUNTERS'][0]['COUNT']) { ?>
                                            <a href="/tasks/?filter=nocalc&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][0]['COUNT'];?></a>
                                        <? } else {
                                        ?> - <?
                                        }?>
                                    </td>
                                </tr>   
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=complete&filter2=<?=$arParams['USER_ID']?>">Ожидает проверки:</a>
                                    </td> 
                                    <td>
                                        <? if($arResult['COUNTERS'][STATUS_LIST_COMPLETE]['COUNT']) { ?>
                                            <a href="/tasks/?filter=complete&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_COMPLETE]['COUNT']?></a>
                                        <? } else { ?> - <? } ?> 
                                    </td>
                                    <td class="vertical-align-mid">
                                        <?  
                                            drawProgressBar($arResult['COUNTERS'][STATUS_LIST_COMPLETE]); 
                                        ?>
                                    </td>
                                </tr> 
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=pause&filter2=<?=$arParams['USER_ID']?>">На паузе:</a>
                                    </td> 
                                    <td>
                                        <? if($arResult['COUNTERS'][STATUS_LIST_PAUSE]['COUNT']) { ?>
                                            <a href="/tasks/?filter=pause&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_PAUSE]['COUNT']?></a>
                                        <? } else { ?> - <? } ?> 
                                    </td>
                                    <td class="vertical-align-mid"> 
                                        <?  
                                             drawProgressBar($arResult['COUNTERS'][STATUS_LIST_PAUSE]); 
                                        ?>
                                    </td>
                                </tr>  
                                 <tr> 
                                    <td>
                                        <a href="/tasks/?filter=pause&filter2=<?=$arParams['USER_ID']?>">В очереди на выполнение:</a>
                                    </td> 
                                    <td>
                                        <? if($arResult['COUNTERS'][STATUS_LIST_CALC_AGRED]['COUNT']) { ?>
                                            <a href="/tasks/?filter=pause&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_CALC_AGRED]['COUNT']?></a>
                                        <? } else { ?> - <? } ?> 
                                    </td>
                                    <td class="vertical-align-mid"> 
                                        <?  
                                            drawProgressBar($arResult['COUNTERS'][STATUS_LIST_CALC_AGRED]); 
                                        ?>
                                    </td>
                                </tr>  
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=agrcalced&filter2=<?=$arParams['USER_ID']?>">Согласование оценки:</a>
                                    </td> 
                                    <td colspan="2">
                                        <? if($arResult['COUNTERS'][STATUS_LIST_AGR_CALCED]['COUNT']) { ?>
                                           <a href="/tasks/?filter=agrcalced&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_AGR_CALCED]['COUNT']?></a>
                                        <? } else { 
                                            ?> - <?
                                        } ?> 
                                    </td>  
                                </tr> 
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=reject&filter2=<?=$arParams['USER_ID']?>">Отклонена:</a></td> 
                                    <td>
                                        <? if($arResult['COUNTERS'][STATUS_LIST_REJECT]['COUNT']) { ?>
                                            <a href="/tasks/?filter=reject&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_REJECT]['COUNT']?></a>
                                        <? } else { ?> - <? } ?>
                                    </td>
                                    <td class="vertical-align-mid"> 
                                        <?  
                                            drawProgressBar($arResult['COUNTERS'][STATUS_LIST_REJECT]);
                                        ?>
                                    </td>
                                </tr>  
                                <tr> 
                                    <td>Сейчас в работе </td> 
                                    <td class="hidden-phone">
                                        <? if($arResult['CURRENT_TASK']) { ?>
                                            <a href="<?=$arResult['CURRENT_TASK']['DETAIL_PAGE_URL']?>">#<?=$arResult['CURRENT_TASK']['ID'];?> <?=$arResult['CURRENT_TASK']['NAME'];?></a>
                                            <br>
                                            <span class="small">в работе <?=$arResult['CURRENT_TASK']['TIME'];?></span>
                                        <? } else { ?>
                                            Задач не запущено
                                        <? } ?>
                                    </td>  
                                    <td class="vertical-align-mid">
                                        <? 
                                            drawProgressBar($arResult['COUNTERS'][STATUS_LIST_WORK]);
                                        ?>
                                    </td>
                                </tr> 
                             </tbody>
                        </table>  
                            <table class="data table table-striped no-margin topline">
                             <thead>
                                <tr> 
                                    <th style="width: 190px;">
                                        <a href="/tasks/?filter=end&filter2=<?=$arParams['USER_ID']?>">Всего закрытых задач:</a></th>
                                    <th>
                                     <? if($arResult['COUNTERS'][STATUS_LIST_ACCEPT]['COUNT']) { ?>
                                        <a href="/tasks/?filter=end&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['COUNTERS'][STATUS_LIST_ACCEPT]['COUNT']?></a>
                                        <? } else { ?> - <? } ?>
                                    </th>
                                    <th class="hidden-phone" style="width: 240px;">
                                    <?  
                                            drawProgressBar($arResult['COUNTERS'][STATUS_LIST_ACCEPT]);
                                    ?>
                                    </th> 
                                </tr>
                            </thead> 
                        </table> 
                    </div> 
                    
                    <div aria-labelledby="profile-tab" id="tab_content2" class="tab-pane fade" role="tabpanel"> 
                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr> 
                                    <th>Всего:</th>
                                    <th><?=$arResult['CUSTOMERS_COUNTERS_SUMM'];?></th> 
                                </tr>
                            </thead>
                            <tbody> 
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=agrcalced&filter2=<?=$arParams['USER_ID']?>">Согласование оценки:</a>
                                    </td>
                                    <td>
                                        <? if($arResult['CUSTOMERS_COUNTERS'][STATUS_LIST_AGR_CALCED]['COUNT']) { ?>
                                            <a href="/tasks/?filter=agrcalced&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['CUSTOMERS_COUNTERS'][STATUS_LIST_AGR_CALCED]['COUNT'];?></a>
                                        <? } else { ?> - <? } ?>
                                    </td>  
                                </tr>  
                                <tr> 
                                    <td>
                                        <a href="/tasks/?filter=complete&filter2=<?=$arParams['USER_ID']?>">Ожидает проверки:</a>
                                    </td> 
                                    <td> 
                                        <? if($arResult['CUSTOMERS_COUNTERS'][STATUS_LIST_COMPLETE]['COUNT']) { ?>
                                        <a href="/tasks/?filter=complete&filter2=<?=$arParams['USER_ID']?>"><?=$arResult['CUSTOMERS_COUNTERS'][STATUS_LIST_COMPLETE]['COUNT'];?></a>
                                        <? } else { ?> - <? } ?>
                                    </td> 
                                </tr> 
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>