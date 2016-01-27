<?php

/*  CREATE TABLE IF NOT EXISTS `views` (
        `ID` int(11) NOT NULL,
        `USER_ID` int(11) NOT NULL,
        `ITEM_ID` int(11) NOT NULL,
        `LAST_VIEW` datetime NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ALTER TABLE `views` ADD PRIMARY KEY (`ID`);
    ALTER TABLE `views` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT; */

class Views {
    
    public function view($id) {
        
    }
    
    public function getLastView($id) {
        
    }
    
}