<?php include "include/db.php"; ?>
<?php include "include/header.php"; ?>

<?php
require_once "config.php";
$loginURL = $gClient->createAuthUrl();
?>
<script src="https://kit.fontawesome.com/b4547f4381.js" crossorigin="anonymous"></script>
<?php
$db_user_password = '';
$message = '';
$db_username = '';
$remember_password = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_password = $_POST['password'];

    $username = mysqli_real_escape_string($connection, $username); //clean data
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }

    while ($row = mysqli_fetch_array($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_role = $row['user_role'];
        $db_user_email = $row['user_email'];
    }

    $password = crypt($password, $db_user_password); //uncrypt

    if ($username === $db_username && $password === $db_user_password) {
        $_SESSION['userId'] = $db_user_id;
        $_SESSION['username'] = $db_username;
        $_SESSION['user_role'] = $db_user_role;
        $_SESSION['user_password'] = $db_user_password;
        $_SESSION['user_email'] = $db_user_email;

        if (isset($_POST['remember'])) {
            setcookie('remember_userId', $db_user_id, time() + 86400);
            setcookie('remember_user', $username, time() + 86400);
            setcookie('remember_password', $remember_password, time() + 86400);
            $location = isset($_GET['from']) ? $_GET['from'] : 'index.php';
            header("Location: ./$location");
        } else {
            $location = isset($_GET['from']) ? $_GET['from'] : 'index.php';
            header("Location: ./$location");
        }
    } else {
        $message = 'Sai t??n ????ng nh???p ho???c m???t kh???u!';
    }
}
?>

<div class="main">
    <form action="" method="POST" class="form" id="form-2">
        <div>
            <script>
                window.onload = function () {
                    let searchParams = new URLSearchParams(window.location.search);
                    let from = 'index.php';
                    if (searchParams.has('from')) {
                        from = searchParams.get('from');
                    }
                    document.getElementById('back-button').setAttribute('href', from);
                }
            </script>
            <a id="back-button" href="index.php" class="btn btn-danger btn-sm icon-back"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h5><?php echo $message; ?></h5>
        <div class="form-header">
            <img class="form-logo" src="./images/logo.jpg" alt="logo">
            <h3 class="heading">????ng nh???p</h3>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">T??n ????ng nh???p</label>
            <input type="text" name="username" class="form-control" placeholder="Nh???p username" value="<?php if (isset($_COOKIE['remember_user'])) {
                                                                                                            echo $_COOKIE['remember_user'];
                                                                                                        } ?>">
            <span class="form-message"></span>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">M???t kh???u</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Nh???p m???t kh???u" value="<?php if (isset($_COOKIE['remember_password'])) {
                                                                                                                                echo $_COOKIE['remember_password'];
                                                                                                                            } ?>">
            <span class="form-message"></span>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="check" name="remember">
            <label class="form-check-label" for="check">Nh??? m???t kh???u</label>
        </div>
        <button name="login" class="btn btn-danger form-submit">????ng nh???p</button>
        <div class="row line">
            <hr class="ORline">
            <span class="ORtext">OR</span>
            <hr class="ORline">
        </div>
        <div class="row">
            <p class="textgoogleaccount">B???n c?? t??i kho???n Google?</p>

            <div class="col-xs-12 col-md-12 col-sm-12">
                <a href="<?php echo $loginURL ?>" class="badge badge-light googlesignupbutton">
                    <img src="./images/google.jpg" alt="google logo" class="googlelogo">
                    ????ng nh???p v???i t??i kho???n Google
                </a>
            </div>
        </div>
    </form>
</div>

<?php include "include/end.php"; ?>