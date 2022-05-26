

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

//***********For insert data ************ */
$is_submitted = false;
if($_SERVER['REQUEST_METHOD']=='POST'){
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
}
//****************Insert data end************ */
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
    if($is_submitted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Hurrah!</strong> Data has been inserted successfully.
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
        while($row = mysqli_fetch_assoc($result)){
             echo "<tr>
            <th scope='row'>".$row['slno']."</th>
            <td>".$row['title']."</td>
            <td>".$row['description']."</td>
            <td>@Actions</td>
            </tr>";
            }
        //************************************************/
    ?>
  </tbody>
</table>
        <!--Table for data showing end-->
    </div>

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
</body>

</html>