<?php
//connect
require_once 'db-config.php';

//Add user check parameters of get request
$res="false";
//check if keys exist
if(isset($_GET['username']) && isset($_GET['password']) && isset($_GET['user_type']) && isset($_GET['full_name'])){
    //check if values of key are not empty
    if(!empty($_GET['username']) && !empty($_GET['password']) && !empty($_GET['user_type']) && !empty($_GET['full_name'])){

        echo $query = "Insert into users (username, password, user_type, full_name) values(
            '".$_GET['username']."',
            '".$_GET['password']."',
            '".$_GET['user_type']."',
            '".$_GET['full_name']."'
            )";

        $exec_query = mysqli_query($con, $query);

        if($exec_query){
            $res="true";
        }
        else{
            $res="false";
        }
    }
    else{
        $res="false";
    }
}
else{
    $res = "false";
}
echo $res;
?>
