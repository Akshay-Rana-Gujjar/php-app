<!DOCTYPE html>
<html>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload[]" id="fileToUpload" accept="image/*" multiple required/>
    <br>
    <label >Category</label>
    <input type="text" name="category" required />
    <br>
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>