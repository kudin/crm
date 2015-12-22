<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= $arResult['TASK']['NAME']; ?></h2> 
                <ul class="nav navbar-right panel_toolbox"> 
                    <li><a href="/tasks/20/"><i class="fa fa-arrow-left"></i> К списку задач</a></li> 
                </ul>
                <div class="clearfix"></div>
            </div> 

            <div class="x_content"> 

                <div class="row">
                    <div class="col-md-9">


                        <div class="inbox-body">  
                            <div class="view-mail"> 
                                <?= $arResult['TASK']['~DETAIL_TEXT']; ?> 
                            </div>
                            <div class="attachment">
                                <p>
                                    <span><i class="fa fa-paperclip"></i> 3 прикреплённых файла </span>  
                                </p>
                                <ul>
                                    <li>
                                        <a class="atch-thumb" href="#">
                                            <img alt="img" src="/images/1.png">
                                        </a>

                                        <div class="file-name">
                                            image-name.jpg
                                        </div>
                                        <span>12KB</span>


                                        <div class="links"> 
                                            <a href="#">Download</a>
                                        </div>
                                    </li>

                                    <li>
                                        <a class="atch-thumb" href="#">
                                            <img alt="img" src="/images/1.png">
                                        </a>

                                        <div class="file-name">
                                            img_name.jpg
                                        </div>
                                        <span>40KB</span>

                                        <div class="links"> 
                                            <a href="#">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="atch-thumb" href="#">
                                            <img alt="img" src="/images/1.png">
                                        </a>

                                        <div class="file-name">
                                            img_name.jpg
                                        </div>
                                        <span>30KB</span>

                                        <div class="links"> 
                                            <a href="#">Download</a>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="compose-btn pull-left">
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Ответить</a>
                                </button>
                            </div>
                        </div>


                    </div> 

                    <div class="col-md-3">

                    </div> 


                </div>    
            </div> 
        </div>
    </div>





</div>