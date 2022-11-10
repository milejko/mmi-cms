<?php

namespace Cms\Orm;

/**
 * Rekord podglądu kategorii CMSowych
 */
class CmsCategoryPreviewRecord extends CmsCategoryRecord
{
    private ?CmsCategoryRecord $originalCmsCategoryRecord = null;

    public function setFromCmsCategoryRecord(CmsCategoryRecord $cmsCategoryRecord): self
    {
        $this->setFromArray($cmsCategoryRecord->toArray());
        if ($this->cmsCategoryOriginalId) {
            $this->originalCmsCategoryRecord = (new CmsCategoryQuery())->findPk($this->cmsCategoryOriginalId);
        }
        return $this;
    }

    public function getChildrenRecords()
    {
        if (null === $this->originalCmsCategoryRecord) {
            return parent::getChildrenRecords();
        }
        return $this->originalCmsCategoryRecord->getChildrenRecords();
    }

    public function getSiblingsRecords()
    {
        if (null === $this->originalCmsCategoryRecord) {
            return parent::getSiblingsRecords();
        }
        return $this->originalCmsCategoryRecord->getChildrenRecords();
    }
}
