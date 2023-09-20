<?php
require 'database.php';

if(isset($_GET['search'])){
    $page=1;
    $totalpage=1;
    $keyword=$_GET['search'];

 $query="SELECT * FROM person JOIN profile ON profile.person_id=person.id WHERE (person.full_name LIKE '%$keyword%' OR person.email LIKE '%$keyword%') ORDER BY person.id DESC";
$run=mysqli_query($conn,$query);
$result=mysqli_fetch_all($run,1);
}else{

    $page=$_GET['page']??1;
    $limit=5;
    $offset=($page-1)*$limit;

    
  $query="SELECT * FROM person JOIN profile ON profile.person_id=person.id ORDER BY person.id DESC LIMIT $offset,$limit";
  $run=mysqli_query($conn,$query);
$result=mysqli_fetch_all($run,1);


  
$query3="SELECT * FROM person";
$run3=mysqli_query($conn,$query3);
$totalresult = mysqli_num_rows($run3);
$totalpage=ceil($totalresult/$limit);

}





?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body style="background-color:#ddf0ff">
    <nav class="navbar bg-body-tertiary shadow-sm border-bottom">
        <div class="container-fluid d-flex justify-content-center">
            <a class="navbar-brand" href="#">
                <img src="php.png" alt="Logo" height="24"
                    class="d-inline-block align-text-top">
                CRUD Operations
            </a>
        </div>
    </nav>

    <div class="container">
<?php
if(isset($_GET['update'])){
    $stid=$_GET['update'];
    $query2="SELECT * FROM person JOIN profile ON profile.person_id=person.id WHERE person.id=$stid ";

$run2=mysqli_query($conn,$query2);

$st=mysqli_fetch_assoc($run2);

?>
<div class="card my-3">
            <div class="card-header">
                Update person
            </div>
            <div class="card-body">
                <form class="row g-3" method="post" action="action.php?updateperson=<?=$st['person_id']?>" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Profile Pic</label>
                        <input type="file" name="profile" class="form-control" id="inputEmail4">
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Full Name</label>
                        <input type="text" name="full_name" value="<?=$st['full_name']?>" class="form-control" placeholder="enter person full name"
                            id="inputPassword4" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date Of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?=date('Y-m-d',$st['dob'])?>" required>
                </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Id</label>
                        <input type="email" name="email" value="<?=$st['email']?>" placeholder="enter person email id" class="form-control" required>
                    </div>


                    <div class="col-12 text-end">
                         <a href="index.php" type="button" class="btn btn-dark">Add New person</a>
                        <button type="submit" class="btn btn-primary">Update person Details</button>
                    </div>
                </form>
            </div>
        </div>

<?php
}else{
    ?>
<div class="card my-3">
            <div class="card-header">
                Add persons
            </div>
            <div class="card-body">
                <form class="row g-3" method="post" action="action.php?addperson" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Profile Pic</label>
                        <input type="file" name="profile" class="form-control" id="inputEmail4" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="enter person full name"
                            id="inputPassword4" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date Of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Id</label>
                        <input type="email" name="email" placeholder="enter person email id" class="form-control" required>
                    </div>


                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Add To Database</button>
                    </div>
                </form>
            </div>
        </div>

    <?php
}
?>

        


        <div class="card my-3">
            <div class="card-header">
                person Lists
            </div>
            <div class="card-body d-flex flex-wrap">
               
                <div class="col-12">
                    <form>
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="enter person name/email"
                            aria-label="person name" value="<?=@$_GET['search']?>" aria-describedby="basic-addon2" required>
                        <button type="submit" class="input-group-text btn btn-primary" id="basic-addon2"><i class="bi bi-search"></i>
                            Search</button>
                    </div>
</form>
                </div>
                
                <?php
                if(!$result){
                    ?>
<div class="text-center py-3 col-12">
No Result Found
                </div>
                    <?php
                }
foreach($result as $person){
    ?>

 <!-- //person start -->
                <div class="col-12 col-md-6 col-lg-4 p-2">
                    <div class="d-flex gap-3 align-items-start border rounded shadow-sm p-2">
                        <img src="./uploads/<?=$person['image']?>" height="85px" width="85px"
                            class="rounded" />

                        <div class="">
                            <div class="small"><?=$person['full_name']?></div>
                            <div class="small"><i class="bi bi-envelope"></i> <?=$person['email']?></div>
                            <div class="small"><i class="bi bi-cake"></i> <?=date('d M,Y',$person['dob'])?></div>
                            <div><a href="?update=<?=$person['person_id']?>" class="text-decoration-none small"><i class="bi bi-pencil-square"></i>
                                    Edit</a>
                                <a href="action.php?delete=<?=$person['person_id']?>" class="text-decoration-none text-danger small"><i class="bi bi-trash"></i>
                                    Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- //person end -->

    <?php
}
                ?>
                





            </div>
            <hr>
            <div class="d-flex justify-content-between p-3">
                <a href="?page=<?=($page-1)?>" class="btn btn-dark btn-sm <?=($page==1?'disabled':'')?>" ><i class="bi bi-arrow-left-circle"></i> Previous</a>
                <div>Page <?=$page?>/<?=$totalpage?></div>
                <a href="?page=<?=($page+1)?>"  class="btn btn-dark btn-sm <?=(($totalpage==1 || $totalpage==$page)?'disabled':'')?>" > Next <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>
</body>

</html>