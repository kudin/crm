<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) {
        return;
    }
}
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<div class="dataTables_paginate paging_full_numbers" id="example_paginate">
    <? if($arResult['NavPageNomer'] != 1) { ?>
    <a class="first paginate_button paginate_button_disabled" tabindex="0" id="example_first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$page?>">Первая</a> 
    <? } 
    for($page = 1; $page <= $arResult['NavPageCount']; $page++ ) {?>
            <span><a<? if ($arResult['NavPageNomer'] == $page) { ?> class="paginate_active"<? } ?> href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$page?>"><?= $page; ?></a></span>
    <? }   
    if( $arResult['NavPageNomer'] != $arResult['NavPageCount']) {?>
    <a class="last paginate_button paginate_button_disabled" tabindex="0" id="example_last" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult['NavPageCount']?>">Последняя</a>
    <? } ?>
</div>