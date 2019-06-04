<?php
include('../include/config.php');

$request = $_POST['request']; // request

// Get username list
if($request == 1){
 $search = $_POST['search'];

 $query = "SELECT * FROM services WHERE name like'%".$search."%'";
 $result = mysqli_query($db,$query);

 while($row = mysqli_fetch_array($result) ){
  $response[] = array("value"=>$row['id'],"label"=>$row['name']);
 }

 // encoding array to json format
 echo json_encode($response);
 exit;
}

// Get details
if($request == 2){
 $service_id = $_POST['serviceid'];
 $sql = "SELECT * FROM services WHERE id=".$service_id;

 $result = mysqli_query($db,$sql);

 $users_arr = array();

 while( $row = mysqli_fetch_array($result) ){
  $service_id = $row['id'];
  $service_name = $row['name'];
  $service_description = $row['description'];
  $service_price = $row['price'];

  $services_arr[] = array("serviceid" => $service_id, "name" => $service_name,"description" => $service_description, "price" =>$service_price);
 }

 // encoding array to json format
 echo json_encode($services_arr);
 exit;
}
