<?php
include 'config/db_connect.php';
session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];

$usersSLQ    = 'SELECT * FROM users';
$usersRESULT = mysqli_query($conn, $usersSLQ);
$usersArray  = mysqli_fetch_all($usersRESULT, MYSQLI_ASSOC);
mysqli_free_result($usersRESULT);

if(isset($_POST['search_advert']) && $_POST['search'] !== ''){
    $messageSearch       = '';
    $search              = mysqli_real_escape_string($conn, $_POST['search']);
    $searchSQL           = "SELECT * FROM adverts WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
    $searchAdvertsResult = mysqli_query($conn, $searchSQL);
    $count               = mysqli_num_rows($searchAdvertsResult);

    if ($conn == 0 ){
        $messageSearch = "There is no adverts like this";
    } else {
        $searchAdvertsArray  = mysqli_fetch_all($searchAdvertsResult, MYSQLI_ASSOC);
    }
} else {
    header("location: index.php");
}

?>
<?php include "header.php"; ?>
    <main class="main-content">
        <div class="common-content">
            <div class="search-results">
                <?php if ($count == 0 ) :?>
                    <span class="search-message"><?php echo $messageSearch; ?></span>
                <?php elseif ($count == 1) : ?>
                    <span class="results-amount">There is <?php echo $count; ?> advert. </span>
                <?php else : ?>
                    <span class="results-amount">There is <?php echo $count; ?> adverts. </span>
                <?php endif; ?>
            </div>

            <section class="adverts__tile">
                <div class="adverts-inner">
                    <?php
                    foreach ($searchAdvertsArray as $advert) :
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