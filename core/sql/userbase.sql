
 CREATE  TABLE  `userbase` (  `id` int( 11  )  NOT  NULL  AUTO_INCREMENT ,
 `username` text NOT  NULL ,
 `password` varchar( 32  )  NOT  NULL ,
 `email` text NOT  NULL ,
 `userclass` int( 11  )  NOT  NULL ,
 `name` text NOT  NULL ,
 `temp_password` varchar( 32  )  NOT  NULL ,
 `temp_password_date` varchar( 30  )  NOT  NULL ,
 `temp_password_key` varchar( 32  )  NOT  NULL ,
 UNIQUE  KEY  `id` (  `id`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = latin1;