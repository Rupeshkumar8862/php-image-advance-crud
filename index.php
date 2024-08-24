<?php 

error_reporting(0);
$conn=mysqli_connect("localhost","root","","test") or die("connection failed");
if($conn){
    // echo 'connection succussful';
    }
//delete
if($_GET['del']){
    $delsql="DELETE FROM image WHERE test_id= '".$_GET['del']."'";
    $delsqlquery=mysqli_query($conn,$delsql) or die("query failed");
    $rowimgedelete= mysqli_fetch_assoc($delsqlquery);
    // echo '<pre>'; print_r($rowimgedelete); echo '</pre>';  die();
    unlink("upload/".$rowimgedelete['test_image']);
    $sql= "DELETE FROM image WHERE test_id='".$_GET['del']."'";
    if($runsql= mysqli_query($conn,$sql)){
        header("location:index.php");
        ?>
<meta HTTP-EQUIV="Refresh" content="2; URL=index.php">
<?php 
    }else { echo "Query failed"; } }
// fetch 
if($_GET['uid']){
 $fetsql="SELECT *FROM image WHERE test_id = '".$_GET['uid']."'";
 $fetquery= mysqli_query($conn,$fetsql) or die("fetch query faield");
//  $editsingledata= mysqli_fetch_all($fetquery);
 $editsingledata= mysqli_fetch_assoc($fetquery);
//  echo '<pre>'; print_r($editsingledata) ;echo '</pre>';
//  echo $editsingledata['test_name'];
 }

 //update
 if(isset($_POST['save'])){
    if(@$_POST['test_id']!=''){
     $target= $_POST['uploadfile_old'];
    if($_FILES['uploadfile']['name']!="") {
    $errors = array();
    $file_name=$_FILES['uploadfile']['name'];
    $file_type=$_FILES["uploadfile"]['type'];
    $tmp_name=$_FILES['uploadfile']['tmp_name'];
    // $errors=$_FILES['uploadfile']['error'];
    $file_size=$_FILES['uploadfile']['size'];
    $file_ext = end(explode('.',$file_name));
    // file name se hta ke sirf extention deta h
    $extensions = array("jpeg","jpg","png");
    //print_r( $extensions = array("jpeg","jpg","png"));   // array hai 
    //output  Notice: Only variables should be passed by reference inArray ( [0] => jpeg [1] => jpg [2] => png )
        if($file_size > 2097152){
        $errors[] = "File size must be 2mb or lower.";
        }
      
        if(in_array($file_ext,$extensions) === false)
        {
        $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
         }

        $new_name = time(). "-".basename($file_name);  // create randome new name
        $target = "upload/".$new_name;

        if(empty($errors) == true){
        move_uploaded_file($tmp_name,$target);
        }else{
        print_r($errors);
        die();
        }
    }
      $updatesql= "UPDATE image SET test_name='".$_POST['name']."',test_image='$target'  WHERE test_id ='".$_POST['test_id']."'";
      $updatequery=mysqli_query($conn,$updatesql) or die('update query failed ');
     if($updatequery){
     echo '<script> alert("data updated successfully")<script>';   ?>
     <meta HTTP-EQUIV="Refresh" content="3; URL=index.php">
<?php  } } }
if(isset($_POST['test_id'])){
     if($_POST['test_id']==""){
    // if(!empty($_POST['name'])  &&  !empty($_POST['uploadfile'])){
    // print_r($_FILES);
    $errors = array();
    $file_name=$_FILES['uploadfile']['name'];
    $file_type=$_FILES["uploadfile"]['type'];
    $tmp_name=$_FILES['uploadfile']['tmp_name'];
    // $errors=$_FILES['uploadfile']['error'];
    $file_size=$_FILES['uploadfile']['size'];
    $file_ext = end(explode('.',$file_name));
    // file name se hta ke sirf extention deta h
    $extensions = array("jpeg","jpg","png");
    //print_r( $extensions = array("jpeg","jpg","png"));   // array hai 
    //output  Notice: Only variables should be passed by reference inArray ( [0] => jpeg [1] => jpg [2] => png )
        if($file_size > 2097152){
        $errors[] = "File size must be 2mb or lower.";
        }
      
        if(in_array($file_ext,$extensions) === false)
        {
        $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
         }

        $new_name = time(). "-".basename($file_name);  // create randome new name
        $target = "upload/".$new_name;

        if(empty($errors) == true){
        move_uploaded_file($tmp_name,$target);
        }else{
        print_r($errors);
        die();
    }   }

      $date = date("d M, Y");
      $insql="INSERT INTO image (test_name,test_image,test_date) VALUES ('".$_POST['name']."','$target','$date')"; 
      $runsql=mysqli_query($conn,$insql) or die("query failed");
       if($runsql){
        echo '<script> alert("data inserted successfully")</script>';
           // header("Location: http://localhost/newproject/IMAGECRUD/index.php");
     ?>
<meta HTTP-EQUIV="Refresh" content="3; URL=index.php">
<?php 
      } }

    //   else{ echo '<script> alert("All field are required")</script>';} 

      ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Form</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

