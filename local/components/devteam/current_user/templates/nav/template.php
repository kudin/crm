<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
 
<li class=""> 
    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <img src="/images/user.png" alt=""><?= $arResult['LOGIN']; ?>
        <span class=" fa fa-angle-down"></span>
    </a>
    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
        <li><a href="/cabinet/profile/">Профиль</a>
        </li>
        <li>
            <a href="/cabinet/config/">
               <!--        <span class="badge bg-red pull-right">50%</span>-->
                <span>Настройки</span>
            </a>
        </li> 
        <li><a href="?logout=yes"><i class="fa fa-sign-out pull-right"></i>Выйти</a>
        </li>
    </ul>
</li>