<?php
include 'config/db_connect.php';

session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];

$message = "";
if (isset($_POST['user_image_upload'])){
    $target = "uploads/user_images/".basename($_FILES['image']['name']);
    $image  = $_FILES['image']['name'];
    if ($image) {
        $imageDeleteSQL = "DELETE FROM users_images WHERE image_owner = '$userID' ";
        $imageSQL       = "INSERT INTO users_images(user_image, image_owner) VALUES ('$image', '$userID')";

        mysqli_query($conn, $imageDeleteSQL);
        mysqli_query($conn, $imageSQL);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $message = "Image uploaded successfully.";
        } else {
            $message = "There was an error in uploading image.";
        }
    }
}

$userAvatarSQL = "SELECT * FROM users_images WHERE image_owner = '$userID' ORDER BY id";
$userAvatarResult = mysqli_query($conn, $userAvatarSQL);
$userAvatarArray = mysqli_fetch_array($userAvatarResult);
mysqli_free_result($userAvatarResult);
//mysqli_close($conn);
if ($userID){
    $userAdvertsSQL    = "SELECT * FROM adverts WHERE author_id = '$userID'";
    $userAdvertsResult = mysqli_query($conn, $userAdvertsSQL);
    $userAdvertsArray  = mysqli_fetch_all($userAdvertsResult, MYSQLI_ASSOC);
}
?>
<?php include "header.php"; ?>
<main>
    <section class="user-cabinet">
        <div>
            <div class="user">
                <div>
                    <?php if ($userAvatarArray['user_image']) : ?>
                        <div class="user__avatar-wrapper">
                            <img class="user__avatar" src="uploads/user_images/<?php echo $userAvatarArray['user_image']; ?>" alt="">
                        </div>
                    <?php endif; ?>

                    <?php if ($message) : ?>
                        <span><?php echo $message; ?></span>
                    <?php endif; ?>

                    <div class="form-wrapper">
                        <form action="mycabinet.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="size" value="100000000">

                            <div>
                                <input type="file" name="image">
                            </div>

                            <input type="submit" name="user_image_upload" value="Upload Image">
                        </form>
                    </div>
                </div>

                <div class="user__information">
                    <?php if ($userNickName) : ?>
                        <h3 class="user__nickname">
                            <?php echo $userNickName; ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ($userFullName) : ?>
                        <span class="user__full-name">
                            <?php echo $userFullName; ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($userEmail) : ?>
                        <span class="user__email">
                            <?php echo $userEmail; ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($userAbout) : ?>
                        <span class="user__description">
                            <?php echo $userAbout; ?>
                        </span>
                    <?php endif; ?>
                </div>

                <a class="link__edit" href="useredit.php">Edit Account</a>

                <a class="link__delete" href="userdelete.php">Delete Account</a>
            </div>

            <?php if ($userAdvertsArray) :?>
                <div class="user-adverts">
                    <?php foreach ($userAdvertsArray as $userAdvert) : ?>
                        <div class="advert">
                            <div class="advert__text-content">
                                <h4 class="advert__title"><a href="single-advert.php?id=<?php echo $userAdvert['id']; ?>"><?php echo htmlspecialchars($userAdvert['title']); ?></a></h4>

                                <div class="advert__description">
                                    <p><?php echo htmlspecialchars($userAdvert['description']);?></p>
                                </div>

                                <div class="advert__contacts">
                                    <span><?php echo htmlspecialchars($userAdvert['contacts']);?></span>
                                </div>

                                <div class="advert__date">
                                    <span><?php echo htmlspecialchars($userAdvert['created_at']);?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php include "footer.php"; ?>
