<?php
session_start();
include("../header.php");




?>

<?php
//echo $_SESSION['captcha_keystring']."<br>";
$keystring = $_POST['keystring'];
if ($keystring<>''){
	if($_SESSION['captcha_keystring'] == $keystring){
		










        // $_POST['theme'] содержит данные из поля "Тема", trim() - убираем все лишние пробелы и переносы строк, htmlspecialchars() - преобразует специальные символы в HTML сущности, будем считать для того, чтобы простейшие попытки взломать наш сайт обломались, ну и  substr($_POST['title'], 0, 1000) - урезаем текст до 1000 символов. Для переменных $_POST['mess'], $_POST['name'], $_POST['tel'], $_POST['email'] все аналогично
        $theme =  substr(htmlspecialchars(trim($theme)), 0, 1000);
        $txt =  substr(htmlspecialchars(trim($txt)), 0, 1000000);
        $name =  substr(htmlspecialchars(trim($name)), 0, 30);
        $_POST['tel'] =  substr(htmlspecialchars(trim($_POST['tel'])), 0, 30);
        $cont =  substr(htmlspecialchars(trim($cont)), 0, 50);
        // если не заполнено поле "Имя" - показываем ошибку 0
        // обратите внимание, теперь мы можем писать красивые письма, с помощью html тегов ;-)
        $txt = '
<b>Имя отправителя:</b>'.$name.'<br />
<b>Контактные данные:</b>'.$cont.'<br />'.$txt;

        // подключаем файл класса для отправки почты
        require 'class.phpmailer.php';

        $mail = new PHPMailer();
        $mail->From = 'We_Have_New@Order.ru';      // от кого
        $mail->FromName = $name;   // от кого
        $mail->AddAddress('an0808@yandex.ru', 'Имя'); // кому - адрес, Имя
        $mail->IsHTML(true);        // выставляем формат письма HTML
        $mail->Subject = $theme;  // тема письма

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
        $mail->Body = $txt;

        // отправляем наше письмо
        if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
        echo '<h2><font color=green>Письмо отправлено. Вы можете отправить еще один заказ.</font></h2>';




	}else{
if ($cont<>""){echo "<center><h2><font color=red>Код введен неверно - попробуйте еще раз</font></h2></center>";}
	}
}
?>

<TABLE wisth=600><TR>
			
    <TD CLASS="content" VALIGN="top" WIDTH="100%" align="left"><BR>
		<center>
	      <h1><? echo $h1; ?></h1>
	     </center>

                    

<br>

<p >*Поля указанные красным цветом обязательны к заполнению</p>            
<form name="form" action="/order/order.php" enctype="multipart/form-data" method=post>
Ваше имя:<br>
<input type="text" name="name" size="25"><br><br>
<font color=red>Тема сообщения</font> <span >(Например "Дифференциальные уравнения", "Интегралы", "ТФКП" или номера задач, которые Вы хотите заказать)</span>:
<br><input type="text" name="theme" value="<? echo $tema; ?>" size="70"><br><br>
Сообщение <span >(дополнительная информация - например, требования по срокам исполнения и предпочитаемые Вами варианты оплаты. В зависимости от сложности, решение задачи стоит от 50 рублей и выше)</span>:
<br><textarea rows="5" name="txt" cols="30"></textarea>
<br><br>
Вложите файл с условиями задачи, если требуется
<br>
<input size="22" name="attachfile" type="file" />
<br><br>
<span style="color:red">Контактные данные (Возможные значения - адрес электронной почты, номер ICQ или позывной Skype. Другие, например номера телефонов, не обрабатываются):</span>
<br>
<textarea rows="5" name="cont" cols="30"></textarea>
<br><br>
<img src="./order/?<?php echo session_name()?>=<?php echo session_id()?>">
<br>
<span style="color:red">Введите код указанный на картинке:</span>
<br>
<input type="text" name="keystring" size="25"><br>
<input type="submit" value="Отправить" name="B1" ONCLICK="return checkIt()">
<input type="reset" value="Сбросить" name="B2"></p></form>
<script language="JavaScript">
function checkIt() {
   if (document.forms.form.cont.value != "") {
      return true; // все отлично
   } else {
      alert("Поле 'Контактные данные' должно быть заполнено! Возможные значения - адрес электронной почты, номер ICQ или позывной Skype");
  return false;
   }
}
//--></SCRIPT>


      </TD>
		</TR>
	
</TABLE>

<?

include ('../footer.php');

?>