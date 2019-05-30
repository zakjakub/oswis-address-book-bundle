<?php

namespace Zakjakub\OswisAddressBookBundle\Entity\AddressBook;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zakjakub\OswisAddressBookBundle\Entity\AbstractClass\AbstractContact;
use Zakjakub\OswisCoreBundle\Entity\AbstractClass\AbstractRevision;
use Zakjakub\OswisCoreBundle\Entity\AbstractClass\AbstractRevisionContainer;
use Zakjakub\OswisCoreBundle\Entity\Nameable;
use Zakjakub\OswisCoreBundle\Exceptions\RevisionMissingException;
use Zakjakub\OswisCoreBundle\Traits\Entity\BasicEntityTrait;
use Zakjakub\OswisCoreBundle\Traits\Entity\NameableBasicContainerTrait;
use function assert;

/**
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="address_book_address_book")
 */
class AddressBook extends AbstractRevisionContainer
{
    use BasicEntityTrait;
    use NameableBasicContainerTrait;

    /**
     * @var Collection
     * @Doctrine\ORM\Mapping\OneToMany(
     *     targetEntity="Zakjakub\OswisAddressBookBundle\Entity\AddressBook\AddressBookRevision",
     *     mappedBy="container",
     *     cascade={"all"},
     *     fetch="EAGER"
     * )
     */
    protected $revisions;

    /**
     * @var Collection|null
     * @Doctrine\ORM\Mapping\OneToMany(
     *     targetEntity="Zakjakub\OswisAddressBookBundle\Entity\AddressBook\AddressBookContactConnection",
     *     cascade={"all"},
     *     mappedBy="addressBook",
     *     fetch="EAGER"
     * )
     */
    protected $addressBookContactConnections;

    public function __construct(
        ?Nameable $nameable = null
    ) {
        $this->addressBookContactConnections = new ArrayCollection();
        $this->revisions = new ArrayCollection();
        $this->addRevision(new AddressBookRevision($nameable));
    }

    /**
     * @return string
     */
    public static function getRevisionClassName(): string
    {
        return AddressBookRevision::class;
    }

    /**
     * @param AbstractRevision|null $revision
     */
    public static function checkRevision(?AbstractRevision $revision): void
    {
        assert($revision instanceof AddressBookRevision);
    }

    /**
     * @param DateTime|null $dateTime
     *
     * @return AddressBookRevision
     * @throws RevisionMissingException
     */
    final public function getRevisionByDate(?DateTime $dateTime = null): AddressBookRevision
    {
        $revision = $this->getRevision($dateTime);
        assert($revision instanceof AddressBookRevision);

        return $revision;
    }

    final public function addContact(AbstractContact $contact): void
    {
        if (!$this->containsContact($contact)) {
            $this->addAddressBookContactConnection(new AddressBookContactConnection(null, $contact));
        }
    }

    final public function containsContact(AbstractContact $contact): bool
    {
        return $this->getContacts()->contains($contact);
    }

    final public function getContacts(): Collection
    {
        return $this->getAddressBookContactConnections()->map(
            static function (AddressBookContactConnection $addressBookContactConnection) {
                return $addressBookContactConnection->getContact();
            }
        );
    }

    final public function getAddressBookContactConnections(): Collection
    {
        return $this->addressBookContactConnections ?? new ArrayCollection();
    }

    final public function setAddressBookContactConnections(?Collection $newAddressBookContactConnections): void
    {
        if (!$this->addressBookContactConnections) {
            $this->addressBookContactConnections = new ArrayCollection();
        }
        if (!$newAddressBookContactConnections) {
            $newAddressBookContactConnections = new ArrayCollection();
        }
        foreach ($this->addressBookContactConnections as $oldAddressBookContactConnection) {
            if (!$newAddressBookContactConnections->contains($oldAddressBookContactConnection)) {
                $this->removeAddressBookContactConnection($oldAddressBookContactConnection);
            }
        }
        if ($newAddressBookContactConnections) {
            foreach ($newAddressBookContactConnections as $newAddressBookContactConnection) {
                if (!$this->addressBookContactConnections->contains($newAddressBookContactConnection)) {
                    $this->addAddressBookContactConnection($newAddressBookContactConnection);
                }
            }
        }
    }

    final public function addAddressBookContactConnection(?AddressBookContactConnection $addressBookContactConnection): void
    {
        if ($addressBookContactConnection && !$this->addressBookContactConnections->contains($addressBookContactConnection)) {
            $this->addressBookContactConnections->add($addressBookContactConnection);
            $addressBookContactConnection->setAddressBook($this);
        }
    }

    final public function removeAddressBookContactConnection(?AddressBookContactConnection $addressBookContactConnection): void
    {
        if (!$addressBookContactConnection) {
            return;
        }
        if ($this->addressBookContactConnections->removeElement($addressBookContactConnection)) {
            $addressBookContactConnection->setAddressBook(null);
        }
    }

    final public function removeContact(AbstractContact $contact): void
    {
        foreach ($this->getAddressBookContactConnections() as $addressBookContactConnection) {
            assert($addressBookContactConnection instanceof AddressBookContactConnection);
            if ($contact->getId() === $addressBookContactConnection->getId()) {
                $this->removeAddressBookContactConnection($addressBookContactConnection);
            }
        }
    }


}