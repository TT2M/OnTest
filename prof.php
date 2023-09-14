<?php session_start();
$id = $_SESSION['id'];
$host = 'localhost'; // имя хоста
$user = 'root';      // имя пользователя
$pass = '';          // пароль
$name = 'user';      // имя базы данных
$link = mysqli_connect($host, $user, $pass, $name);

function verif ($tel,$mail)  // проверка занятости почты и телефона
{    $id = $_SESSION['id']; //айди авторизованого юзера из сесии
    $host = 'localhost'; // хост
    $user = 'root';      // пользователь
    $pass = '';          // пароль
    $name = 'user';      // имя бд
    $link = mysqli_connect($host, $user, $pass, $name);
    $query = "SELECT * FROM user WHERE mail='$mail'";  // запрос по почте
    $user = mysqli_fetch_assoc(mysqli_query( $link, $query));
    $flag=0;
    if (!empty($user) and $user['id']!=$id)  // если существует пользователь с такими данными,
                                              //которые не пренадлежат этому айди
    {
        echo 'Email используется одним из пользователей';
        $flag=1;
    }
    $query = "SELECT * FROM user WHERE tel='$tel'";  //запрос по телефону( наверное так не оч ? нужно было все в 1 запрос засунуть?
    $user1 = mysqli_fetch_assoc(mysqli_query($link, $query));

    if (!empty($user1) and $user1['id']!=$id)
        // если существует пользователь с такими данными,
        //которые не пренадлежат этому айди
    {
        echo 'Телефон используется одним из пользователей';
        $flag=1;
    }
    return $flag; // возвращаем флаг
}
?>
<!-- Кнопки для перехода на другие стр -->
    <h3>Профиль</h3>
<form action="http://testonly" target="_blank">
    <button>На главную </button>
</form>
<form action="http://testonly/logout.php" target="_blank">
    <button>Выход</button>
</form>


<?php if (!empty($_SESSION['auth'])) { // если в глобальной переменной есть отметка о авторизации
    $id = $_SESSION['id'];  //айди пользователя в сесии
    $query = "SELECT * FROM user WHERE id='$id'";
    $res = mysqli_query($link, $query); // запрос в бд по айди юзера
    $user = mysqli_fetch_assoc($res);  // конвертируем в асоциативный массив

    ?>
    <h3>Информация которую можно отредактировать:</h3>
    <h4>Заполните поля которые необходимо изменить новыми данными остальные оставьте без изменений </h4>

    <form action="" method="POST">
      <br><br><label for="name"> ФИО</label>
        <input name="name" value="<?= $user['name'] ?>">
        <br><br><label for="tel"> Телефон +7</label>
            <input name="tel" value="<?= $user['tel'] ?>" pattern="9[0-9]{9}"title="Используйте
    валидный номер телефона">
        <br><br><label for="email"> Почта</label>
        <input name="mail" type="email"  value="<?= $user['mail'] ?>">


        <br> <br><label for="old_password"> Старый пароль
        </label>
        <input   name="old_password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}"title="Используйте
         цифры,и латинские буквы разного регистра от 5 до 15 символов"
        >

        <br> <br><label for="new_password"> Новый пароль
        </label>
        <input  name="new_password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}"title="Используйте
         цифры,и латинские буквы разного регистра от 5 до 15 символов"
        >  <!-- Любые цифры, любые малые лат буквы и любые большие  -->


        <br> <br><label for="confirm_new_pas"> Повторите новый пароль
        </label>
        <input   name="confirm_new_pas" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}"title="Используйте
         цифры,и латинские буквы разного регистра от 5 до 15 символов"
        >  <!--  проверка на клиенте Любые цифры, любые малые лат буквы и любые большие  -->

        <br><br><input type="submit" name="submit">
</form>
     <?php
    if (!empty($_POST['submit'])) {
        $name = $_POST['name']; // имя из формы
        $tel=$_POST['tel']; // телефон из формы
        $mail=$_POST['mail']; // почта из формы
        $old_pas=$_POST['old_password']; // старый пароль
        $new_pas=$_POST['new_password'];  //новый пароль
        $new_pasConf=$_POST['confirm_new_pas']; // повторный пароль
        $hash = $user['pas']; // пароль из бд


        if (!empty($old_pas) or !empty($new_pas) or !empty($new_pasConf)) { // если одно из полей пароля заполнено
            if (password_verify($old_pas, $hash)) { // введеный старый пароль совпадает с тем что в базе
                if (!empty($new_pas) and !empty($new_pasConf) and ($new_pas==$new_pasConf)) { // новый парол вторай раз введен верно
                    $flag = verif($tel, $mail);// проверяем на занятость почту и телефон

                    if ($flag == 0) {
                        $newPas = password_hash($new_pas, PASSWORD_DEFAULT); // добавляем хэш в новый пароль
                        $query = "UPDATE user SET name='$name', tel='$tel',mail='$mail',pas='$newPas' WHERE id='$id'";
                        mysqli_query($link, $query);
                        echo 'Пароль успешно обновлен';
                        header("Refresh:0");
                    }
                } else {
                    echo 'Пароли не совпадают или пусты';
                }
            } else {
                echo 'Старый пароль введен неверно';
            }
        }
else {
    $flag = verif($tel, $mail);// проверяем на занятость почту и телефон

    if ($flag == 0) {
        $query = "UPDATE user SET name='$name', tel='$tel',mail='$mail' WHERE id='$id'";
        mysqli_query($link, $query);
        header("Refresh:0");
}
    }
    }

}
else{
    header('Location: index.php');} //если пользователь не авторизован выкидываем на главную
?>