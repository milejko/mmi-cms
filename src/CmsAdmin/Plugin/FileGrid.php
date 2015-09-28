<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid plików
 */
class FileGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		//źródło danych
		$this->setQuery(\Cms\Orm\File\Query::factory());

		//miniatura (lub ikona)
		$this->addColumn('custom', 'thumb', [
			'label' => 'miniatura',
			'seekable' => false,
			'sortable' => false,
			'value' => '{if ($rowData->class ==\'image\')}<img src="{thumb($rowData, \'scaley\', \'30\')}" />{else}' .
			'{$mime = \'%mimeType%\'}' .
			'{if $mime == \'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/xlsx-32.png" alt="Microsoft Office - OOXML - Spreadsheet" />' .
			'{elseif $mime == \'application/vnd.ms-excel\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/xls-32.png" alt="Microsoft Excel Sheet File" />' .
			'{elseif $mime == \'application/vnd.openxmlformats-officedocument.wordprocessingml.document\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/docx-32.png" alt="Microsoft Office - OOXML - Document" />' .
			'{elseif $mime == \'application/msword\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/doc-32.png" alt="Microsoft Word Document" />' .
			'{elseif $mime == \'application/vnd.openxmlformats-officedocument.presentationml.presentation\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/pptx-32.png" alt="Microsoft Office - OOXML - Presentation" />' .
			'{elseif $mime == \'application/vnd.ms-powerpoint\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/ppt-32.png" alt="Microsoft PowerPoint Presentation" />' .
			'{elseif $mime == \'text/csv\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/csv-32.png" alt="Comma-Seperated Values" />' .
			'{elseif $mime == \'application/pdf\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/pdf-32.png" alt="Adobe Portable Document Format" />' .
			'{elseif $mime == \'application/rtf\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/rtf-32.png" alt="Rich Text Format" />' .
			'{elseif $mime == \'application/zip\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/zip-32.png" alt="Zip Archive" />' .
			'{elseif $mime == \'application/xml\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/xml-32.png" alt="XML - Extensible Markup Language" />' .
			'{elseif $mime == \'text/plain\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/txt-32.png" alt="Text File" />' .
			'{elseif $mime == \'audio/mpeg\'}' .
			'	<img src="{$baseUrl}/resource/cmsAdmin/images/types/mp3-32.png" alt="Music File" />' .
			'{/if}' .
			'{/if}'
		]);

		//rozmiar pliku
		$this->addColumn('text', 'size', [
			'label' => 'rozmiar',
		]);

		//nazwa pliku
		$this->addColumn('text', 'original', [
			'label' => 'nazwa pliku',
		]);

		//tytuł
		$this->addColumn('text', 'title', [
			'label' => 'tytuł'
		]);

		//autor
		$this->addColumn('text', 'author', [
			'label' => 'autor'
		]);

		//źródło
		$this->addColumn('text', 'source', [
			'label' => 'źródło'
		]);

		//zasób
		$this->addColumn('text', 'object', [
			'label' => 'zasób'
		]);

		//id zasobu
		$this->addColumn('text', 'objectId', [
			'label' => 'id zasobu'
		]);

		//checkbox aktywności
		$this->addColumn('checkbox', 'active', [
			'label' => 'widoczny'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
			'links' => [
				'edit' => null
			]
		]);
	}

}
