<?php
include 'config/db_connect.php';
$errors = array(
    'nickname' => '',
    'email' => '',
);

$nickname = $email = $password = $fullName = $aboutUser = '';

if (isset($_POST['regis'])) {
    $nickname  = mysqli_real_escape_string($conn, $_POST['nickname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $fullName  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $aboutUser = mysqli_real_escape_string($conn, $_POST['about_user']);

    // save to db and check
    //check if nickname or email is already taken
    $userNicknameIdentify = mysqli_num_rows(mysqli_query($conn, "SELECT nickname FROM users WHERE nickname = '" . $_POST['nickname'] . "'"));
    $userEmailIdentify    = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email = '" . $_POST['email'] . "'"));

    if ($userNicknameIdentify) {
        $errors['nickname'] = "This nickname is already take.";
    } else if ($userEmailIdentify) {
        $errors['email'] = "This email is already taken.";
    } else {
        //create sql
        $sqlUserRegister = "INSERT INTO users(nickname, email, password, full_name, about_user) VALUES('$nickname', '$email', '$password', '$fullName', '$aboutUser')";

        if (mysqli_query($conn, $sqlUserRegister)) {
            //success
            header('Location: login.php');
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
        <section>
            <div class="form-wrapper">
                <form action="registration.php" method="POST">
                    <input type="text"
                           placeholder="Nickname"
                           name="nickname"
                           required
                           value="<?php echo $nickname; ?>"
                    >
                    <span>
                        <?php echo $errors['nickname']; ?>
                    </span>

                    <input type="email"
                           placeholder="E-mail"
                           name="email"
                           required
                           value="<?php echo $email; ?>"
                    >
                    <span>
                        <?php echo $errors['email']; ?>
                    </span>

                    <input type="password"
                           placeholder="password"
                           name="password"
                           required
                           value="<?php echo $password; ?>"
                    >

                    <input type="text"
                           placeholder="Full Name"
                           name="full_name"
                           value="<?php echo $fullName; ?>"
                    >

                    <textarea rows="5"
                              cols="10"
                              placeholder="Describe you. And left some contact information"
                              name="about_user"
                    ><?php echo $aboutUser; ?></textarea>

                    <input
                        type="submit"
                        name="regis"
                        value="Sign Up"
                    >
                </form>
            </div>
        </section>
    </main>
<?php include "footer.php"; ?>