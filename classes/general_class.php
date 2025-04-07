<?php
//connect to database class
require("../settings/db_class.php");

/**
*General class to handle all functions 
*/
/**
 *@author David Sampah
 *
 */



class Music {
    public $title;
    public $genre;
    public $songs;
    public $image;

    public function __construct($title, $genre, $songs, $image) {
        $this->title = $title;
        $this->genre = $genre;
        $this->songs = $songs;
        $this->image = $image;
    }
}



//  public function add_brand($a,$b)
// 	{
// 		$ndb = new db_connection();	
// 		$name =  mysqli_real_escape_string($ndb->db_conn(), $a);
// 		$desc =  mysqli_real_escape_string($ndb->db_conn(), $b);
// 		$sql="INSERT INTO `brands`(`brand_name`, `brand_description`) VALUES ('$name','$desc')";
// 		return $this->db_query($sql);
// 	}
class general_class extends db_connection
{
	//--INSERT--//
	

	//--SELECT--//



	//--UPDATE--//



	//--DELETE--//
	

}

?>