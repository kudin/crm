<?php

define('PROJECTS_IBLOCK_ID', 1);
define('TASKS_IBLOCK_ID', 2);
define('COMMENTS_IBLOCK_ID', 3);
 
define('DEFAULT_PRIORITY', 5);
define('MAX_PRIORITY', 9);
define('MAX_TASK_TIME', 480);

define('COMMENT_MAX_LENGHT', 40000);

define('STATUS_COMMENT_CALCED', 11);
define('STATUS_COMMENT_REJECT', 12);
define('STATUS_COMMENT_CONFIRM', 13);

define('STATUS_LIST_WORK', 1);
define('STATUS_LIST_PAUSE', 2);
define('STATUS_LIST_COMPLETE', 3); 
define('STATUS_LIST_ACCEPT', 4);
define('STATUS_LIST_REJECT', 5);
define('STATUS_LIST_AGR_CALCED', 6);
define('STATUS_LIST_CALC_REJECT', 7);
define('STATUS_LIST_CALC_AGRED', 10);

define('PROJECTS_LIST_URL', '/projects/');
define('TASKS_LIST_URL', '/tasks/');