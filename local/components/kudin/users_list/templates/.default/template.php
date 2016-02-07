<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="row">
    <? foreach ($arResult['USERS'] as $user) { ?>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="well profile_view">
                <div class="col-sm-12"> 
                    <div class="left col-xs-8">
                        <h2><?= $user['FULL_NAME'] ?></h2> 
                        <ul class="list-unstyled">
                            <li><i class="fa fa-mail-forward"></i> <?= $user['EMAIL'] ?></li> 
                        </ul> 
                    </div>
                    <div class="right col-xs-4 text-center">
                        <a  href="<?=USERS_LIST_URL;?><?= $user['ID'] ?>/" ><img class="img-circle img-responsive" alt="<?= $user['FULL_NAME'] ?>" src="<?= $user['PERSONAL_PHOTO']; ?>"></a>
                    </div>
                </div>
                <div class="col-xs-12 bottom text-center">
                    <div class="col-xs-12 col-sm-5 emphasis online_indicator"> 
                    <? if ($user['IS_ONLINE'] == 'Y') { ?>
                        <i class="fa fa-user"></i> Онлайн 
                    <? } ?>
                    </div>
                    <div class="col-xs-12 col-sm-7 emphasis">

                    </div> 
                </div>
            </div>
        </div>
    <? } ?>
</div>
