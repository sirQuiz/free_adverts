<?php
include 'config/db_connect.php';
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/general.css">
    <link rel="stylesheet" href="/styles/header.css">
    <link rel="stylesheet" href="/styles/footer.css">
    <link rel="stylesheet" href="/styles/buttons-links.css">
    <link rel="stylesheet" href="/styles/index.css">
    <link rel="stylesheet" href="/styles/form.css">
    <link rel="stylesheet" href="/styles/cabinet.css">
    <link rel="stylesheet" href="/styles/advert.css">
</head>
<body>

<div class="content">
    <header class="header">
        <div class="header-inner">
            <div class="header__logo">
                <a href="/index.php">HOME</a>
            </div>
            
            <div class="form-wrapper search-form">
                <form action="search.php" method="POST">
                    <input type="text" name="search" placeholder="Search an advert">
                    <input type="submit" value="Search" name="search_advert">
                </form>
            </div>

            <nav class="header__navigation">
                <ul class="header__menu">
                    <li><a href="/adverts.php">Adverts</a></li>
                    <?php if (!isset($_SESSION['nickname'])) : ?>
                        <li><a href="/registration.php">Registration</a></li>
                        <li><a href="/login.php">Log In</a></li>
                    <?php elseif (isset($_SESSION['nickname'])) : ?>
                        <li><a href="/addadvert.php">Add Advert</a></li>
                        <li><a href="/mycabinet.php"><?php echo $_SESSION['nickname']; ?></a></li>
                        <li><a href="/logout.php">Log Out</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>