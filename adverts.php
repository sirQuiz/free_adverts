<?php
include 'config/db_connect.php';


session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];

$adverts       = 'SELECT * FROM adverts';
$advertsResult = mysqli_query($conn, $adverts);
$advertsArray  = mysqli_fetch_all($advertsResult, MYSQLI_ASSOC);
mysqli_free_result($advertsResult);
?>
<?php include "header.php"; ?>
    <main>
        <section class="adverts">
            <div class="adverts-inner">
                <?php
                foreach ($advertsArray as $advert) :
                    $advertID          = htmlspecialchars($advert['id']);
                    $advertTitle       = htmlspecialchars($advert['title']);
                    $advertDescription = htmlspecialchars($advert['description']);
                    $advertContacts    = htmlspecialchars($advert['contacts']);
                    $advertDate        = htmlspecialchars($advert['created_at']);
                    $advertAuthor      = htmlspecialchars($advert['author_id']);

                    if ($advertID){
                        $advertAvatarSQL    = "SELECT * FROM adverts_images WHERE image_owner = '$advertID'";
                        $advertAvatarResult = mysqli_query($conn, $advertAvatarSQL);
                        $advertAvatarArray  = mysqli_fetch_array($advertAvatarResult);
                    }

                    if ($advertAuthor){
                        $userAdvertsSQL    = "SELECT * FROM users WHERE id = '$advertAuthor'";
                        $userAdvertsResult = mysqli_query($conn, $userAdvertsSQL);
                        $userAdvertsArray  = mysqli_fetch_assoc($userAdvertsResult);
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
                            <div class="advert__author">
                                <?php if ($advertAuthor === $userID) : ?>
                                    <h5 class="author__name"><a href="mycabinet.php"><?php echo $userAdvertsArray['nickname']; ?></a></h5>
                                <?php else: ?>
                                    <h5 class="author__name"><a href="single-user.php?id=<?php echo $advertAuthor; ?>"><?php echo $userAdvertsArray['nickname']; ?></a></h5>
                                <?php endif; ?>

                            </div>
                            <div class="advert__title">
                                <a href="single-advert.php?id=<?php echo $advertID; ?>"><?php echo $advertTitle; ?></a>
                            </div>

                            <div class="advert__description">
                                <?php echo $advertDescription; ?>
                            </div>

                            <div class="advert__contacts">
                                <?php echo $advertContacts; ?>
                            </div>

                            <div class="advert__date">
                                <?php echo $advertDate; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
<?php include "footer.php"; ?>