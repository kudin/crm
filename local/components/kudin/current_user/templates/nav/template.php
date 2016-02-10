<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<li>
    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <img src="<?= $arResult['PERSONAL_PHOTO'];?>" alt=""><?= $arResult['FULL_NAME']; ?>
        <span class=" fa fa-angle-down"></span>
    </a>
    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
        <? if ($arResult['IS_ADMIN']) { ?>
            <li><a href="/bitrix/">Админка Bitrix</a></li>
        <? } ?>
        <li><a href="/cabinet/profile/">Профиль</a></li>
        <li><a href="/config/"><span>Настройки</span></a></li> 
        <li><a href="/?logout=yes"><i class="fa fa-sign-out pull-right"></i>Выйти</a>
        </li>
    </ul>
</li>