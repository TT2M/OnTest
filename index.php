<?php session_start();
if (isset($_SESSION['flash'])) { //если существует флеш сообщение, у нас оно при выходе записывается
    echo $_SESSION['flash']; // выводим его
    unset($_SESSION['flash']); // удаляем из сесии
}
if (empty($_SESSION['auth'])) { //кнопки видны если пользователь не авторизован иначе скрыты
    ?>
    <h3>Главная</h3>
<form action="http://testonly/reg.php" target="_blank">
    <button>Переход на страницу регистрации</button>
</form>
<form action="http://testonly/log.php" target="_blank">
    <button>Авторизация</button>
    <?php  }
    if (!empty($_SESSION['auth'])) { //кнопки видны если пользователь авторизован
        ?>
</form>
<form action="http://testonly/logout.php" target="_blank">
    <button>Выход</button>
</form>

    <form action="http://testonly/prof.php" target="_blank">
    <button>Мой профиль</button>
</form>
    <?php  } ?>
