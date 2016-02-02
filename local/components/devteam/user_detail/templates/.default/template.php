<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

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
                    <li class="" role="presentation"><a aria-expanded="false" data-toggle="tab" id="profile-tab" role="tab" href="#tab_content2">Задачи, поставленые от <?= $arResult['USERS'][$arParams['USER_ID']]['FULL_NAME']; ?></a>
                    </li> 
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div aria-labelledby="home-tab" id="tab_content1" class="tab-pane fade active in" role="tabpanel">

                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr> 
                                    <th>Всего открытых задач:</th>
                                    <th>Client Company</th>
                                    <th class="hidden-phone" style="width: 200px;"> </th> 
                                </tr>
                            </thead>
                            <tbody> 
                                <tr> 
                                    <td>Ожидает оценки:</td>
                                    <td>Deveint Inc</td>
                                    <td class="hidden-phone">18</td> 
                                </tr>  
                                <tr> 
                                    <td>Согласование оценки:</td>
                                    <td>Deveint Inc</td> 
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="45" class="progress-bar progress-bar-success" style="width: 45%;" aria-valuenow="45"></div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr> 
                                    <td>Ожидает проверки:</td> 
                                    <td class="hidden-phone">28</td>
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="75" class="progress-bar progress-bar-success" style="width: 75%;" aria-valuenow="75"></div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr> 
                                    <td>В очереди на выполнение:</td> 
                                    <td class="hidden-phone">28</td>
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="75" class="progress-bar progress-bar-success" style="width: 75%;" aria-valuenow="75"></div>
                                        </div>
                                    </td>
                                </tr>  
                                <tr> 
                                    <td>Сейчас в работе </td> 
                                    <td class="hidden-phone">название задачи</td>
                                    <td class="vertical-align-mid"> 
                                    </td>
                                </tr>  
                            </tbody>
                        </table> 
                    </div> 
                    <div aria-labelledby="profile-tab" id="tab_content2" class="tab-pane fade" role="tabpanel"> 
                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr> 
                                    <th>Всего открытых задач:</th>
                                    <th>Client Company</th>
                                    <th class="hidden-phone" style="width: 200px;"> </th>

                                </tr>
                            </thead>
                            <tbody> 
                                <tr> 
                                    <td>Согласование оценки:</td>
                                    <td>Deveint Inc</td> 
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="45" class="progress-bar progress-bar-success" style="width: 45%;" aria-valuenow="45"></div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr> 
                                    <td>Ожидает уточнения:</td>
                                    <td>Deveint Inc</td> 
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="75" class="progress-bar progress-bar-success" style="width: 75%;" aria-valuenow="75"></div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr> 
                                    <td>Ожидает проверки:</td> 
                                    <td class="hidden-phone">28</td>
                                    <td class="vertical-align-mid">
                                        <div class="progress">
                                            <div data-transitiongoal="75" class="progress-bar progress-bar-success" style="width: 75%;" aria-valuenow="75"></div>
                                        </div>
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