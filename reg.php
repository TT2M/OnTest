<?php
session_start();?>
<h3>Регистрация</h3>
<form action="http://testonly" target="_blank">
    <button>На главную </button>
</form>
<?php   // ниже кнопки для пользователей которые не авторизованы
if (empty($_SESSION['auth'])) {?>
<form action="http://testonly/log.php" target="_blank">
    <button>Авторизация</button>
</form>
<?php } // ниже кнопки для пользователей которые  авторизованы
if (!empty($_SESSION['auth'])) {?>
    <form action="http://testonly/logout.php" target="_blank">
        <button>Выход</button>
    </form>
    <form action="http://testonly/prof.php" target="_blank">
        <button>Мой профиль</button>
    </form>
    <?php }

if (empty($_SESSION['auth'])) {  // если пользователь не авторизован то показываем ему формы регистрации

$host = 'localhost'; // имя хоста
$user = 'root';      // имя пользователя
$pass = '';          // пароль
$name = 'user';      // имя базы данных
$link = mysqli_connect($host, $user, $pass, $name);
?>
<form action="" method="POST">
    <label for="name"> Имя
        </label>
    <input required type="text"  name="name" placeholder="ФИО" maxlength="60">
    <br><br> <label for="tel"> Телефон +7
    <input required type="tel" autocomplete="tel" value=""
           placeholder="900 000 00 00" name="tel" maxlength="20" pattern="9[0-9]{9}"title="Используйте
    валидный номер телефона" >
        <br> <br><label  for="mail"> Email
    </label>
    <input required name="mail" type="email" autocomplete="email" value=""
           placeholder="mail@mail.com" maxlength="25">
        <br> <br><label for="password"> Пароль
    </label>
    <input  required name="password" type="password" placeholder="От 5 до 15 символов" maxlength="20"
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}"title="Используйте цифры,и латинские буквы разного регистра
             от 5 до 15 символов"
    >  <!-- Любые цифры, любые малые лат буквы и любые большие  -->
        <br> <br> <label for="conf"> Повтор пароль
    </label>
    <input  required name="conf" type="password" placeholder="Пароль" maxlength="20">
    <input type="submit">
</form>
<?php
if (!empty($_POST['name']) and !empty($_POST['tel'])and !empty($_POST['mail'])and !empty($_POST['password'])) { // проверка что все поля заполнены
    if ($_POST['password'] == $_POST['conf']){ // повтор пароля совпадает с основным
    $name = $_POST['name'];
    $tel=$_POST['tel'];
    $mail=$_POST['mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // добавление хэша в пароль

    $query = "SELECT * FROM user WHERE mail='$mail'";  // запрос поиск пользователей с такими де данными по телефону
    $user = mysqli_fetch_assoc(mysqli_query($link, $query));  // превращаем в асоциативный массив
   // var_dump($user);
        $flag=0;
        if (!empty($user))
        {
         echo 'Email используется одним из пользователей';
         $flag=1;
        }
        $query = "SELECT * FROM user WHERE tel='$tel'"; // запрос поиск пользователей с такими де данными по телефону
        $user1 = mysqli_fetch_assoc(mysqli_query($link, $query)); // превращаем в асоциативный массив
        if (!empty($user1))
        {
            echo 'Телефон используется одним из пользователей';
            $flag=1;
        }
        if($flag==0) { //если в базе пользователь с такими же данными не найден то регистрируем пользователя
    $query = "INSERT INTO user SET name='$name', tel='$tel',mail='$mail',pas='$password'"; // запись данных в базу
    mysqli_query($link, $query);
    $_SESSION['auth'] = true; // пометка об авторизации
    $id = mysqli_insert_id($link);
    $_SESSION['id'] = $id; // пишем id в сессию для работы с редактированием
            echo 'Поздравляю с успешной регистрацией и входом!';
            header("Refresh:0"); // обновление для скрытия форм
}
    }
    else {
        echo 'пароли не совпадают';
    }
}}
else{
    echo 'Вы находитесь в системе, для изменения данных перейдите в профиль';
}
?>
