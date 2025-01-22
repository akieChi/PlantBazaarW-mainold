<?php 
$conn = mysqli_connect("localhost", "root", "", "plantbazaardb");
if(!$conn){
    echo "Connection error: ". mysqli_connect_error();
    
}
?>