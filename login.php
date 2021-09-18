<?php
session_start();

include 'config/db_connect.php';
$loginError = $nickname = $password = $email = $fullName = $aboutUser = '';
$iD = 0;

if (isset($_POST['login'])) {

    $nickname = $_POST['nickname'];
    $password = $_POST['password'];

    $loginSelect     = "SELECT * FROM users WHERE nickname = '$nickname' && password = '$password'";
    $loginResult     = mysqli_query($conn, $loginSelect);
    $loginUsersArray = mysqli_fetch_all($loginResult, MYSQLI_ASSOC);

    foreach ($loginUsersArray as $loginSys) {
        $iD        = $loginSys['id'];
        $email     = $loginSys['email'];
        $fullName  = $loginSys['full_name'];
        $aboutUser = $loginSys['about_user'];
    }
    $checkLogin    = mysqli_num_rows($loginResult);
    $checkPassword = mysqli_num_rows($loginResult);

    if ($checkLogin == 1) {
        $_SESSION['id']         = $iD;
        $_SESSION['nickname']   = $nickname;
        $_SESSION['password']   = $password;
        $_SESSION['email']      = $email;
        $_SESSION['full_name']  = $fullName;
        $_SESSION['about_user'] = $aboutUser;
        header('Location: index.php');
    } else {
        $loginError = "Nickname/password incorrect";
    }
}
?>

<?php include "header.php"; ?>
<main>
    <section>
        <div class="form-wrapper">
            <form action="login.php" method="POST">
                <h2>Log In</h2>

                <input type="text" name="nickname" required value="<?php echo $nickname; ?>">

                <input type="password" name="password" required value="<?php echo $password; ?>">

                <input type="submit" name="login" value="Submit">
            </form>

            <span class="error"><?php echo $loginError; ?></span>
        </div>
    </section>
</main>
<?php include "footer.php"; ?>
