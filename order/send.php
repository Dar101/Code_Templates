<?php
function show_form()
{
?>
<form action="" method=post enctype="multipart/form-data">
<div align="center">
              <br />Имя*<br />
              <input type="text" name="name" size="40">
              <br />Контактный телефон<br />
              <input type="text" name="tel" size="40">
              <br />Контактный email*<br />
              <input type="text" name="email" size="40">
              <br />Teма<br />
              <input type="text" name="title" size="40">
              <br />Сообщение*<br />
              <textarea rows="10" name="mess" cols="30"></textarea>
              <br />Файл<br />
              <input name="attachfile" type="file" size="28">
              <br />Изображение<br />
              <input name="attachimage" type="file" size="28">
              <br /><br /><input type="submit" value="Отправить" name="submit">
</div>
</form>
* Помечены поля, которые необходимо заполнить
<?
}

function complete_mail() {
        // $_POST['title'] содержит данные из поля "Тема", trim() - убираем все лишние пробелы и переносы строк, htmlspecialchars() - преобразует специальные символы в HTML сущности, будем считать для того, чтобы простейшие попытки взломать наш сайт обломались, ну и  substr($_POST['title'], 0, 1000) - урезаем текст до 1000 символов. Для переменных $_POST['mess'], $_POST['name'], $_POST['tel'], $_POST['email'] все аналогично
        $_POST['title'] =  substr(htmlspecialchars(trim($_POST['title'])), 0, 1000);
        $_POST['mess'] =  substr(htmlspecialchars(trim($_POST['mess'])), 0, 1000000);
        $_POST['name'] =  substr(htmlspecialchars(trim($_POST['name'])), 0, 30);
        $_POST['tel'] =  substr(htmlspecialchars(trim($_POST['tel'])), 0, 30);
        $_POST['email'] =  substr(htmlspecialchars(trim($_POST['email'])), 0, 50);
        // если не заполнено поле "Имя" - показываем ошибку 0
        if (empty($_POST['name']))
             output_err(0);
        // если неправильно заполнено поле email - показываем ошибку 1
        if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email']))
             output_err(1);
        // если не заполнено поле "Сообщение" - показываем ошибку 2
        if(empty($_POST['mess']))
             output_err(2);
        // обратите внимание, теперь мы можем писать красивые письма, с помощью html тегов ;-)
        $mess = '
<b>Имя отправителя:</b>'.$_POST['name'].'<br />
<b>Контактный телефон:</b>'.$_POST['tel'].'<br />
<b>Контактный email:</b>'.$_POST['email'].'<br />
'.$_POST['mess'];

        // подключаем файл класса для отправки почты
        require 'class.phpmailer.php';

        $mail = new PHPMailer();
        $mail->From = 'test@test.ru';      // от кого
        $mail->FromName = 'www.php-mail.ru';   // от кого
        $mail->AddAddress('zakaz@mat-an.ru', 'Имя'); // кому - адрес, Имя
        $mail->IsHTML(true);        // выставляем формат письма HTML
        $mail->Subject = $_POST['title'];  // тема письма

        // если был файл, то прикрепляем его к письму
        if(isset($_FILES['attachfile'])) {
                 if($_FILES['attachfile']['error'] == 0){
                    $mail->AddAttachment($_FILES['attachfile']['tmp_name'], $_FILES['attachfile']['name']);
                 }
        }
        // если было изображение, то прикрепляем его в виде картинки к телу письма.
        if(isset($_FILES['attachimage'])) {
                 if($_FILES['attachimage']['error'] == 0){
                    if (!$mail->AddEmbeddedImage($_FILES['attachimage']['tmp_name'], 'my-attach', 'image.gif', 'base64', $_FILES['attachimage']['type']))
                         die ($mail->ErrorInfo);
                    $mess .= 'А вот и наша картинка:<br /><img src="cid:image.gif" border=0><br />я показал как ее прикреплять, соответственно Вам осталось вставить ее в нужное место Вашего письма ;-) ';
                 }
        }
        $mail->Body = $mess;

        // отправляем наше письмо
        if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
        echo 'Спасибо! Ваше письмо отправлено.';
}

function output_err($num)
{
    $err[0] = 'ОШИБКА! Не введено имя.';
    $err[1] = 'ОШИБКА! Неверно введен e-mail.';
    $err[2] = 'ОШИБКА! Не введено сообщение.';
    echo '<p>'.$err[$num].'</p>';
    show_form();
    exit();
}

if (!empty($_POST['submit'])) complete_mail();
else show_form();
?> 