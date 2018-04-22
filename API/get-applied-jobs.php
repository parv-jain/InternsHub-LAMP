<?php
header("Content-Type:application/json");
//connect

require_once 'db-config.php';

$res="false";

if(isset($_GET['student_id'])){
    if(!empty($_GET['student_id'])){

       $query = "SELECT j.job_title, j.job_desc, u.full_name from jobs j, applications a, users u where a.student_id = '".$_GET['student_id']."' AND a.job_id = j.job_id AND j.company_id = u.user_id";

        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) >= 1){
            $rows = array();
            while($row = mysqli_fetch_assoc($result)){
                $rows[]=$row;
            }
            $res = $rows;
        }
        else{
            $res="empty";
        }
    }else{
        $res="false";
    }
}else{
    $res="false";
}


print(json_encode($res));
?>
