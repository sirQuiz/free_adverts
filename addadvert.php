<?php
include 'config/db_connect.php';
session_start();
$userID       = $_SESSION['id'];
$userNickname = $_SESSION['nickname'];

//if (!$userID){
//    header("location login.php");
//} //TO DO!!!!!!!!!!!!!!!!!!!!!

$title = $description = $contacts = '';

if (isset($_POST['add_advert'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $contacts = mysqli_real_escape_string($conn, $_POST['contacts']);
    $advertAuthor = $userNickname;

    $advertSQL = "INSERT INTO adverts(title, description, contacts, author_id) VALUES('$title', '$description', '$contacts', '$userID')";
    $advertQuery = mysqli_query($conn, $advertSQL);
    if ($advertQuery) {
        //success
        header('Location: mycabinet.php');
    } else {
        echo 'query error' . mysqli_error($conn);
    }

    // close connection
    mysqli_close($conn);
}
?>
<?php include "header.php"; ?>
    <main class="main-content">
        <section class="add-advert">
            <div class="form-wrapper">
                <form action="addadvert.php" method="POST">
                    <input type="text"
                           placeholder="Title of advert"
                           name="title"
                           required
                           value="<?php echo $title; ?>"
                    >

                    <textarea rows="3"
                              cols="20"
                              placeholder="Left some contact information in this label"
                              name="contacts"
                    ><?php echo $contacts; ?></textarea>

                    <textarea rows="5"
                              cols="50"
                              placeholder="Advert description"
                              name="description"
                    ><?php echo $description; ?></textarea>

                    <input
                            type="submit"
                            name="add_advert"
                            value="Add Advert"
                    >
                </form>
            </div>
        </section>
    </main>
<?php include "footer.php"; ?>