<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<li role="presentation" class="dropdown">
    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-envelope-o"></i>
        <?if($arResult['NEW']) { ?><span class="badge bg-green"><?= $arResult['NEW']; ?></span><? } ?>
    </a>
    <? if ($arResult['EVENTS']) { ?>
        <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
        <? foreach ($arResult['EVENTS'] as $event) {
            if($n++ >= 5) {
                break;
            }
            ?>
            <li><a href="<?= $event['LINK']; ?>">
                    <span class="image"><img src="<?= $arResult['USERS'][$event['FROM_USER']]['PERSONAL_PHOTO'];?>"></span>
                    <span><span><?= $arResult['USERS'][$event['FROM_USER']]['FULL_NAME']; ?></span>
                    <span class="time"><?= $event['DATE']; ?></span></span>
                    <span class="message<?if(!$event['VIEW']) { ?> red<? } ?>"><b><?= $event['TEXT_ACTION']; ?></b></span>
                    <span class="message"><?= $event['MESSAGE']; ?></span>
                </a>
            </li> 
        <? } ?>
            <li>
                <div class="text-center">
                    <a href="/">Все события <i class="fa fa-angle-right"></i></a>
                </div>
            </li>
        </ul>
    <? } ?> 
</li>