<?php
include 'config/db_connect.php';
session_start();

$errors = array(
    'nickname' => '',
    'email' => '',
);

$userNickName = $_SESSION['nickname'];
$usersSLQ     = "SELECT * FROM users WHERE nickname = '$userNickName'";
$usersRESULT  = mysqli_query($conn, $usersSLQ);
$usersArray   = mysqli_fetch_all($usersRESULT, MYSQLI_ASSOC);
$userID       = 0;

foreach ($usersArray as $user) {
    $userID       = htmlspecialchars($user['id']);
    $userEmail    = htmlspecialchars($user['email']);
    $userPassword = htmlspecialchars($user['password']);
    $userFullName = htmlspecialchars($user['full_name']);
    $userAbout    = htmlspecialchars($user['about_user']);
}

$nickname = $email = $password = $fullName = $aboutUser = '';

if (isset($_POST['edit'])) {
    $nickname  = mysqli_real_escape_string($conn, $_POST['nickname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $fullName  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $aboutUser = mysqli_real_escape_string($conn, $_POST['about_user']);

    // save to db and check
    //check if nickname or email is already taken
    $userNicknameIdentify = mysqli_num_rows(mysqli_query($conn, "SELECT nickname FROM users WHERE nickname = '" . $_POST['nickname'] . "'"));
    $userEmailIdentify    = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email = '" . $_POST['email'] . "'"));
    if ($userNicknameIdentify > 1) {
        $errors['nickname'] = "This nickname is already take.";
    } else if ($userEmailIdentify > 1) {
        $errors['email'] = "This email is already taken.";
    } else {
        //create sql
        $sqlUserEdit = "UPDATE users SET nickname='$nickname', email='$email', password='$password', full_name='$fullName', about_user='$aboutUser' WHERE id ='$userID'";

        if (mysqli_query($conn, $sqlUserEdit)) {
            //success
            header('Location: relogin.php');
        } else {
            echo 'query error' . mysqli_error($conn);
        }
    }

    // close connection
    mysqli_close($conn);
}

?>
<?php include "header.php"; ?>
<main>
    <section class="user-cabinet">
        <p class="admin__message">The Administration asks you to be tolerant to errors and some issues. For now if you want to change some information you need to login after. Best wishes the Administration.</p>

        <div>
            <div class="user">
                <div class="form-wrapper">
                    <form action="useredit.php" method="POST">
                        <input type="text"
                               placeholder="Nickname"
                               name="nickname"
                               required
                               value="<?php echo $userNickName; ?>"
                        >
                        <span>
                            <?php echo $errors['nickname']; ?>
                        </span>

                        <input type="email"
                               placeholder="E-mail"
                               name="email"
                               required
                               value="<?php echo $userEmail; ?>"
                        >
                        <span>
                            <?php echo $errors['email']; ?>
                        </span>

                        <input type="text"
                               placeholder="password"
                               name="password"
                               required
                               value="<?php echo $userPassword; ?>"
                        >

                        <input type="text"
                               placeholder="Full Name"
                               name="full_name"
                               value="<?php echo $userFullName; ?>"
                        >

                        <textarea rows="5"
                                  cols="10"
                                  placeholder="Describe you. And left some contact information"
                                  name="about_user"
                        ><?php echo $userAbout; ?></textarea>

                        <input
                                type="submit"
                                name="edit"
                                value="Submit Edit"
                        >
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include "footer.php"; ?>
