<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="menu_section"> 
    <h3>Навигация</h3>
    <ul class="nav side-menu">  
        <?
        foreach ($arResult as $arItem) {
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) {
                continue;
            }
            ?> 
            <li<? if ($arItem["SELECTED"]) {
                ?> class="current-page"<? }
            ?>><a href="<?= $arItem["LINK"] ?>"><i class="fa <?= $arItem["PARAMS"]['CLASS']; ?>"></i><?= $arItem["TEXT"] ?></a></li>

        <? } ?>
    </ul>
</div>
<?
 
return;

?> 
    <ul class="nav side-menu"> 
        <li><a href="/projects/"><i class="fa fa-rocket"></i>Проекты</a></li> 
        <li><a href="/tasks/"><i class="fa fa-bug"></i>Задачи</a></li> 
        <li><a><i class="fa fa-wrench"></i> Настройки <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li><a href="/cabinet/profile/">Мой профиль</a>
                </li>
                <li><a href="media_gallery.html">Мои настройки</a>
                </li> 
            </ul>
        </li> 
        <li><a><i class="fa fa-calendar"></i> Отчёты <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li><a href="chartjs.html">Chart JS</a>
                </li>
                <li><a href="chartjs2.html">Chart JS2</a>
                </li>
                <li><a href="morisjs.html">Moris JS</a>
                </li> 
            </ul>
        </li>
    </ul>
 