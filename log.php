<?php
session_start();?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <h3>Авторизация</h3>
    <form action="http://testonly" target="_blank">
        <button>На главную </button>
    </form>

<?php
if (empty($_SESSION['auth'])) { // кнопка ниже отображаются если юзер неАвторизован
    ?>
    <form action="http://testonly/reg.php" target="_blank">
        <button>Регистрация</button>
    </form>
    <?php  }  // кнопки ниже отображаются если юзер Авторизован
if (!empty($_SESSION['auth'])) {?>
    <form action="http://testonly/logout.php" target="_blank">
        <button>Выход</button>
    </form>
    <form action="http://testonly/prof.php" target="_blank">
        <button>Мой профиль</button>

    </form>
    <?php echo 'Вы вошли в аккаунт'; } ?>


<?php
if (empty($_SESSION['auth'])) { // проверка инфы в сесии о авторизации пользователя

$host = 'localhost'; // имя хоста
$user = 'root';      // имя пользователя
$pass = '';          // пароль
$name = 'user';      // имя базы данных
$link = mysqli_connect($host, $user, $pass, $name);
?>


<form action="" method="POST">
    <label for="login"> Email или номер телефона без +7
    </label>
    <input name="login">
    <br><br> <label for="password"> Пароль
         </label>
    <input name="password" type="password">
    <div class="g-recaptcha" data-sitekey="Ключ капчи 1"></div>
    <br/>
    <br><br> <input type="submit">
</form>

<?php //если форма отправлена проверяем есть ли в бд юзер с такой почтой или телефоном  предварительно проверив капчу
    $response = $_POST["g-recaptcha-response"];
    var_dump($response);
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => 'Ключ капчи 2 ',
        'response' => $_POST["g-recaptcha-response"]
    ];
    $options = [
        'http' => [
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    var_dump($context);
    $verify = file_get_contents($url, false, $context);
    var_dump($verify);
    $captcha_success=json_decode($verify);
    if ($captcha_success->success==false) {
        echo "Пройдите капчу снова";
    } else if ($captcha_success->success==true) {

        if (!empty($_POST['password']) and !empty($_POST['login'])) {
            $login = $_POST['login'];

            $query = "SELECT * FROM user WHERE mail='$login'OR tel='$login'";// поиск в базе или почты или телефона совпадающего
            $res = mysqli_query($link, $query);
            $user = mysqli_fetch_assoc($res);
//var_dump($user);
            if (!empty($user)) { // если есть сопадения в базе
                $hash = $user['pas']; //  пароль из базы

                if (password_verify($_POST['password'], $hash)) { //если пасворд введеный совпадает с старым из базы
                    $_SESSION['auth'] = true;  //  устанвливаем флаг успешной авторизации
                    $_SESSION['id'] = $user['id']; // так же айди пользователя из базы для работы в профиле
                    echo 'Успешная авторизация!';
                    header("Refresh:0");
                } else {
                    echo 'неверно ввел логин или пароль'; // неверно ввел логин или пароль
                }
            } else {
                echo 'неверно ввел логин или пароль'; // неверно ввел логин или пароль
            }
        }
    }
}

?>