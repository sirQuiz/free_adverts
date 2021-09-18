<?php
include 'config/db_connect.php';
session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];

// write query for all users
$usersSLQ = 'SELECT * FROM users';
// make query
$usersRESULT = mysqli_query($conn, $usersSLQ);
// fetch the resulting rows as an array
$usersArray = mysqli_fetch_all($usersRESULT, MYSQLI_ASSOC);
// free result from memory
mysqli_free_result($usersRESULT);
// close connection
//mysqli_close($conn);

if (1){
    $adverts = 'SELECT * FROM adverts';
    $advertsResult = mysqli_query($conn, $adverts);
    $advertsArray = mysqli_fetch_all($advertsResult, MYSQLI_ASSOC);
    mysqli_free_result($advertsResult);
}

?>
<?php include "header.php"; ?>
    <main class="main-content">
        <aside class="users-list">
            <?php
            foreach ($usersArray as $user) :
                $authorID = $user['id'];

                if ($authorID){
                    $authorAvatarSQL    = "SELECT * FROM users_images WHERE image_owner = '$authorID'";
                    $authorAvatarResult = mysqli_query($conn, $authorAvatarSQL);
                    $authorAvatarArray  = mysqli_fetch_array($authorAvatarResult);
                }
                ?>
                <?php if ($userID != $authorID) :?>
                    <div class="user">
                        <?php if ($authorAvatarArray) : ?>
                            <div class="user__avatar">
                                <img class="advert__thumbnail"
                                     src="uploads/user_images/<?php echo $authorAvatarArray['user_image']; ?>"
                                     alt="avatar">
                            </div>
                        <?php endif; ?>

                        <div class="user__nickname">
                            <a href="single-user.php?id=<?php echo $authorID;?>"><?php echo htmlspecialchars($user['nickname']); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </aside>

        <div class="common-content">

            <section class="adverts__tile">
                <div class="adverts-inner">
                    <?php
                    foreach ($advertsArray as $advert) :
                        $advertID = $advert['id'];
                        if ($advertID){
                            $advertAvatarSQL    = "SELECT * FROM adverts_images WHERE image_owner = '$advertID'";
                            $advertAvatarResult = mysqli_query($conn, $advertAvatarSQL);
                            $advertAvatarArray  = mysqli_fetch_array($advertAvatarResult);
                        }
                        ?>
                    <div class="advert">
                        <div class="advert__thumbnail-wrapper">
                            <?php if ($advertAvatarArray) : ?>
                                <img class="advert__thumbnail"
                                     src="uploads/advert_images/<?php echo $advertAvatarArray['advert_image']; ?>"
                                     alt="thumbnail">
                            <?php endif; ?>
                        </div>

                        <div class="advert__text-content">
                            <div class="advert__title">
                                <a href="single-advert.php?id=<?php echo $advert['id']; ?>"><?php echo htmlspecialchars($advert['title']); ?></a>
                            </div>

                            <div class="advert__description">
                                <?php echo htmlspecialchars($advert['description']); ?>
                            </div>

                            <div class="advert__contacts">
                                <?php echo htmlspecialchars($advert['contacts']); ?>
                            </div>

                            <div class="advert__date">
                                <?php echo htmlspecialchars($advert['created_at']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>
<?php include "footer.php"; ?>