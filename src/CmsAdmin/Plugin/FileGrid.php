<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid plików
 */
class FileGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//źródło danych
		$this->setQuery(new \Cms\Orm\CmsFileQuery);

		//miniatura (lub ikona)
		$this->addColumnCustom('thumb')
			->setLabel('miniatura')
			->setTemplateCode('{if ($record->class ==\'image\')}<img src="{thumb($record, \'scaley\', \'30\')}" />{else}' .
				'{$mime = $record->mimeType}' .
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
		);

		//rozmiar pliku
		$this->addColumnText('size')
			->setLabel('rozmiar');

		//nazwa pliku
		$this->addColumnText('original')
			->setLabel('nazwa pliku');

		//tytuł
		$this->addColumnText('title')
			->setLabel('tytuł');

		//autor
		$this->addColumnText('author')
			->setLabel('autor');

		//źródło
		$this->addColumnText('source')
			->setLabel('źródło');

		//zasób
		$this->addColumnText('object')
			->setLabel('zasób');

		//id zasobu
		$this->addColumnText('objectId')
			->setLabel('id zasobu');

		//checkbox aktywności
		$this->addColumnCheckbox('active')
			->setLabel('widoczny');

		$this->addColumnCustom('download')
			->setLabel('pobierz')
			->setTemplateCode('<a class="button small" href="{$record->getUrl()}">pobierz</a>');

		//operacje
		$this->addColumnOperation()
			->setEditParams([]);
	}

}
