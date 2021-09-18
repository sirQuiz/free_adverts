<?php
//connect ot db
$conn = mysqli_connect('localhost', 'root', '', 'dimplomtwo');

//check connection
if (!$conn) {
    echo "connection error" . mysqli_connect_errno();
}

?>