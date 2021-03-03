<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Mvc\Controller;
use Ifsnop\Mysqldump\Mysqldump;
use App\Registry;

/**
 * Kontroler operacji na DB
 */
class DbController extends Controller
{
    const PATH      = BASE_PATH . '/var/data/dump.sql';
    const OPTIONS   = [
        'compress' => Mysqldump::GZIP,
    ];

    /**
     * Database dump
     */
    public function dumpAction()
    {
        ob_end_clean();
        //new dumper object
        $dumper = new Mysqldump(
            'mysql:host=' . Registry::$config->db->host . ';dbname=' . Registry::$config->db->name . ';dbport=' . Registry::$config->db->port,
            Registry::$config->db->user,
            Registry::$config->db->password,
            self::OPTIONS
        );
        //write file
        $dumper->start(self::PATH);
        //headers
        $this->getResponse()
            ->setHeader('Content-Disposition', 'attachment; filename=' . Registry::$config->db->name . date('-Y-m-d-Hi') . '.sql.gz')
            ->setHeader('Content-Type', 'application/octet-stream')
            ->setHeader('Content-Transfer-Encoding', 'binary')
            ->sendHeaders();
        //readfile + unlink
        readfile(self::PATH);
        unlink(self::PATH);
        exit;
    }
}
