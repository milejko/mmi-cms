<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use CmsAdmin\Grid\Column;

/**
 * Grid plików
 */
class FileGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //źródło danych
        $this->setQuery(new \Cms\Orm\CmsFileQuery);

        //miniatura (lub ikona)
        $this->addColumn((new Column\CustomColumn('thumb'))
            ->setLabel('miniatura')
            ->setTemplateCode('{if ($record->class ==\'image\')}<img src="{thumb($record, \'scaley\', \'30\')}" />{else}' .
                '{$mime = $record->mimeType}' .
                '{if $mime == \'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet\'}' .
                '	<img src="/resource/cmsAdmin/images/types/xlsx-32.png" alt="Microsoft Office - OOXML - Spreadsheet" />' .
                '{elseif $mime == \'application/vnd.ms-excel\'}' .
                '	<img src="/resource/cmsAdmin/images/types/xls-32.png" alt="Microsoft Excel Sheet File" />' .
                '{elseif $mime == \'application/vnd.openxmlformats-officedocument.wordprocessingml.document\'}' .
                '	<img src="/resource/cmsAdmin/images/types/docx-32.png" alt="Microsoft Office - OOXML - Document" />' .
                '{elseif $mime == \'application/msword\'}' .
                '	<img src="/resource/cmsAdmin/images/types/doc-32.png" alt="Microsoft Word Document" />' .
                '{elseif $mime == \'application/vnd.openxmlformats-officedocument.presentationml.presentation\'}' .
                '	<img src="/resource/cmsAdmin/images/types/pptx-32.png" alt="Microsoft Office - OOXML - Presentation" />' .
                '{elseif $mime == \'application/vnd.ms-powerpoint\'}' .
                '	<img src="/resource/cmsAdmin/images/types/ppt-32.png" alt="Microsoft PowerPoint Presentation" />' .
                '{elseif $mime == \'text/csv\'}' .
                '	<img src="/resource/cmsAdmin/images/types/csv-32.png" alt="Comma-Seperated Values" />' .
                '{elseif $mime == \'application/pdf\'}' .
                '	<img src="/resource/cmsAdmin/images/types/pdf-32.png" alt="Adobe Portable Document Format" />' .
                '{elseif $mime == \'application/rtf\'}' .
                '	<img src="/resource/cmsAdmin/images/types/rtf-32.png" alt="Rich Text Format" />' .
                '{elseif $mime == \'application/zip\'}' .
                '	<img src="/resource/cmsAdmin/images/types/zip-32.png" alt="Zip Archive" />' .
                '{elseif $mime == \'application/xml\'}' .
                '	<img src="/resource/cmsAdmin/images/types/xml-32.png" alt="XML - Extensible Markup Language" />' .
                '{elseif $mime == \'text/plain\'}' .
                '	<img src="/resource/cmsAdmin/images/types/txt-32.png" alt="Text File" />' .
                '{elseif $mime == \'audio/mpeg\'}' .
                '	<img src="/resource/cmsAdmin/images/types/mp3-32.png" alt="Music File" />' .
                '{/if}' .
                '{/if}'
        ));

        //rozmiar pliku
        $this->addColumn((new Column\TextColumn('size'))
            ->setLabel('grid.file.size.label'));

        //nazwa pliku
        $this->addColumn((new Column\TextColumn('original'))
            ->setLabel('grid.file.original.label'));

        //zasób
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('grid.file.object.label'));

        //id zasobu
        $this->addColumn((new Column\TextColumn('objectId'))
            ->setLabel('grid.file.objectId.label'));

        //checkbox aktywności
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.file.active.label')
            ->setDisabled());

        $this->addColumn((new Column\CustomColumn('download'))
            ->setLabel('<i class="fa fa-2 fa-download"></i>')
            ->setTemplateCode('<a class="button small" href="{$record->getUrl()}"><i class="fa fa-2 fa-download"></i></a>'));

        //operacje
        $this->addColumn((new Column\OperationColumn())->setEditParams([]));
    }

}
