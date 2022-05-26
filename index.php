

<!-- INSERT INTO `notes` (`slno`, `title`, `description`, `timestamp`) VALUES ('1', 'description', current_timestamp()); -->
<?php
//<!--Database connection with php-->
$servername = "localhost:3307";
$username = "root";
$password = "";
$database_name = "note_tracker";
$con = mysqli_connect($servername,$username,$password,$database_name);
if(!$con){
    die("Sorry we failed to connect: ".mysqli_connect_error()); 
}

//***********For insert and update and delete data ************ */
$is_submitted = false;
$is_updated = false;
$is_deleted = false;
if($_SERVER['REQUEST_METHOD']=='POST'){
    //***********For update data ************ */
    if(isset($_POST['slNoEdit'])){
    $title = $_POST['titleEdit'];
    $description = $_POST['descriptionEdit'];
    $serialNo = $_POST['slNoEdit'];
    $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`slno` = $serialNo;";
    
    $result = mysqli_query($con,$sql);
    
     if($result){
        $is_updated = true;
     }
     else{
        echo "Updation failed: ".mysqli_error($con);
         }
    }
    else if(isset($_POST['title'])){
    //************Update data section end*** */
    //***********For insert data ************ */
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sql = "INSERT INTO `notes` (`title`, `description`, `timestamp`) VALUES ('$title', '$description', current_timestamp());";
    $result = mysqli_query($con,$sql);
    if($result){
        $is_submitted = true;
    }
    else{
        echo "Insertion failed: ".mysqli_error($con);
    }
    //***********Insert data end ************ */
}
//*********************For delete data -start********* */
if(isset($_POST['deleteId'])){
    $sNo = $_POST['deleteId'];
    $sql = "DELETE FROM notes WHERE `notes`.`slno` = $sNo";
    $result = mysqli_query($con,$sql);
    if($result){
        $is_deleted = true;
    }
    else{
        echo "Deletion failed: ".mysqli_error($con);
    }
}
//*********************For delete data -end*********** */
}
//****************Insert and update and delete data end************ */

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
     <link rel="stylesheet" href="//cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">

    <title>Note Tracker</title>
</head>

<body>
    <!-- Edit trigger modal -->
<!--Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/note_tracker/index.php" method="post">
            <input type="hidden" id="slNoEdit" name="slNoEdit" class="hidden">
            <div class="form-group">
                <label for="title">Note title</label>
                <input type="text" class="form-control" id="titleEdit" name="titleEdit">
            </div>
        
            <div class="form-group">
                <label for="description">Note description</label>
                <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--Delete Modal -start-->
<!-- Button trigger modal -->
<!-- Modal --> 
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Delete Action</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form  action="/note_tracker/index.php" method="post">
          <input type="hidden" id="deleteId" name="deleteId" class="hidden"> 
          <div>Are you really want to delete this note?</div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Yes</button>
      </div>
           </form>
      </div>
    </div>
  </div>
</div>
 
<!--Delete modal -end-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Note Tracker</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <?php 
    if($is_submitted || $is_updated){
        $action_name = $is_submitted?"inserted":"updated";
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
     <strong>Hurrah!</strong> Data has been '.$action_name.' successfully.
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
        </div>';
    }
    if($is_deleted){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
     <strong>Hurrah!</strong> Data has been deleted successfully.
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
        </div>';
    }
    ?>

    <div class="container my-4">
        <h2>Add a note</h2>
        <form action="/note_tracker/index.php" method = "post">
            <div class="form-group">
                <label for="title">Note title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
        
            <div class="form-group">
                <label for="description">Note description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <!--For data show-->
    <div class="container">

        <!--Table for data showing-->
<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">Sl.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
        //************fetching data from database***********
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($con,$sql);
        $sNo = 0;
        while($row = mysqli_fetch_assoc($result)){
              $sNo++;
             echo "<tr>
            <th scope='row'>".$sNo."</th>
            <td>".$row['title']."</td>
            <td>".$row['description']."</td>
            <td><button class='edit btn btn-sm btn-primary' id=".$row['slno'].">Edit</button> 
            <button class='delete btn btn-sm btn-danger' id=d".$row['slno'].">Delete</button></td>
            </tr>";
            }
            
        //************************************************/
    ?>
  </tbody>
</table>
        <!--Table for data showing end-->
    </div>
    <div class="container my-4"></div>
    <hr>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready( function () {
        $('#myTable').DataTable();
    } );  
    </script>
    <!--Scripting for edit button action-->
    <script>
        //************Edit related js -start********** */
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
            console.log("edit",e.target.parentNode.parentNode);
            tr = e.target.parentNode.parentNode;
            title = tr.getElementsByTagName("td")[0].innerText;
            description = tr.getElementsByTagName("td")[1].innerText;
            console.log(title,description);
            titleEdit.value = title;
            descriptionEdit.value = description;
            slNoEdit.value = e.target.id;
            $('#editModal').modal('toggle');
            console.log(e.target.id);
        })
      })
      //**********Edit related js -end**************** */
      //***********Delete related js -start************ */
    deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener("click",(e)=>{
            sNo = e.target.id.substr(1,);
            deleteId.value = sNo;
            $('#deleteModal').modal('toggle');
            // if(confirm("Are you sure you want to delete?")){
            //     console.log("yes");
            //     $('#deleteModal').modal('toggle');
            // }
            // else{
            //     console.log("No");
            // }
        })
      })
      //***********Delete related js -end*************** */
    </script>
</body>

</html>