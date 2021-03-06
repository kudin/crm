<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="col-md-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Последняя активность пользователя <?= $arResult['USERS'][$arParams['USER_ID']]['FULL_NAME']; ?></h2> 
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <?
        if (count($arResult['EVENTS'])) { ?>
            <ul class="list-unstyled msg_list big-imgs"> 
            <? foreach ($arResult['EVENTS'] as $event) { ?>
                <li<?if($event['PIC']) { ?> class="with_image" style="background-image: url('<?=$event['PIC']['src'];?>');"<? } ?>>
                <a href="<?= $event['LINK']; ?>"><span class="image"><img alt="<?= $arResult['USERS'][$event['FROM_USER']]['FULL_NAME']; ?>" src="<?= $arResult['USERS'][$event['FROM_USER']]['PERSONAL_PHOTO']; ?>"></span>
                    <span>
                        <span><?= $event['TEXT_ACTION']; ?></span>
                        <span class="time"><?= $event['DATE_FORMATED'] ?></span> 
                    </span> 
                    <span class="message"><?= $event['MESSAGE'] ?></span></a>
               </li>
            <? } ?>
            </ul>
        <? } ?>
        </div>
    </div>
</div>