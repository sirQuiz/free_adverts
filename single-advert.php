<?php
include 'config/db_connect.php';

session_start();
$userNickName = $_SESSION['nickname'];
$userID       = $_SESSION['id'];
$userEmail    = $_SESSION['email'];
$userAbout    = $_SESSION['about_user'];
$userFullName = $_SESSION['full_name'];

if (isset($_GET['id'])) {
    $id     = mysqli_real_escape_string($conn, $_GET['id']);
    $sql    = "SELECT * FROM adverts WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $advert = mysqli_fetch_assoc($result);

    if ($id) {
        $authorID     = $advert['author_id'];
        $authorSQL    = "SELECT * FROM users WHERE id = '$authorID'";
        $authorResult = mysqli_query($conn, $authorSQL);
        $author       = mysqli_fetch_assoc($authorResult);
    }
}
$message  = "";
$advertID = $advert['id'];
if (isset($_POST['advert_image_upload'])) {
    $target = "uploads/advert_images/" . basename($_FILES['image']['name']);
    $image  = $_FILES['image']['name'];
    if ($image) {
        $imageDeleteSQL = "DELETE FROM adverts_images WHERE image_owner = '$advertID' ";
        $imageSQL       = "INSERT INTO adverts_images(advert_image, image_owner) VALUES ('$image', '$advertID')";

        mysqli_query($conn, $imageDeleteSQL);
        mysqli_query($conn, $imageSQL);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $message = "Image uploaded successfully.";

        } else {
            $message = "There was an error in uploading image.";
        }
    }
}

$advertThumbnailSQL    = "SELECT * FROM adverts_images WHERE image_owner = '$advertID' ORDER BY id";
$advertThumbnailResult = mysqli_query($conn, $advertThumbnailSQL);
$advertThumbnailArray  = mysqli_fetch_array($advertThumbnailResult);


if (isset($_POST['edit_advert'])){
    $title         = mysqli_real_escape_string($conn, $_POST['title']);
    $contacts      = mysqli_real_escape_string($conn, $_POST['contacts']);
    $description   = mysqli_real_escape_string($conn, $_POST['description']);
    $advertEditSQL = "UPDATE adverts SET title='$title', contacts='$contacts', description='$description' WHERE id ='$id'";
    $query         = mysqli_query($conn, $advertEditSQL);

    if ($query){
        header('Location: mycabinet.php');
    } else{
        echo 'query error - ' . mysqli_error($conn);
    }
}

if (isset($_POST['advert_to_delete'])){
    $advertToDelete      = mysqli_real_escape_string($conn, $_POST['advert_to_delete']);
    $advertDeleteSQL     = "DELETE FROM adverts WHERE id ='$id'";
    $advertImagesSQL     = "DELETE FROM adverts_images WHERE image_owner ='$id'";
    $advertCommentsSQL   = "DELETE FROM advert_comments WHERE advert_comment_id ='$id' AND user_comment_id = '$userID'";
    $advertImagesQuery   = mysqli_query($conn, $advertImagesSQL);
    $advertCommentsQuery = mysqli_query($conn, $advertCommentsSQL);
    $advertDeleteQuery   = mysqli_query($conn, $advertDeleteSQL);

    if ($advertDeleteQuery){
        header('Location: mycabinet.php');
    } else {
        echo 'query error - ' . mysqli_error($conn);
    }
}

if (isset($_POST['left_comment'])){
    $commentText  = mysqli_real_escape_string($conn, $_POST['advert_comment_text']);
    $commentSQL   = "INSERT INTO advert_comments(comment_text, user_comment_id, advert_comment_id) VALUES ('$commentText', '$userID', '$id')";
    $commentQuery = mysqli_query($conn, $commentSQL);

    if ($commentQuery){
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "query error - " . mysqli_error($conn);
    }
}

if ($id) {
    $advertCommentsSQL    = "SELECT * FROM advert_comments WHERE advert_comment_id = '$id'";
    $advertCommentsResult = mysqli_query($conn, $advertCommentsSQL);
    $advertCommentsArray  = mysqli_fetch_all($advertCommentsResult, MYSQLI_ASSOC);
}

if (isset($_POST['comment_to_delete'])){
    $commentToDelete      = mysqli_real_escape_string($conn, $_POST['comment_to_delete']);
    $commentToDeleteSQL   = "DELETE FROM advert_comments WHERE advert_comment_id = '$id' AND user_comment_id = '$userID'";
    $commentToDeleteQuery = mysqli_query($conn, $commentToDeleteSQL);

    if ($commentToDeleteQuery){
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "query error - " . mysqli_error($conn);
    }
}

?>

