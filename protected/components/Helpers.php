<?php
/**
 * Class Helpers, содержащий вспомогательные функции
 */
class Helpers {

    public function resizeImage($image, $savePath, $newFilename, $w, $h=null, $source=null){
        $downloaded = false;
        if ($source==null){
            $source = $image->tempName;
            $filename = $image->name;
            $downloaded = true;
            $target = Yii::getPathOfAlias('webroot').'/images/temp/'.$filename;
            if (!move_uploaded_file($source, $target)){
                return false;
            }//загрузка оригинала в папку $path_to_90_directory
        } else {
            $filename = explode('/', $source);
            $filename = $filename[count($filename)-1];
            $target = $source;
        }
        $type = 'jpg';
        if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
            $type = 'gif';
            $im = imagecreatefromgif($target) ; //если оригинал был в формате gif, то создаем изображение в этом же формате. Необходимо для последующего сжатия
        }
        if(preg_match('/[.](PNG)|(png)$/', $filename)) {
            $type = 'png';
            $im = imagecreatefrompng($target) ;//если оригинал был в формате png, то создаем изображение в этом же формате. Необходимо для последующего сжатия
        }

        if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
            $im = imagecreatefromjpeg($target); //если оригинал был в формате jpg, то создаем изображение в этом же формате. Необходимо для последующего сжатия
        }

        // Создание квадрата 90x90
        // dest - результирующее изображение
        // w - ширина изображения
        // ratio - коэффициент пропорциональности

        // создаём исходное изображение на основе
        // исходного файла и определяем его размеры
        $w_src = imagesx($im); //вычисляем ширину
        $h_src = imagesy($im); //вычисляем высоту изображения
        $ratio = $w_src/$h_src;
        if ($h==null){
            $h = (int)($w/$ratio);
        }
        if ($w>$w_src || $h>$h_src){
            unlink ($target);
            return false;
        }
        // создаём пустую квадратную картинку
        // важно именно truecolor!, иначе будем иметь 8-битный результат
        $dest = imagecreatetruecolor($w,$h);

        // вырезаем квадратную серединку по x, если фото горизонтальное
        if ($w/$h<$ratio)
            imagecopyresampled($dest, $im, 0, 0, (int)(($w_src-$w*$h_src/$h)/2), 0, $w, $h, (int)($w*$h_src/$h), $h_src);

        // вырезаем квадратную верхушку по y,
        // если фото вертикальное (хотя можно тоже серединку)
        if ($w/$h>$ratio)
            imagecopyresampled($dest, $im, 0, 0, 0, (int)(($h_src-$h*$w_src/$w)/2), $w, $h, $w_src, (int)($h*$w_src/$w));

        // квадратная картинка масштабируется без вырезок
        if ($w/$h==$ratio)
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $w_src, $h_src);

        switch ($type){
            case 'jpg':
                imagejpeg($dest, $savePath.$newFilename);//сохраняем изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.
                break;
            case 'png':
                imagepng($dest, $savePath.$newFilename);//сохраняем изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.
                break;
            case 'gif':
                imagegif($dest, $savePath.$newFilename);//сохраняем изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.
                break;
            default:
                imagejpeg($dest, $savePath.$newFilename);//сохраняем изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.
        }
        //почему именно jpg? Он занимает очень мало места + уничтожается анимирование gif изображения, которое отвлекает пользователя. Не очень приятно читать его комментарий, когда краем глаза замечаешь какое-то движение.
        if ($downloaded){
            unlink ($target);//удаляем оригинал загруженного изображения, он нам больше не нужен. Задачей было - получить миниатюру.
        }
        return true;
        }

    public function smtpMail($mail_to, $subject, $message, $headers='') {
        $config['smtp_username'] = 'fifa-challenge@mail.ru'; //Смените на имя своего почтового ящика.
        $config['smtp_port'] = '25'; // Порт работы. Не меняйте, если не уверены.
        $config['smtp_host'] = 'smtp.mail.ru'; //сервер для отправки почты
        $config['smtp_password'] = 'mailrrruuu33kms'; //пароль
        $config['smtp_charset'] = 'UTF-8'; //кодировка сообщений.
        $config['smtp_from'] = 'fifa-challenge'; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"
        $SEND =   "Date: ".date("D, d M Y H:i:s") . " UT\r\n";
        $SEND .=   'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
        if ($headers) $SEND .= $headers."\r\n\r\n";
        else
        {
            $SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
            $SEND .= "MIME-Version: 1.0\r\n";
            $SEND .= "Content-Type: text/plain; charset=\"".$config['smtp_charset']."\"\r\n";
            $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
            $SEND .= "From: \"".$config['smtp_from']."\" <".$config['smtp_username'].">\r\n";
            $SEND .= "To: $mail_to <$mail_to>\r\n";
            $SEND .= "X-Priority: 3\r\n\r\n";
        }
        $SEND .=  $message."\r\n";
        if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
            return false;
        }

        if (!self::serverParse($socket, "220", __LINE__)) return false;

        fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");
        if (!self::serverParse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "AUTH LOGIN\r\n");
        if (!self::serverParse($socket, "334", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
        if (!self::serverParse($socket, "334", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
        if (!self::serverParse($socket, "235", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
        if (!self::serverParse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");

        if (!self::serverParse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "DATA\r\n");

        if (!self::serverParse($socket, "354", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, $SEND."\r\n.\r\n");

        if (!self::serverParse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        return TRUE;
    }

    public function serverParse($socket, $response, $line = __LINE__) {
        while (substr($server_response, 3, 1) != ' ') {
            if (!($server_response = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($server_response, 0, 3) == $response)) {
            return false;
        }
        return true;
    }

    public function generateRandomKey($len) //запускаем функцию, генерирующую код
    {
        $hours = date("H"); // час
        $minuts = substr(date("H"), 0 , 1);// минута
        $mouns = date("m");    // месяц
        $year_day = date("z"); // день в году
        $str = $hours . $minuts . $mouns . $year_day; //создаем строку

        $str = md5(md5($str)); //дважды шифруем в md5
        $str = strrev($str);// реверс строки
        $str = substr($str, 3, $len); // извлекаем $len символов, начиная с 3
        // Вам конечно же можно постваить другие значения, так как, если взломщики узнают, каким именно способом это все генерируется, то в защите не будет смысла.

        $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        srand ((float)microtime()*1000000);
        shuffle ($array_mix);
        //Тщательно перемешиваем, соль, сахар по вкусу!!!
        return implode("", $array_mix);
    }
}