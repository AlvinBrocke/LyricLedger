<?php
//connect to the user account class
include("../classes/general_class.php");

//sanitize data



class MusicController {
    public static function getAllMusic() {
        return [
            new Music("HighLife", "Highlife", 13, "images/highlife.jpg"),
            new Music("Worship", "Gospel", 20, "images/worship.jpg"),
            new Music("Afropop", "Afropop", 10, "images/afropop.jpg"),
            new Music("Gospel", "Gospel", 15, "images/gospel.jpg"),
            new Music("Be My Valentine", "Love Songs", 10, "images/valentine.jpg"),
            new Music("Ono Ji Ono", "Afropop", 15, "images/onoji.jpg")
        ];
    }
}

// function add_user_ctr($a,$b,$c,$d,$e,$f,$g){
// 	$adduser=new customer_class();
// 	return $adduser->add_user($a,$b,$c,$d,$e,$f,$g);
// }


//--INSERT--//

//--SELECT--//

//--UPDATE--//

//--DELETE--//

?>