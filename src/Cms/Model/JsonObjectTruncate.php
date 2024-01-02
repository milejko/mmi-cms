<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Mmi\Validator\ValidatorException;

class JsonObjectTruncate
{
    private const DEFAULT_LIMIT = 160;
    private array $jsonArray = [];

    public function setInputFromJsonString(string $inputJson): self
    {
        $inputJsonArray = \json_decode($inputJson, true);
        if (null === $inputJsonArray) {
            throw new ValidatorException('Invalid JSON');
        }
        return $this->setInputFromJsonArray($inputJsonArray);
    }

    public function setInputFromJsonArray(array $inputJsonArray): self
    {
        $this->jsonArray = $inputJsonArray;
        return $this;
    }

    public function getAsJson(int $limit = self::DEFAULT_LIMIT): string
    {
        return \json_encode($this->getAsArray($limit));
    }

    public function getAsArray(int $limit = self::DEFAULT_LIMIT): array
    {
        return $this->truncate($this->jsonArray, $limit);
    }

    private function truncate(array $data, int $limit): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->truncate($data[$key], $limit);
                continue;
            }
            if (!is_string($value)) {
                continue;
            }
            $data[$key] = mb_substr($this->filterOutHTML($value), 0, $limit);
        }
        return $data;
    }

    private function filterOutHTML(string $input): string
    {
        return trim(preg_replace('/\s+/', ' ', preg_replace('/[^ -~]+/', '', preg_replace('/[\t\n\r]+/i', ' ', strip_tags($input)))));
    }
}
