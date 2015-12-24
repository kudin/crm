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
                                <?= $arResult['TASK']['~DETAIL_TEXT'];?> 
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