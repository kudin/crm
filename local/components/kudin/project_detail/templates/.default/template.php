<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arResult['ERROR']) {
    ShowMessage($arResult['ERROR']);
    return;
}
?><div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= $arResult['PROJECT']['NAME'] ?></h2> 
                <ul class="nav navbar-right panel_toolbox">  
                    <li><a href="<?= PROJECTS_LIST_URL ?>"><i class="fa fa-arrow-left"></i> К списку проектов</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div> 
            <div class="x_content"> 
                <div class="col-md-9 col-sm-9 col-xs-12"> 
                    <ul class="stats-overview">
                        <li>
                            <span class="name"><a href="<?= TASKS_LIST_URL ?><?= $arResult['PROJECT']['ID']; ?>/?filter=end">Выполнено задач</a></span>
                            <span class="value text-success"> 2300 </span>
                        </li>
                        <li>
                            <span class="name"><a href="<?= TASKS_LIST_URL ?><?= $arResult['PROJECT']['ID']; ?>/">Задач в работе</a></span>
                            <span class="value text-success"> 33 </span>
                        </li>
                        <li class="hidden-phone">
                            <span class="name"> Всего часов </span>
                            <span class="value text-success"> 20 </span>
                        </li>
                    </ul> 
                    <div> 
                        <h4>Последняя активность</h4> 
                        <ul class="messages">
                            <li>
                                <img alt="Avatar" class="avatar" src="/images/img.jpg">
                                <div class="message_date">
                                    <h3 class="date text-info">24</h3>
                                    <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                    <h4 class="heading">Desmond Davison</h4>
                                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                    <br>
                                    <p class="url">
                                        <span data-icon="" aria-hidden="true" class="fs1 text-info"></span>
                                        <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                                    </p>
                                </div>
                            </li>
                            <li>
                                <img alt="Avatar" class="avatar" src="/images/img.jpg">
                                <div class="message_date">
                                    <h3 class="date text-error">21</h3>
                                    <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                    <h4 class="heading">Brian Michaels</h4>
                                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                    <br>
                                    <p class="url">
                                        <span data-icon="" aria-hidden="true" class="fs1"></span>
                                        <a data-original-title="" href="#">Download</a>
                                    </p>
                                </div>
                            </li>
                            <li>
                                <img alt="Avatar" class="avatar" src="/images/img.jpg">
                                <div class="message_date">
                                    <h3 class="date text-info">24</h3>
                                    <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                    <h4 class="heading">Desmond Davison</h4>
                                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                    <br>
                                    <p class="url">
                                        <span data-icon="" aria-hidden="true" class="fs1 text-info"></span>
                                        <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                                    </p>
                                </div>
                            </li>
                        </ul> 
                    </div> 
                </div> 
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <section class="panel"> 
                        <div class="panel-body">
                            <? if($arResult['PROJECT']['PREVIEW_TEXT']) { ?>
                                <?= $arResult['PROJECT']['PREVIEW_TEXT']; ?>
                                <br><br> 
                            <? } ?>
                            <div class="project_detail"> 
                                <p class="title">Заказчик</p> 
                                <? foreach ($arResult['PROJECT']['PROPERTIES']['CUSTOMER']['VALUE'] as $userId) { ?> 
                                    <p><?= $arResult['USERS'][$userId]['NAME']; ?> <?= $arResult['USERS'][$userId]['LAST_NAME']; ?></p>
                                <? } ?> 
                                <p class="title">Исполнитель</p>
                                <? foreach ($arResult['PROJECT']['PROPERTIES']['PROGRAMMER']['VALUE'] as $userId) { ?> 
                                    <p><?= $arResult['USERS'][$userId]['NAME']; ?> <?= $arResult['USERS'][$userId]['LAST_NAME']; ?></p>
                                <? } ?> 
                            </div> 
                            <br>
                            <h5>Файлы проекта</h5>
                            <ul class="list-unstyled project_files">
                                <li><a href=""><i class="fa fa-file-word-o"></i> Functional-requirements.docx</a>
                                </li>
                                <li><a href=""><i class="fa fa-file-pdf-o"></i> UAT.pdf</a>
                                </li>
                                <li><a href=""><i class="fa fa-mail-forward"></i> Email-from-flatbal.mln</a>
                                </li>
                                <li><a href=""><i class="fa fa-file-word-o"></i> Contract-10_12_2014.docx</a>
                                </li>
                            </ul>
                            <br> 
                        </div> 
                    </section> 
                </div> 
            </div>
        </div>
    </div>
</div>