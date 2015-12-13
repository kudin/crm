<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Проекты</h2> 
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="add/">Добавить проект</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div>
            <?if(count($arResult['ITEMS'])) {?>
            <div class="x_content"> 
                <!-- start project list -->
                <table class="table table-striped projects">
                    <thead>
                        <tr> 
                            <th style="width: 20%">Проект</th>
                            <th>Программист</th>
                            <th>Cтатус</th> 
                            <th style="width: 20%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($arResult['ITEMS'] as $project) {?>
                        <tr> 
                            <td>
                                <a href='<?=$project['DETAIL_PAGE_URL']?>'><?=$project['NAME']?></a>
                                <br>
                                <small>Заказчик: #name#</small>
                            </td>
                            <td>
                                <ul class="list-inline">
                                    <li>
                                        <img alt="Avatar" class="avatar" src="/images/user.png">
                                    </li>
                                    <li>
                                        <img alt="Avatar" class="avatar" src="/images/user.png">
                                    </li>
                                </ul>
                            </td>
                            <td class="project_progress">
                                <div class="progress progress_sm">
                                    <div data-transitiongoal="57" role="progressbar" class="progress-bar bg-green" style="width: 57%;" aria-valuenow="56"></div>
                                </div>
                                <small>57% Complete</small>
                            </td> 
                            <td> 
                                <a class="btn btn-info btn-xs" href="<?=$project['DETAIL_PAGE_URL']?>edit/"><i class="fa fa-pencil"></i> Изменить </a>
                                <a class="btn btn-danger btn-xs" href="#"><i class="fa fa-trash-o"></i> Удалить </a>
                            </td>
                        </tr> 
                        <?}?>
                    </tbody>
                </table> 
            </div>
            <? } ?>
        </div>
    </div>
</div>