</head>

<body>
    <div class="form-container">
        <center>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="name" name="test_id" value="<?php  echo $_GET['uid'] ?>">
                <h2 style="text-decoration:underline;text-transform:uppercase;color:blue">Upload Your File</h2>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $editsingledata['test_name'] ?>"
                        placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="file">Choose File</label>
                    <input type="hidden" id="file" name="uploadfile_old" value="<?php echo $editsingledata['test_image'] ?>">
                    <?php if($editsingledata['test_image']!=''){
                    echo "<img class='w-25' src='".$editsingledata['test_image']." '> ";
                } ?>
                    <input type="file" id="file" name="uploadfile">
                </div>

                <div class="form-group">
                    <button type="submit" name="save">Submit</button>
                </div>
            </form>
        </center>
    </div>
</body>

</html>
<?php 
if(@$_GET['search']!=''){
$search=$_GET['search'];
$WHERE="";
$WHERE="WHERE test_name LIKE '%$search%' "; }?>
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <form class="d-flex" role="search" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="search" aria-label="Search"
                value="<?php echo @$_GET['search'] ?>">
            <button class="btn btn-outline-dark" type="submit" name="searchbtn">Search</button>
        </form>
    </div>
</nav>

<?php 
echo '<table width="100%" border="1">
<tr style="font-weight:bolder;text-align:center; background-color:black;color:#fff">
<th>#</th>
<th>DATE</th>
<th>Name</th>
<th>Image</th>
<th>Operation</th>
</tr>';
$page=$_GET['page'];
if(isset($_GET['page'])){$_GET['page'];}else{$_GET['page']=1;}

$limit=2;
// $offset=($page-1)*$limit;
$offset=($_GET['page']-1)*$limit;
//  echo $dissql="SELECT *FROM image {$WHERE} ORDER BY test_id DESC LIMIT {$offset},{$limit}";
   $dissql= "SELECT *FROM image {$WHERE} ORDER BY test_id DESC LIMIT {$offset},{$limit}";
 $rundis=mysqli_query($conn,$dissql) or die("query failed");
 if(mysqli_num_rows($rundis)>0){
    // echo "row found";
    $abc=1;
    foreach ($rundis as $key => $value) {
       // echo '<pre>'; print_r($value); echo '</pre>';

        echo '<tr align:center style="font-weight:bolder;text-align:center;">
                 <td>'.$abc++.'</td>
                  <td align:center>'.$value['test_date'].'</td>
              <td align:center>'.$value['test_name'].'</td>
              <td align:center> <img src='.$value["test_image"].' width="50px" height="50px"</td>
                <td align:center> 
                <a href=index.php?del='.$value['test_id']. ' class="btn btn-danger"> Delete  </a>
                <a href=index.php?uid='.$value['test_id']. ' class="btn btn-primary"> Edit </a></td>
              </tr>';}   }
         else { echo "No Row Found "; } echo '</table>'; ?>
<!-- pagination -->
<?php  
  echo '<nav aria-label="..."><ul class="pagination">';
  if($_GET['page']>1)
  echo ' <li class="page-item"><a class="page-link" href="index.php?page='.($_GET['page']-1).'">Pre</a></li>';
  $pagesql= "SELECT *FROM image";
  $pagerun=mysqli_query($conn,$pagesql);
  $total_record=mysqli_num_rows($pagerun);
  $total_page=ceil($total_record/$limit);
  for($i=1; $i<$total_page; $i++){
  if($i==$_GET['page']){$active="active";} else{$active="";}
  echo '<li class="'.$active.'"><a class="page-link" href="index.php?page='.$i.'">'.$i.'</a></li>';}
  if($total_page>$page)
  echo '<li class="page-item"><a class="page-link" href="index.php?page='.($_GET['page']+1).'">Next</a></li>
 </ul>
 </nav>';
  ?>