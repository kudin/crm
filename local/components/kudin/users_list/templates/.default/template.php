<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="row">
    <?
    foreach($arResult['USERS'] as $user) { ?>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="well profile_view">
                <div class="col-sm-12"> 
                    <div class="left col-xs-8">
                        <h2><?=$user['FULL_NAME']?></h2> 
                        <ul class="list-unstyled">
                            <li><i class="fa fa-mail-forward"></i> <?=$user['EMAIL']?></li> 
                        </ul>
                    </div>
                    <div class="right col-xs-4 text-center">
                        <a  href="/users/<?=$user['ID']?>/" ><img class="img-circle img-responsive" alt="<?=$user['FULL_NAME']?>" src="<?=$user['PERSONAL_PHOTO'];?>"></a>
                    </div>
                </div>
                <div class="col-xs-12 bottom text-center">
                    <div class="col-xs-12 col-sm-6 emphasis">

                    </div>
                    <div class="col-xs-12 col-sm-6 emphasis"> 
                        <a class="btn btn-primary btn-xs" href="/users/<?=$user['ID']?>/" type="button"> <i class="fa fa-user"></i> Просмотр профиля </a>
                    </div>
                </div>
            </div>
        </div>
    <? } ?>
</div>
