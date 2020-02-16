<?php
/**
 * @noinspection MethodShouldBeFinalInspection
 */

namespace Zakjakub\OswisAddressBookBundle\Form\MediaObject;

use Zakjakub\OswisAddressBookBundle\Entity\MediaObject\ContactImage;
use Zakjakub\OswisCoreBundle\Form\AbstractClass\AbstractImageType;

class ContactImageType extends AbstractImageType
{
    public static function getFileClassName(): string
    {
        return ContactImage::class;
    }

    public function getBlockPrefix(): string
    {
        return 'oswis_address_book_contact_image';
    }
}
