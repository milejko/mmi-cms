<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Mmi\Mvc\Controller;
use Mmi\Session\SessionSpace;

/**
 * Kontroler captcha
 */
class CaptchaController extends Controller
{

    /**
     * Akcja generująca obrazek captcha
     * @return binary
     */
    public function indexAction()
    {
        if (!$this->name) {
            return '';
        }
        //lista dozwolonych znaków
        $characters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'W', 'Z'];
        $word = '';
        $charsCount = count($characters) - 1;

        //generuje 5 literowy string
        for ($i = 0; $i < 5; $i++) {
            $word .= $characters[rand(0, $charsCount)];
        }

        //tworzenie obrazu
        $img = imagecreatetruecolor(130, 50);

        //konfiguracja kolorów
        $green = imagecolorallocate($img, 0x00, 0x77, 0x00);
        $gray = imagecolorallocate($img, 0xF5, 0xF5, 0xF5);
        $darkGray = imagecolorallocate($img, 0x99, 0x99, 0x99);

        //czcionka
        $font = BASE_PATH . '/web/resource/cmsAdmin/fonts/dejavu.ttf';

        //ramka
        imagefilledrectangle($img, 0, 0, 129, 49, $darkGray);
        imagefilledrectangle($img, 1, 1, 128, 48, $gray);

        $prevSpace = -5;
        $length = strlen($word);

        //przesunięcia i obroty
        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 !== 0) {
                $size = rand(32, 36);
                $height = rand(42, 46);
                $angle = rand(5, 15);
                $space = 25 + $prevSpace;
            } else {
                $size = rand(28, 32);
                $height = rand(39, 43);
                $angle = rand(-15, 5);
                $space = 13 + $prevSpace;
            }
            $prevSpace = $space;
            imagefttext($img, $size, $angle, $space, $height, $green, $font, $word[$i]);
        }

        //zapis do sesji
        $formSession = new SessionSpace('captcha');
        //filtracja nazwy pola z nazwy formularza + pola
        $name = strpos($this->name, '[') === false ? $this->name : substr($this->name, strpos($this->name, '[') + 1, -1);
        $formSession->$name = $word;

        //ustalenie nagłówka na niebuforowany
        $this->getResponse()
            ->setHeader('Expires', 'Mon, 15 Dec ' . (date('Y') - 1) . ' 01:00:00 GMT+0100')
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->setHeader('Cache-Control', 'post-check=0, pre-check=0', false)
            ->setHeader('Pragma', 'no-cache')
            ->setTypeJpeg();

        //zwrot obrazu
        imagejpeg($img, null, 25);
        return '';
    }
}
