<?
$arUrlRewrite = array( 
	array(
		"CONDITION" => "#^/tasks/([^/]+)/add/$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/tasks/add.php",
	), 
	array(
		"CONDITION" => "#^/tasks/([^/]+)/([^/]+)/.*?\$#",
		"RULE" => "PROJECT=\$1&ID=\$2",
		"ID" => "",
		"PATH" => "/tasks/detail.php",
	),
	array(
		"CONDITION" => "#^/tasks/([^/]+)/.*?\$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/tasks/list.php",
	),
	array(
		"CONDITION" => "#^/projects/([^/]+)/.*?\$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/projects/detail.php",
	),
	array(
		"CONDITION" => "#^/users/([^/]+)/.*?\$#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/users/detail.php",
	),
);