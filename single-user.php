<?php
include 'config/db_connect.php';

session_start();
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($id) {
        $advertsID = $user['id'];
        $advertsSQL = "SELECT * FROM adverts WHERE author_id = '$advertsID'";
        $advertsResult = mysqli_query($conn, $advertsSQL);
        $adverts = mysqli_fetch_all($advertsResult, MYSQLI_ASSOC);
    }

    if ($id) {
        $userAvatarID = $user['id'];
        $userAvatarSQL = "SELECT * FROM users_images WHERE image_owner = '$userAvatarID'";
        $userAvatarResult = mysqli_query($conn, $userAvatarSQL);
        $userAvatar = mysqli_fetch_assoc($userAvatarResult);
    }
}
?>

<?php include "header.php"; ?>
    <main>
        <section>
            <div class="user-cabinet">
                <div class="user">
                    <?php if ($userAvatar) : ?>
                        <div class="user__avatar-wrapper">
                            <img class="user__avatar"
                                 src="uploads/user_images/<?php echo $userAvatar['user_image']; ?>"
                                 alt="avatar">
                        </div>
                    <?php endif; ?>
                    <div class="user__information">
                        <?php if ($user) : ?>
                            <h3 class="user__nickname">
                                <?php echo $user['nickname']; ?>
                            </h3>

                            <span class="user__full-name">
                                <?php echo $user['full_name']; ?>
                            </span>

                            <span class="user__email">
                                <?php echo $user['email']; ?>
                            </span>

                            <span class="user__description">
                                <?php echo $user['about_user']; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


            <?php if ($adverts) : ?>
                    <div class="user-adverts">
                        <?php foreach ($adverts as $userAdvert) : ?>
                            <div class="advert">
                                <h4 class="advert__title"><a href="single-advert.php?id=<?php echo $userAdvert['id']; ?>"><?php echo htmlspecialchars($userAdvert['title']); ?></a></h4>
                                <br>
                                <div class="advert__description">
                                    <p><?php echo htmlspecialchars($userAdvert['description']); ?></p>
                                </div>
                                <br>
                                <div class="advert__contacts">
                                    <p><?php echo htmlspecialchars($userAdvert['contacts']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
        </section>
    </main>
<?php include "footer.php"; ?>