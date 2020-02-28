<?php
/**
 * @noinspection PhpUnused
 * @noinspection MethodShouldBeFinalInspection
 */

namespace Zakjakub\OswisAddressBookBundle\Entity;

use Zakjakub\OswisCoreBundle\Entity\Nameable;
use Zakjakub\OswisCoreBundle\Interfaces\BasicEntityInterface;
use Zakjakub\OswisCoreBundle\Traits\Entity\BasicEntityTrait;
use Zakjakub\OswisCoreBundle\Traits\Entity\NameableBasicTrait;
use Zakjakub\OswisCoreBundle\Traits\Entity\PriorityTrait;
use function filter_var;
use function htmlspecialchars;

/**
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="address_book_contact_detail")
 * @Doctrine\ORM\Mapping\Cache(usage="NONSTRICT_READ_WRITE", region="address_book_contact")
 */
class ContactDetail implements BasicEntityInterface
{
    use BasicEntityTrait;
    use PriorityTrait;
    use NameableBasicTrait;

    /**
     * Type of this contact.
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Zakjakub\OswisAddressBookBundle\Entity\ContactDetailType", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private ?ContactDetailType $contactType = null;

    /**
     * Text content of note.
     * @Doctrine\ORM\Mapping\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    public function __construct(?ContactDetailType $contactType = null, ?string $content = null, ?Nameable $nameable = null)
    {
        $this->setContactType($contactType);
        $this->setContent($content);
        $this->setFieldsFromNameable($nameable);
    }

    public function getFormatted(): ?string
    {
        if (null !== $this->getContactType()) {
            return $this->getContactType()->getFormatted(
                filter_var($this->getContent(), FILTER_SANITIZE_URL).'',
                htmlspecialchars($this->getDescription()).null,
                htmlspecialchars($this->getName()).null,
                );
        }

        return $this->getContent();
    }

    public function getContactType(): ?ContactDetailType
    {
        return $this->contactType;
    }

    public function setContactType(?ContactDetailType $contactType): void
    {
        $this->contactType = $contactType;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getSchemaString(): ?string
    {
        return $this->contactType ? $this->contactType->getContactSchema() : null;
    }

    public function getTypeString(): ?string
    {
        return $this->contactType ? $this->contactType->getName() : null;
    }
}
