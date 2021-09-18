<?php
include 'config/db_connect.php';

session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];
$advertsID    = $advertsCommentsID = 0;

if ($userID){
    $advertsSQL    = "SELECT * FROM adverts WHERE author_id = '$userID'";
    $advertsResult = mysqli_query($conn, $advertsSQL);
    $advertsArray  = mysqli_fetch_all($advertsResult, MYSQLI_ASSOC);
}

if ($userID){
    $advertsCommentsSQL    = "SELECT * FROM advert_comments WHERE user_comment_id = '$userID'";
    $advertsCommentsResult = mysqli_query($conn, $advertsCommentsSQL);
    $advertsCommentsArray  = mysqli_fetch_all($advertsCommentsResult, MYSQLI_ASSOC);
}

if (isset($_POST['delete'])) {
    $userToDelete         = mysqli_real_escape_string($conn, $_POST['user_to_delete']);
    $userDeleteSQL        = "DELETE FROM users WHERE id = '$userToDelete'";
    $imagesUserDeleteSQL  = "DELETE FROM users_images WHERE image_owner = '$userToDelete'";
    $advertsUserDeleteSQL = "DELETE FROM adverts WHERE author_id = '$userToDelete'";

    foreach ($advertsArray as $advertImage){
        $advertsID       = $advertImage['id'];
        $advertsImageSQL = "DELETE FROM adverts_images WHERE image_owner = '$advertsID'";
        $advertsImages        = mysqli_query($conn, $advertsImageSQL);
    }

    foreach ($advertsCommentsArray as $advertComments){
        $advertsCommentsID  = $advertComments['advert_comment_id'];
        $advertsCommentsSQL = "DELETE FROM advert_comments WHERE advert_comment_id = '$advertsCommentsID' AND user_comment_id = '$userID'";
        $advertsComments    = mysqli_query($conn, $advertsCommentsSQL);
    }

    $usersImages  = mysqli_query($conn, $imagesUserDeleteSQL);
    $usersAdverts = mysqli_query($conn, $advertsUserDeleteSQL);
    $userDelete   = mysqli_query($conn, $userDeleteSQL);


    if ($userDelete) {
        session_destroy();
        header('Location: index.php');
    } else {
        echo 'query error - ' . mysqli_error($conn);
    }
}

?>
<?php include "header.php"; ?>
<main>
    <section class="user-cabinet">
        <div>
            <div class="user">
                <!--     DELETE FORM        -->
                <form action="userdelete.php" method="POST">
                    <input type="hidden" name="user_to_delete" value="<?php echo $userID; ?>">
                    <input type="submit" name="delete" value="Delete Account">
                </form>
            </div>
        </div>
    </section>
</main>
<?php include "footer.php"; ?>
