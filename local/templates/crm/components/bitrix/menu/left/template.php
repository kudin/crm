<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="menu_section">
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