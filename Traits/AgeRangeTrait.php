<?php
/**
 * @noinspection MethodShouldBeFinalInspection
 */

namespace OswisOrg\OswisAddressBookBundle\Traits;

use DateTime;
use Exception;
use OswisOrg\OswisCoreBundle\Utils\AgeUtils;

/**
 * Trait adds createdDateTime and updatedDateTime fields.
 *
 * Trait adds fields *createdDateTime* and *updatedDateTime* and allows to access them.
 * * _**createdDateTime**_ contains date and time when entity was created
 * * _**updatedDateTime**_ contains date and time when entity was updated/changed
 */
trait AgeRangeTrait
{
    /**
     * Minimal age of person in this group.
     *
     * @Doctrine\ORM\Mapping\Column(type="smallint", nullable=true)
     */
    protected ?int $minAge = null;

    /**
     * Maximal age of person in this group.
     *
     * @Doctrine\ORM\Mapping\Column(type="smallint", nullable=true)
     */
    protected ?int $maxAge = null;

    /**
     * True if person belongs to this age range (at some moment - referenceDateTime).
     *
     * @param  DateTime  $birthDate  BirthDate for age calculation
     * @param  DateTime|null  $referenceDateTime  Reference date, default is _now_
     *
     * @return bool True if belongs to age range
     * @throws Exception
     */
    public function containsBirthDate(DateTime $birthDate, DateTime $referenceDateTime = null): bool
    {
        return AgeUtils::isBirthDateInRange($birthDate, $this->minAge, $this->maxAge, $referenceDateTime);
    }

    public function agesDiff(): int
    {
        return ($this->getMaxAge() && $this->getMinAge()) ? $this->getMaxAge() - $this->getMinAge() : 0;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(?int $maxAge): void
    {
        $this->maxAge = $maxAge;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(?int $minAge): void
    {
        $this->minAge = $minAge;
    }
}
