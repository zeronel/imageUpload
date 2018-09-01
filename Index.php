<?php

    $db = mysqli_connect('localhost', 'root', '', 'upload_image');

    $msg = "";

    if(isset($_POST['upload'])){

        $err = 1;

        $file_img   = $_FILES['image']['name'];
        $file_size  = $_FILES['image']['size'];
        $file_temp  = $_FILES['image']['tmp_name'];

        $thisFile = $_FILES['image'];

        $file_name  = strtolower(pathinfo($file_img, PATHINFO_FILENAME));
        $file_ext   = strtolower(pathinfo($file_img, PATHINFO_EXTENSION));
        

        $types = array('gif','jpeg','jpg','png');

        if(in_array($file_ext, $types) === false){
            $msg = '<div class="err">Unsupported Image File!</div>';
            $err = 0;
        }

        if($file_size > 2097152){
            //MAX SIZE: 2MB
            $msg = '<div class="err">File is Too Large!</div>';
            $err = 0;
        }

        if($err == 0){
            $msg = '<div class="err">Upload Failed!</div>';
        }

        else{
            $val = md5(microtime(true).$file_name).'.'.$file_ext;
            $sql = "INSERT INTO img(img_name)VALUES('$val')";
            $dir = 'images/'.$val;

            if(move_uploaded_file($_FILES['image']['tmp_name'], $dir )){
                mysqli_query($db, $sql);
            }else{
                $msg = '<div class="err">Upload Failed!</div>';
            }
        }

        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Image Upload</title>
</head>
<body>
    <div class="sideA">
    <form action="index.php" method="post" enctype="multipart/form-data">
    <div style="height:460px;">
        <h2>Image Upload <!--<span>v0.4</span>--></h2>
        <div class="imgPreview" id="img"></div>
        <div class="i" style="display:flex">
            <div class="input-file">
                <input type="file" name="image" id="image">
                <label for="image">Browse...</label>
            </div>
            <button type="submit"name="upload">Upload</button>
        </div>
        </div>
    </form>
    <?php echo $msg; ?>
    </div>

    <div class="imgList">
    <?php 
        $x = mysqli_query($db,'SELECT * FROM img ORDER BY img_id DESC' );
        if($y = mysqli_num_rows($x) > 0){
            while($data = mysqli_fetch_array($x)){
                $file = filesize('images/'.$data['img_name']);
                $size = $file/1024;
                $sizeOf = '';
                if($size < 1024){
                    $sizeOf = number_format($size, 2).' KB';
                }else{
                    $sizeOf = number_format($size/1024, 2).' MB';
                }
    ?>
        <div class="pic">
            <div class="image" style="background-image:url('images/<?php echo $data['img_name'];?>')"></div>
            <div>
                <a class="picLnk" target="_blank" href="images/<?php echo $data['img_name'];?>" title="<?php echo $sizeOf;?>"></a>
                <a class="picDel" href="delete.php?id=<?php echo $data['img_id'];?>">&#x2716</a>
            </div>
        </div>
    <?php
            }
        }
    ?>
    </div>
    <?php
    
        $a = mysqli_query($db,'SELECT * FROM img');
        $b = mysqli_num_rows($a);

        $fsize = array();
        $tsize = 0;

        if($b > 0){
            while($imgs = mysqli_fetch_array($a)){
                $fsize[] = filesize('images/'.$imgs['img_name']);
            }
        }

        for($i = 0; $i < count($fsize); $i++){
            $tsize += $fsize[$i];
        }

        $used       = $tsize/1024;
        $thisSize   = '';
        
        if($used < 1024){
            $thisSize = number_format($used, 2).' KB';
        }else{
            $thisSize = number_format($used/1024, 2).' MB';
        }
    
    ?>
    <footer class="footer">Storage Used = <?php echo $thisSize;?></footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/script.js"></script>

</body>    
</html>