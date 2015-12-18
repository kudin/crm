<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?> 
                </div>
            </div>  
        </div> 
    </div>  
    <?if(defined('SHOWTINYSCRIPT')) { // если вставлять в шапку через $APPLICATION->AddHeadScript 
                                      // то при загрузне страницы браузер противно на пол секунды дёргается 
    ?>  <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script> <? } ?>
    <?ToolTip::ShowJs(true);?> 
</body> 
</html>