<?php include "header.php"; ?>
    <main>
        <section class="advert">

            <div class="advert-inner">
                <?php if ($advert) : ?>
                    <div class="advert__thumbnail-wrapper">
                        <?php if ($advertThumbnailArray) : ?>
                            <img class="advert__thumbnail"
                                 src="uploads/advert_images/<?php echo $advertThumbnailArray['advert_image']; ?>"
                                 alt="thumbnail">
                        <?php endif; ?>
                    </div>

                    <div class="advert__text-content">
                        <h4 class="advert__title"><?php echo htmlspecialchars($advert['title']); ?></h4>

                        <?php if ($author['id'] === $_SESSION['id']) : ?>
                            <h5 class="author__name"><a href="mycabinet.php"><?php echo htmlspecialchars($author['nickname']); ?></a></h5>
                        <?php else: ?>
                            <h5 class="author__name"><a href="single-user.php?id=<?php echo $author['id']; ?>"><?php echo htmlspecialchars($author['nickname']); ?></a></h5>
                        <?php endif; ?>

                        <div class="advert__description"><p><?php echo htmlspecialchars($advert['description']); ?></p>
                        </div>

                        <div class="advert__contacts"><p><?php echo htmlspecialchars($advert['contacts']); ?></p></div>

                        <div class="advert__date"><p><?php echo htmlspecialchars($advert['created_at']); ?></p></div>
                    </div>
                <?php else : ?>
                    <h3 class="advert-print-error"><?php echo "Sorry, but advert is not exist."; ?></h3>
                <?php endif; ?>
            </div>

            <?php if ($author['id'] === $_SESSION['id']) : ?>
            <div class="advert-edit-forms">
                <div class="form-wrapper">
                    <form action="single-advert.php?id=<?php echo $advertID; ?>" method="POST"
                          enctype="multipart/form-data">
                        <input type="hidden" name="size" value="100000000">

                        <div>
                            <input type="file" name="image">
                        </div>

                        <input type="submit" name="advert_image_upload" value="Upload Image">
                    </form>
                </div>

                <button class="js-single-advert-edit single-advert-edit">Edit advert</button>

                <div class="js-edit-form form-wrapper edit-form">
                    <form action="single-advert.php?id=<?php echo $advertID; ?>" method="POST">
                        <input type="text"
                               placeholder="Title of advert"
                               name="title"
                               required
                               value="<?php echo $advert['title']; ?>"
                        >

                        <textarea rows="3"
                                  cols="20"
                                  placeholder="Left some contact information in this label"
                                  name="contacts"
                        ><?php echo $advert['contacts']; ?></textarea>

                        <textarea rows="5"
                                  cols="50"
                                  placeholder="Advert description"
                                  name="description"
                        ><?php echo $advert['description']; ?></textarea>

                        <input
                                type="submit"
                                name="edit_advert"
                                value="Submit Edit"
                        >
                    </form>
                </div>

                <div class="form-wrapper">
                    <!--     DELETE FORM        -->
                    <form action="single-advert.php?id=<?php echo $advertID; ?>" method="POST">
                        <input type="hidden" name="advert_to_delete" value="<?php echo $advertID; ?>">
                        <input type="submit" name="delete" value="Delete Advert">
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <?php if ($advert) : ?>
            <section class="comments">
                <div class="comments-inner">
                    <?php if ($_SESSION['id']) : ?>
                        <div class="comment-form">
                            <form action="single-advert.php?id=<?php echo $advertID; ?>" method="POST">
                                <textarea rows="4"
                                          cols="20"
                                          placeholder="Left some comment about this advert..."
                                          name="advert_comment_text"
                                ><?php echo $contacts; ?></textarea>

                                <input type="submit" name="left_comment" value="Left comment">
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php if ($advertCommentsArray) : ?>
                        <div class="comments-list">
                            <?php foreach ($advertCommentsArray as $advertComment) :
                                $commentID     = htmlspecialchars($advertComment['id']);
                                $commentText   = htmlspecialchars($advertComment['comment_text']);
                                $commentUserID = htmlspecialchars($advertComment['user_comment_id']);

                                if ($commentUserID){
                                    $commentUserAvatarSQL    = "SELECT * FROM users_images WHERE image_owner = '$commentUserID'";
                                    $commentUserAvatarResult = mysqli_query($conn, $commentUserAvatarSQL);
                                    $commentUserAvatarArray  = mysqli_fetch_array($commentUserAvatarResult);
                                }

                                if ($commentUserID){
                                    $userComment       = "SELECT * FROM users WHERE id = '$commentUserID'";
                                    $userCommentResult = mysqli_query($conn, $userComment);
                                    $userCommentArray  = mysqli_fetch_assoc($userCommentResult);
                                }
                                ?>
                                    <div class="comment-single">
                                        <div class="comment-author">
                                            <?php if ($commentUserAvatarArray['user_image']) : ?>
                                                <div class="author__avatar-wrapper">
                                                    <img class="author__avatar"
                                                         src="uploads/user_images/<?php echo $commentUserAvatarArray['user_image']; ?>"
                                                         alt="User avatar">
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($author['id'] === $_SESSION['id']) : ?>
                                                <h5 class="author__name"><a href="mycabinet.php"><?php echo $userCommentArray['nickname']; ?></a></h5>
                                            <?php else: ?>
                                                <h5 class="author__name"><a href="single-user.php?id=<?php echo $userCommentArray['id']; ?>"><?php echo $userCommentArray['nickname']; ?></a></h5>
                                            <?php endif; ?>
                                        </div>

                                        <div class="comment-text"><p><?php echo $commentText; ?></p></div>

                                        <?php if ($userID === $commentUserID) : ?>
                                            <!--     DELETE FORM        -->
                                            <form action="single-advert.php?id=<?php echo $advertID; ?>" method="POST">
                                                <input type="hidden" name="comment_to_delete" value="<?php echo $commentID; ?>">
                                                <input type="submit" name="delete" value="Delete Comment">
                                            </form>
                                        <?php endif; ?>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
<?php include "footer.php"; ?>