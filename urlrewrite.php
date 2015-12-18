<?
$arUrlRewrite = array(
    
	array(
		"CONDITION" => "#^/tasks/([^/]+)/add/$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/tasks/add.php",
	), 
	array(
		"CONDITION" => "#^/tasks/([^/]+)/.*?\$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/tasks/list.php",
	),
);

?>