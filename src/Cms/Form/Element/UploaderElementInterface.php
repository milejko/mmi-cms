<?php

namespace Cms\Form\Element;

interface UploaderElementInterface
{
    //przedrostek tymczasowego obiektu plików
    public const TEMP_OBJECT_PREFIX = 'tmp-';
    public const PLACEHOLDER_NAME = '.placeholder';
    public const FILES_MOVED_OPTION_PREFIX = 'move-files-handled-';
    public const UPLOADER_ID_KEY = 'uploaderId';
}
