<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
	<title>Algonquin Social Media Site</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./Common/CSS/Style.css">
</head>
<body style="padding-top: 150px; margin-bottom: 60px;">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top mb bg-dark" data-bs-theme='dark'>
        <div class="container-fluid">
            <a class="navbar-brand" href="http://www.algonquincollege.com">
                <img src="Common/PageElements/Logo.svg" alt="Logo" style="width: 50px;" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-underline">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeHome; ?>" href="Index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeFriends; ?>" href="MyFriends.php">My Friends</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeAlbums; ?>" href="MyAlbums.php">My Albums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePictures; ?>" href="MyPictures.php">My Pictures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeUpload; ?>" href="UploadPictures.php">Upload Pictures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeLog; ?>" href="<?php echo "$Link"; ?>"><?php echo $Message; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
