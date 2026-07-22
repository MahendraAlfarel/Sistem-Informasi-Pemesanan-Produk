<?php
// Memanggil Koneksi yang ada di database
$con = new mysqli('localhost','root','','db_ika');
if(!$con){
    die(mysqli_error($con));
}
?>
