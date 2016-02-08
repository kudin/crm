<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>

<table class="data-table bx-forgotpass-table"> 
	<tbody>
		<tr>
			<td style="width: 70px;"><?=GetMessage("AUTH_LOGIN")?></td>
			<td><input type="text" class="form-control" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" /> 
			</td>
		</tr>
                <tr>
                    <td colspan="2"><?=GetMessage("AUTH_OR")?></td>
			 
		</tr>
                
		<tr> 
			<td><?=GetMessage("AUTH_EMAIL")?></td>
			<td>
				<input type="text" class="form-control" name="USER_EMAIL" maxlength="255" />
			</td>
		</tr>
	</tbody>
	<tfoot>
            <tr> <td></td>
			<td >
				<input type="submit" name="send_account_info" class="btn btn-success" value="<?=GetMessage("AUTH_SEND")?>" />
			</td>
		</tr>
	</tfoot>
</table>
<p>
<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a>
</p> 
</form>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
