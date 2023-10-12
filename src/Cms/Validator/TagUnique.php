<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Validator;

use Cms\Orm\CmsTagQuery;
use Mmi\Form\Form;
use Mmi\Validator\ValidatorAbstract;
use Mmi\Validator\ValidatorException;

/**
 * Walidator unikalności tagu
 *
 * @method self setCurrentForm(Form $scope) ustawia bieżący form
 * @method self setMessage($message) ustawia własną wiadomość walidatora
 *
 * @method Form getCurrentForm() pobiera bieżący form
 * @method string getMessage() pobiera własną wiadomość walidatora
 */
class TagUnique extends ValidatorAbstract
{
    /**
     * Komunikat istnienia pola
     */
    public const INVALID = 'validator.recordUnique.message';

    /**
     * Ustawia opcje
     * @param array $options
     * @param bool $reset
     * @return self
     */
    public function setOptions(array $options = [], $reset = false)
    {
        return $this
            ->setCurrentForm(current($options))
            ->setMessage(next($options));
    }

    /**
     * Walidacja unikalności rekordu z użyciem Query
     * @param mixed $value wartość
     * @return bool
     * @throws ValidatorException
     */
    public function isValid($value): bool
    {
        //brak pola scope
        if (!$this->getCurrentForm() instanceof Form) {
            throw new ValidatorException('No current form object provided.');
        }
        $tagQuery = CmsTagQuery::byName($value, $this->getCurrentForm()->getElement('lang')->getValue(), $this->getCurrentForm()->getCurrentScope());
        $id = $this->getCurrentForm()->getRecord()->id;
        if (null !== $id) {
            $tagQuery->andFieldId()->notEquals($id);
        }
        //rekord istnieje
        if ($tagQuery->count() > 0) {
            return $this->_error(static::INVALID);
        }
        return true;
    }
}
