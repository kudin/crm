<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="x_content">  
<div class="row">  
    <? if (count($arResult['COMMENTS'])) { ?>                    
        <div class="col-md-12"> 
            <h4>Комментарии:</h4>
        </div> 
    <? }
    foreach ($arResult['COMMENTS'] as $comment) { ?> 
        <div class="col-md-12 comment">
            <img class="avatar" src="<?=$arResult['USERS'][$comment['CREATED_BY']]['PERSONAL_PHOTO']['src'];?>">
            <div class="commentcalc">
                <a name="comment<?=$comment['ID']?>"></a>
                <? 
                if($arResult['IS_PROGRAMMER']) {
                    switch ($comment['STATUS']) {
                        case false: 
                            ?>
                            <a href="#" class="showPanel" data-id="<?=$comment['ID']?>"><i class="fa fa-clock-o"></i> Оценить</a>
                            <div class="commnetcalcpanel" id="<?=$comment['ID']?>">
                                <form method="POST">
                                    <input type="text" class="form-control" placeholder="часы" name="timeComment" autocomplete="off"> 
                                    <button class="btn btn-success" type="submit"><i class="fa fa-clock-o"></i> Оценить</button>
                                    <input type="hidden" value="calccomment" name="action">
                                    <input type="hidden" value="<?=$comment['ID']?>" name="commentId">
                                </form>
                            </div> 
                            <? 
                            break; 
                        case STATUS_COMMENT_CALCED:
                            ?>
                            <span class="label label-info">Оценён в <?=$comment['PROPERTY_CALC_VALUE']?> ч.</span>
                            <?
                            break;
                        case STATUS_COMMENT_REJECT:
                            ?>
                            <span class="label label-danger">Отменён</span>
                            <?
                            break;
                        case STATUS_COMMENT_CONFIRM:
                            ?>
                            <span class="label label-success">В работе (<?=$comment['PROPERTY_CALC_VALUE'];?> ч.)</span> 
                             <?
                            break;
                        default:
                            break;
                    }
                }
                if($arResult['IS_CUSTOMER']) {
                    switch ($comment['STATUS']) {
                        case false: 
                            break;
                        case STATUS_COMMENT_CALCED:
                            ?>
                            <span class="label label-info">Оценён в <?=$comment['PROPERTY_CALC_VALUE']?> ч.</span>
                            <form method="POST">
                                <input type="submit" class="btn btn-success smallbtn" name="accept" value="Принять">
                                <input type="submit" class="btn btn-danger smallbtn" name="reject" value="Отклонить">
                                <input type="hidden" value="<?=$comment['ID']?>" name="commentId">
                                <input type="hidden" name="action" value="commentStatus">
                            </form>
                            <?
                            break;
                        case STATUS_COMMENT_REJECT:
                            ?>
                            <span class="label label-danger">Отменён</span>
                            <?
                            break;
                        case STATUS_COMMENT_CONFIRM:
                            ?>
                            <span class="label label-success">В работе (<?=$comment['PROPERTY_CALC_VALUE'];?> ч.)</span> 
                            <? 
                            break;
                        default:
                            break;
                    }
                }?> 
            </div>
            <strong class="name"><?= $arResult['USERS'][$comment['CREATED_BY']]['NAME']; ?> <?= $arResult['USERS'][$comment['CREATED_BY']]['LAST_NAME']; ?></strong>
            <span class="date"><?= $comment['DATE_CREATE']; ?></span> <?
            if($comment['CREATED_BY'] == $arResult['USER_ID']) { 
                ?><a href="#" title="Редактировать"><i class="fa fa-edit"></i></a><?
            } ?>
            <div class="comment_text"><?= $comment['~PREVIEW_TEXT']; ?></div> 
        </div> 
    <? } ?>
    <div class="col-md-12">
        <?
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