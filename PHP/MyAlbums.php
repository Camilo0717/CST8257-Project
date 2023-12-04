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
    $_SESSION['Location'] = 'MyAlbums.php';
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
foreach ($albumList['albumArray'] as $row){
    $albumId = htmlspecialchars($row['albumId']);
if (isset($_POST["del_{$albumId}"])){
  
    deleteAlbum($albumId);
}
}



include 'Common/PageElements/header.php';
?>


    <div class="container mt-5">
        <h2 class="row justify-content-center">My Albums</h2>
        <form  action="MyALbums.php" method="post" class="form-group">
        <?php 

        echo <<<HTML
             <div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-9">
                        <p>{$albumList['message']}</p>
                    </div>
                    <div class="col-lg-4 col-sm">
                        <a href="AddAlbum.php">Add Album</a>
                    </div>
             </div>
            HTML;
        if (count($albumList['albumArray']) > 0){
            echo '<div class="row justify-content-center">';
                    echo <<<TABLE
                    <div class="col-lg-8 col-sm">
                        <table id="albumTable" class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th class="col-lg-2 col-sm">Title</th>
                                    <th class="col-lg-2 col-sm">Number Of Pictures</th>
                                    <th class="text-center col-lg-4 col-sm">Accessibility</th>
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
                                    <td>

                                        <a href="MyPictures.php?albumSelection={$albumId}"> {$albumTitle}</a>
   
                                    </td>
                                    <td>{$pictureCount}</td>
                                    <td class="text-center">
                                    <div class="albumControls">
                                        <select class="form-control" id="albumSelection" name="{$albumId}">
                                            <option value="shared"  {$shared}>Available to owner and friends</option>
                                            <option value="private" {$private}>Available to owner only</option>
                                         </select>
                                         

                                         <input type="submit" value="Delete" class="btn btn-primary" name="del_{$albumId}" onclick='return confirmDelete()'> 
                                    </div>

                                    </td>
                                </tr>   
                            ROW;
            }
         echo <<<HTML
            </tbody></table></div>
            </div>
            <div class="row offset-lg-2">
            <input name="btnSubmit" type="submit" value="Save Changes" class="btn btn-primary col-auto" name="btnRegister"> 
            </div>
        HTML;
        }
        ?>
        </form>
        
    </div>

<script>
    function confirmDelete(){
        let result = confirm("Are you sure you want to delete the selected album?");
        return result;  
    }
        function submitForm( id) {
            let form = document.querySelector("form");
            console.log(form);
            console.log(id);
            form.submit();
        }
   
</script>
<?php
    include 'Common/PageElements/Footer.php';

