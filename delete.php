<?php

    $db = mysqli_connect('localhost', 'root', '', 'upload_image');

    if( ($_GET['id'] != null) && is_numeric($_GET['id'])){

        $id4Del = $_GET['id'];

        $sql = mysqli_query($db, "SELECT * FROM img WHERE img_id='$id4Del'");
        $qry = mysqli_fetch_array($sql);

        if(unlink('images/'.$qry['img_name'])){

            mysqli_query($db, "DELETE FROM img WHERE img_id='$id4Del'");

        }
    }

    header('location:./');

?>