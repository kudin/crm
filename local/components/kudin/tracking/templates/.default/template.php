<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!$arResult['TIME']) {
    return;
}
?>
<li>
    <a href="<?=$arResult['DETAIL_PAGE_URL']?>" title="#<?=$arResult['ID']?> <?=$arResult['NAME']?>">
        <i style="width: 15px;" class="fa fa-clock-o"></i> <span data-date="<?=$arResult['DATE'];?>" id="tracking_time"><?=$arResult['TIME'];?><span>
    </a>
</li>