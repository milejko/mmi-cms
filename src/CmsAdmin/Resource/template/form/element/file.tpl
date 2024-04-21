{$value = $_element->getValue()}
{$file = $_element->getUploadedFile($value)}

<div>
    <div class="pull-left mr-2">
        <img style="width: 90px; height: 90px; padding: 21px; background: #eee; border-radius: 5px;" id="{$_element->getId()}_img"
        {if $file}
            {if 'image' == $file->class}
                src="{thumb($file, 'scalecrop', '48x48')}"
            {elseif $file->mimeType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}
                src="/resource/cmsAdmin/images/types/xlsx-48.png" alt="Microsoft Office - OOXML - Spreadsheet"
            {elseif $file->mimeType == 'application/vnd.ms-excel'}
                src="/resource/cmsAdmin/images/types/xls-48.png" alt="Microsoft Excel Sheet File"
            {elseif $file->mimeType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'}
                src="/resource/cmsAdmin/images/types/docx-48.png" alt="Microsoft Office - OOXML - Document"
            {elseif $file->mimeType == 'application/msword'}
                src="/resource/cmsAdmin/images/types/doc-48.png" alt="Microsoft Word Document"
            {elseif $file->mimeType == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'}
                src="/resource/cmsAdmin/images/types/pptx-48.png" alt="Microsoft Office - OOXML - Presentation"
            {elseif $file->mimeType == 'application/vnd.ms-powerpoint'}
                src="/resource/cmsAdmin/images/types/ppt-48.png" alt="Microsoft PowerPoint Presentation"
            {elseif $file->mimeType == 'text/csv'}
                src="/resource/cmsAdmin/images/types/csv-48.png" alt="Comma-Seperated Values"
            {elseif $file->mimeType == 'application/pdf'}
                src="/resource/cmsAdmin/images/types/pdf-48.png" alt="Adobe Portable Document Format"
            {elseif $file->mimeType == 'application/rtf'}
                src="/resource/cmsAdmin/images/types/rtf-48.png" alt="Rich Text Format"
            {elseif $file->mimeType == 'application/zip'}
                src="/resource/cmsAdmin/images/types/zip-48.png" alt="Zip Archive"
            {elseif $file->mimeType == 'application/xml'}
                src="/resource/cmsAdmin/images/types/xml-48.png" alt="XML - Extensible Markup Language"
            {elseif $file->mimeType == 'text/plain'}
                src="/resource/cmsAdmin/images/types/txt-48.png" alt="Text File"
            {elseif $file->mimeType == 'audio/mpeg'}
                src="/resource/cmsAdmin/images/types/mp3-48.png" alt="Music File"
            {elseif $file->mimeType == 'video/mp4'}
                src="/resource/cmsAdmin/images/types/mp4-48.png" alt="Video File"
            {else}
                src="/resource/cmsAdmin/images/upload/file.svg" alt="Unknown file"
            {/if}
        {/if}
        {if !$file}
            src="/resource/cmsAdmin/images/arrow.png"
        {/if}>
    </div>
    <div class="mb-2">
        <input accept="{$_element->getAcceptMimeType()}" type="file" onchange="
            document.getElementById('{$_element->getId()}_img').src = '/resource/cmsAdmin/images/arrow.png';
            if (undefined !== this.files[0]) {
                document.getElementById('{$_element->getId()}_name').value = '';
                document.getElementById('{$_element->getId()}_img').src = window.URL.createObjectURL(this.files[0]);
                document.getElementById('{$_element->getId()}_size').innerHTML =  this.files[0].name + ' (' + (this.files[0].size / 1048576).toFixed(2) + ' MB)';
                document.getElementById('{$_element->getId()}_checkbox').checked = false;
                document.getElementById('{$_element->getId()}-errors').innerHTML = '';
            }" {$_htmlOptions} />
        <input type="hidden" id="{$_element->getId()}_name" name="{$_element->getName()}" value="{$value|input}" />
    </div>
    <div>
        <label><span id="{$_element->getId()}_size">{if $file}{$file->original} ({php_round($file->size / 1048576, 2)} MB){else}({#form.element.image.missing.label#}){/if}</span></label><br/>
        <button onclick="
            document.getElementById('{$_element->getId()}').value = '';
            document.getElementById('{$_element->getId()}_name').value = '';
            document.getElementById('{$_element->getId()}_img').src = '/resource/cmsAdmin/images/arrow.png';
            document.getElementById('{$_element->getId()}_size').innerHTML = '(brak pliku)';
            return false;
        ">{#form.element.image.delete.label#}</button>
    </div>
    <div class="clearfix"></div>
</div>
