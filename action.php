<?php
require 'database.php';

if(isset($_GET['addperson'])){

    

    $person_name=$_POST['full_name'];
    $person_email=$_POST['email'];
    $person_dob=strtotime($_POST['dob']);
    $imagename=rand(1,9999999).time().$_FILES['profile']['name'];
    move_uploaded_file($_FILES['profile']['tmp_name'],'./uploads/'.$imagename);

    $query="INSERT INTO person(full_name,email,dob) VALUES('$person_name','$person_email',$person_dob)";
    mysqli_query($conn,$query);

    $personid = mysqli_insert_id($conn);
    $query2="INSERT INTO profile(person_id,image) VALUES($personid,'$imagename')";
    mysqli_query($conn,$query2);
    
    header('location:index.php');

}


if(isset($_GET['delete'])){

    $personid = $_GET['delete'];
   
    $query="DELETE FROM person WHERE id=$personid";
    mysqli_query($conn,$query);

   
  $query2="DELETE FROM profile WHERE person_id=$personid";
    mysqli_query($conn,$query2);
    
    header('location:index.php');

}


if(isset($_GET['updateperson'])){

    $personid = $_GET['updateperson'];

    $person_name=$_POST['full_name'];
    $person_email=$_POST['email'];
    $person_dob=strtotime($_POST['dob']);



    $query="UPDATE person SET full_name='$person_name',email='$person_email',dob='$person_dob' WHERE id=$personid";
    mysqli_query($conn,$query);

    if($_FILES['profile']['name']!=''){
    $imagename=rand(1,9999999).time().$_FILES['profile']['name'];
    move_uploaded_file($_FILES['profile']['tmp_name'],'./uploads/'.$imagename);

    $query2="UPDATE profile SET image='$imagename' WHERE person_id=$personid";
    mysqli_query($conn,$query2);
    }
    
    
    
    header('location:index.php?update='.$personid);

}
