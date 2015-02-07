<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Обратная связь</title>
</head>

<body>
<form class="text_forma" action="" method="post" enctype="multipart/form-data">
        <strong>Ваше имя:</strong><br />
        <input type="text" name="name" size="30"/>
        <br />
        <strong>Тема:</strong><br />
        <input type="text" name="title" size="30"/>
        <br />
        <strong>Ваш email:</strong><br />
        <input type="text" name="email" size="30"/>
        <br />
        <strong>Выбор  файла для отправки:</strong><br />
        <input type="file" name="files"  size="30"/>
        <br />
        <strong>Ваше сообщение:</strong><br />
        <textarea rows="5" name="mess" cols="30"></textarea>
        <br />
        <input type="submit" value="Отправить сообщение" name="submit"/>
      </form>
<?php
function send_mail()
{
$name = htmlspecialchars($_REQUEST['name']);
}
{
$email = htmlspecialchars($_REQUEST['email']);
}
$message = '<b>Имя пославшего: </b>'.$_REQUEST['name'].'<br> <b>Электронный адрес: </b>'.$_REQUEST['email'].'<br><b>Сообщение: </b>'.$_REQUEST['mess'];

include "class.phpmailer.php";// подключаем класс

$mail = new PHPMailer();
$mail->From = $_REQUEST['email'];
$mail->FromName = $_REQUEST['name'];
$mail->AddAddress('zakaz@toe5.ru');
$mail->IsHTML(true);
$mail->Subject = $_POST['title'];

if(isset($_FILES['files']))
{
if($_FILES['files']['error'] == 0)
{
$mail->AddAttachment($_FILES['files']['tmp_name'],$_FILES['files']['name']);
}
}
$mail->Body = $message;
if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
{
echo '<center><b>Спасибо за отправку вашего сообщения<br><a href=index.html>Нажмите</a>, чтобы вернуться на главную страницу';
}
if (!empty($_POST['submit'])) send_mail();
?>
</body>
</html>