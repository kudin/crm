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
            <img class="avatar" src="<?=$arResult['USERS'][$comment['CREATED_BY']]['PERSONAL_PHOTO'];?>">
            <div class="commentcalc">
                <a name="comment<?=$comment['ID']?>"></a>
                <? 
                if(!(($comment['CREATED_BY'] == $arResult['USER_ID']) &&
                      ($arResult['EDIT_COMMENT'] == $comment['ID']))) {
                    if($arResult['IS_PROGRAMMER'] || $arResult['IS_PROGRAMMER_AND_CUSTOMER']) {
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
                        <span class="label label-info">Оценён в <?=$comment['PROPERTY_CALC_VALUE'];?> ч. <a href="?action=cancel_status&id=<?=$comment['ID'];?>" class="status_cancel" title="Отменить оценку">x</a> </span>
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
                    } 
                }?> 
            </div>
            <span><a href="#comment<?=$comment['ID']?>">#<?=$comment['ID']?></a></span> <strong class="name"><?= $arResult['USERS'][$comment['CREATED_BY']]['FULL_NAME']; ?></strong>
            <span class="date"><?= $comment['DATE_CREATE']; ?></span> <?
            if(($comment['CREATED_BY'] == $arResult['USER_ID']) && ($arResult['EDIT_COMMENT'] != $comment['ID'])) { 
                ?><a href="?editcomment=<?=$comment['ID'];?>#comment<?=$comment['ID'];?>" title="Редактировать"><i class="fa fa-edit"></i></a> <a onclick="return confirm('Вы действительно хотите удалить этот комментарий?')" href="?delete_comment=<?=$comment['ID'];?>" title="Удалить"><i class="fa fa-remove"></i></a><?
            }
            if(($comment['CREATED_BY'] == $arResult['USER_ID']) && ($arResult['EDIT_COMMENT'] == $comment['ID'])) { ?>
                <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" enctype="multipart/form-data" class="commentEditForm">  
                    <textarea class="tiny" name="comment_text"><?= $comment['~PREVIEW_TEXT']; ?></textarea> 
                    <div class="row editfilerow">  
                        <div class="col-md-4 col-sm-4 col-xs-12 hiddenfiles" style="display: block;"> 
                            <p><a href="#" onclick="$('.add_editcomments_files').toggle(); return false;"><i class="fa fa-paperclip"></i> Добавить файлы</a></p>
                            <div class="add_editcomments_files">
                            <label class="form-control"><input type="file" name="attach[]"></label> 
                            <label class="form-control"><input type="file" name="attach[]"></label> 
                            <label class="form-control"><input type="file" name="attach[]"></label> 
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">  
                            <? if ($comment['FILES']) { ?>  
                                <p>Удалить файлы</p>
                                <? foreach ($comment['FILES'] as $file) { ?>
                                    <p title="<?= $file['ORIGINAL_NAME'] ?>"><input type="checkbox" name="deletefile[]" value="<?=$file['ID'];?>"> <i class="fa <?=$file['class'];?>"></i> <?= $file["TRUNCATED_NAME"]; ?></p> 
                                <? } ?>
                             <? } ?> 
                        </div>  
                        <div class="col-md-4 col-sm-4 col-xs-12"> 
                            <div class="compose-btn pull-right">
                                <input type="hidden" name="id" value="<?=$comment['ID'];?>">
                                <input type="submit" class="btn btn-sm btn-danger" name="cancel_edit_comment" value="Отмена">
                                <input type="submit" class="btn btn-sm btn-success" value="Изменить" name="edit_comment"> 
                            </div>
                        </div>  
                    </div>  
                </form>
            <? } else { ?>
                <div class="comment_text"><?= $comment['~PREVIEW_TEXT']; ?><?
                if ($comment['FILES']) { ?>
                <div class="attachment"> 
                    <ul><? 
                        foreach ($comment['FILES'] as $file) { ?>
                            <li>
                                <a class="atch-thumb" href="<?= $file['SRC'] ?>"><img title="<?=$file['ORIGINAL_NAME']?>" src="<?= $file['icon'] ?>"></a>
                                <br>
                                <div class="file-name">
                                    <a title="<?=$file['ORIGINAL_NAME']?>" href="<?= $file['SRC'] ?>"><?= $file["TRUNCATED_NAME"]; ?></a>
                                </div>
                                <br>
                                <span class="file-size"><?= $file["FILE_SIZE"] ?></span> 
                            </li> 
                        <? } ?>
                    </ul>
                </div>
                <? } ?> 
                </div>
            <? } ?>
        </div> 
    <? } ?>
    <div class="col-md-12"> 
        <a name="bottom"></a>
        <?
        if ($arResult['ERROR']) {
            ShowMessage($arResult['ERROR']);
        }
        ?>
        <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" enctype="multipart/form-data"> 
            <h4>Новый комментарий</h4>
            <textarea class="tiny" name="comment"></textarea> 
            <div class="row editfilerow">  
                <div class="col-md-4 col-sm-4 col-xs-12 hiddenfiles" style="display: block;"> 
                    <p><a href="#" onclick="$('.add_comments_files').toggle(); return false;"><i class="fa fa-paperclip"></i> Добавить файлы</a></p>
                    <div class="add_comments_files">
                        <label class="form-control"><input type="file" name="attach[]"></label> 
                        <label class="form-control"><input type="file" name="attach[]"></label> 
                        <label class="form-control"><input type="file" name="attach[]"></label> 
                    </div>
                </div> 
                <div class="col-md-8 col-sm-8 col-xs-12"> 
                    <div class="compose-btn pull-right">
                        <input type="submit" class="btn btn-sm btn-success" value="Отправить" name="add_comment"> 
                    </div>
                </div>  
            </div>
        </form>
    </div>  
</div>    
</div>