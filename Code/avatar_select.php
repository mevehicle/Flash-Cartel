<?php
session_start();

// images for avatars
?>

<!DOCTYPE html>
<html>
<head>
<title>Select Avatar</title>

<style>

.avatarBox{
    margin-top:30px;
}

.avatar{
    width:90px;
    border-radius:50%;
    cursor:pointer;
}

.avatar:hover{
    border:3px solid green;
}

</style>

</head>

<body>

<h2>Choose an avatar</h2>

<div class="avatarBox">


<a href="save_avatar.php?avatar=avatar1.png">
<img src="images/avatars/avatar1.png" class="avatar">
</a>

<a href="save_avatar.php?avatar=avatar2.png">
<img src="images/avatars/avatar2.png" class="avatar">
</a>

</div>

</body>
</html>