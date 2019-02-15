<?php
require_once("./connection_vars.php");
// echo $target_dir . "<br>";
$target_dir = getcwd()."/uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
    
    // Here's what you do in Ubuntu
    
    // Make sure all files are owned by the Apache group and user. In Ubuntu it is the www-data group and user
    
    // chown -R www-data:www-data /path/to/webserver/www
    
    // Next enabled all members of the www-data group to read and write files
    
    // chmod -R g+rw /path/to/webserver/www
};
if(!file_exists($target_dir."index.html")){
    fopen($target_dir."index.html","w");
};



// $files = array_filter($_FILES['upload']['name']);

// Count # of uploaded files in array
$total = count($_FILES['fileToUpload']['name']);
echo "<br>total = ".$total;

// Create connection
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    
    $create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
    if(mysqli_query( $conn, $create_db)){
        // echo "Database $dbname created successfully\n";
    } else {
        echo '<br>Error creating database: ' . mysqli_connect_error() . "\n";
    }
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $create_table = "CREATE TABLE IF NOT EXISTS Stock(id INT NOT NULL AUTO_INCREMENT, url VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL,
    primary key (id))";
    if (mysqli_query($conn, $create_table)){

        // echo "Table Stock created successfully\n";
    
    }else{
        echo '<br>Error creating table: ' .  mysqli_error($conn) . "\n";
}
// Loop through each file
for( $i=0 ; $i < $total ; $i++ ) {

  //Get the temp file path
  $tmpFilePath = $_FILES['fileToUpload']['tmp_name'][$i];

  //Make sure we have a file path
  if ($tmpFilePath != ""){
    //Setup our new file path
    $newFilePath = $_FILES['fileToUpload']['name'][$i];

    //Upload the file into the temp dir
    uploadAndAdd($newFilePath, $tmpFilePath);

  }else{
      echo "tmpFilePath is null.";
  }
}

// mysqli_close($conn);

function uploadAndAdd($filename, $filetmpname){
    $target_dir = getcwd()."/uploads/";
    // $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . basename($filename);
    echo "<br>".$target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        // $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $check = getimagesize($filetmpname);
        if($check !== false) {
            echo "<br/>"."File is an image - " . $check["mime"] . ".";
            echo "<br/>"."imageFileType - " . $imageFileType . ".";
            $uploadOk = 1;
        } else {
            echo "<br>File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<br>Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    // if ($_FILES["fileToUpload"]["size"] > 500000) {
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    // }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<br>Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<br>Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        $status = move_uploaded_file($filetmpname, $target_file);
        // echo "status=".$status . " <br>";
        if ($status) {
            echo "<br>The file ". basename($filename). " has been uploaded.";
            $image_url = "/uploads/".basename($filename);
            $current_time = time();
            $category = $_POST["category"];
    
            echo "image_url =", $image_url;
            echo "current_time = ".$current_time;
            echo "category = ".$category;
    
            if($category && $image_url && $current_time){
                $insert_query  ="INSERT INTO Stock (id, url, category, created_at) VALUES (NULL, '$image_url', '$category', '$current_time')";
                global $conn;
                if(mysqli_query($conn, $insert_query)){
                    echo "<br>Added to table";
                    
                }else{
                    echo '<br>Error adding to table: ' .  mysqli_error($conn) . "\n";
                };
    
            }else{
                echo "<br>All values are mandatory.";
            }
    
        } else {
            echo "<br>Sorry, there was an error uploading your file.";
        }
    }
    
}

?>