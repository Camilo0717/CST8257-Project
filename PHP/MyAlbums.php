<?php
session_start();
extract($_POST);

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename) {
    include $filename;
}

// Set active Link
extract(setActiveLink('Albums'));

// Check user status
$isLogged = (isset($_SESSION['userId']));
[$Message, $Link] = checkLogStatus($isLogged);

$currentUserId = $_SESSION['userId'] ?? null;


if (!$isLogged) {
    header("Location: LogIn.php");
    exit;
}
 $albumList = getAlbumsList($currentUserId); 
if (isset($btnSubmit)) {
    foreach ($albumList['albumArray'] as $row){
        $albumId = htmlspecialchars($row['albumId']);
        updateAlbum($albumId, $_POST[$albumId]);
    }
}




include 'Common/PageElements/header.php';
?>

<body>
    <div class="container mt-5">
        <h2>My Albums</h2>
        <form  action="MyALbums.php" method="post"  >
        <?php 
       
        
       
        echo <<<HTML
             <div class=row>
                    <div class=col>
                        <p>{$albumList['message']}</p>
                    </div>
                    <div class=col>
                        <a href="AddAlbum.php">Add Album</a>
                    </div>
             </div>
        HTML;
        if (count($albumList['albumArray']) > 0){
                    echo <<<TABLE
                        <table id="albumTable" class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Number Of Pictures</th>
                                    <th>Accessibility</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                    TABLE;
                        
                        
             foreach ($albumList['albumArray'] as $row){
                            $albumId = htmlspecialchars($row['albumId']);
                            $albumTitle = htmlspecialchars($row['albumTitle']);
                            $pictureCount = htmlspecialchars($row['pictureCount']);
                            $accessibilityCode = htmlspecialchars($row['accessibilityCode']);
                            $shared = '';
                            $private = '';
                            if (isset($btnSubmit)){
                                $accessibilityCode = $_POST[$albumId];
                            }
                            
                            if ($accessibilityCode == "shared"){
                                $shared = "selected";
                            }else{
                                $private = "selected";
                            }
                            
                            
                            echo <<<ROW
                                <tr>
                                    <td>{$albumTitle}</td>
                                    <td>{$pictureCount}</td>
                                    <td>
                                        <select class="form-control" id="albumSelection" name="{$albumId}">
                                            <option value="shared"  {$shared}>Available to owner and friends</option>
                                            <option value="private" {$private}>Available to owner only</option>
                                         </select>
                                    </td>
                                </tr>   
                            ROW;
            }
         echo <<<HTML
            </table>
             <input name="btnSubmit" type="submit" value="Save Changes" class="btn btn-primary" name="btnRegister" onclick=''> 
        HTML;
        }
        ?>
        </form>
        
    </div>
</body>

<?php
    include 'Common/PageElements/Footer.php';

