<?php
function show_form()
{
?>
<form action="" method=post enctype="multipart/form-data">
<div align="center">
              <br />���*<br />
              <input type="text" name="name" size="40">
              <br />���������� �������<br />
              <input type="text" name="tel" size="40">
              <br />���������� email*<br />
              <input type="text" name="email" size="40">
              <br />Te��<br />
              <input type="text" name="title" size="40">
              <br />���������*<br />
              <textarea rows="10" name="mess" cols="30"></textarea>
              <br />����<br />
              <input name="attachfile" type="file" size="28">
              <br />�����������<br />
              <input name="attachimage" type="file" size="28">
              <br /><br /><input type="submit" value="���������" name="submit">
</div>
</form>
* �������� ����, ������� ���������� ���������
<?
}

function complete_mail() {
        // $_POST['title'] �������� ������ �� ���� "����", trim() - ������� ��� ������ ������� � �������� �����, htmlspecialchars() - ����������� ����������� ������� � HTML ��������, ����� ������� ��� ����, ����� ���������� ������� �������� ��� ���� ����������, �� �  substr($_POST['title'], 0, 1000) - ������� ����� �� 1000 ��������. ��� ���������� $_POST['mess'], $_POST['name'], $_POST['tel'], $_POST['email'] ��� ����������
        $_POST['title'] =  substr(htmlspecialchars(trim($_POST['title'])), 0, 1000);
        $_POST['mess'] =  substr(htmlspecialchars(trim($_POST['mess'])), 0, 1000000);
        $_POST['name'] =  substr(htmlspecialchars(trim($_POST['name'])), 0, 30);
        $_POST['tel'] =  substr(htmlspecialchars(trim($_POST['tel'])), 0, 30);
        $_POST['email'] =  substr(htmlspecialchars(trim($_POST['email'])), 0, 50);
        // ���� �� ��������� ���� "���" - ���������� ������ 0
        if (empty($_POST['name']))
             output_err(0);
        // ���� ����������� ��������� ���� email - ���������� ������ 1
        if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email']))
             output_err(1);
        // ���� �� ��������� ���� "���������" - ���������� ������ 2
        if(empty($_POST['mess']))
             output_err(2);
        // �������� ��������, ������ �� ����� ������ �������� ������, � ������� html ����� ;-)
        $mess = '
<b>��� �����������:</b>'.$_POST['name'].'<br />
<b>���������� �������:</b>'.$_POST['tel'].'<br />
<b>���������� email:</b>'.$_POST['email'].'<br />
'.$_POST['mess'];

        // ���������� ���� ������ ��� �������� �����
        require 'class.phpmailer.php';

        $mail = new PHPMailer();
        $mail->From = 'test@test.ru';      // �� ����
        $mail->FromName = 'www.php-mail.ru';   // �� ����
        $mail->AddAddress('zakaz@mat-an.ru', '���'); // ���� - �����, ���
        $mail->IsHTML(true);        // ���������� ������ ������ HTML
        $mail->Subject = $_POST['title'];  // ���� ������

        // ���� ��� ����, �� ����������� ��� � ������
        if(isset($_FILES['attachfile'])) {
                 if($_FILES['attachfile']['error'] == 0){
                    $mail->AddAttachment($_FILES['attachfile']['tmp_name'], $_FILES['attachfile']['name']);
                 }
        }
        // ���� ���� �����������, �� ����������� ��� � ���� �������� � ���� ������.
        if(isset($_FILES['attachimage'])) {
                 if($_FILES['attachimage']['error'] == 0){
                    if (!$mail->AddEmbeddedImage($_FILES['attachimage']['tmp_name'], 'my-attach', 'image.gif', 'base64', $_FILES['attachimage']['type']))
                         die ($mail->ErrorInfo);
                    $mess .= '� ��� � ���� ��������:<br /><img src="cid:image.gif" border=0><br />� ������� ��� �� �����������, �������������� ��� �������� �������� �� � ������ ����� ������ ������ ;-) ';
                 }
        }
        $mail->Body = $mess;

        // ���������� ���� ������
        if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
        echo '�������! ���� ������ ����������.';
}

function output_err($num)
{
    $err[0] = '������! �� ������� ���.';
    $err[1] = '������! ������� ������ e-mail.';
    $err[2] = '������! �� ������� ���������.';
    echo '<p>'.$err[$num].'</p>';
    show_form();
    exit();
}

if (!empty($_POST['submit'])) complete_mail();
else show_form();
?> 