<?php

namespace Zakjakub\OswisAddressBookBundle\Form;

use Zakjakub\OswisAddressBookBundle\Entity\ContactImage;
use Zakjakub\OswisAddressBookBundle\Form\AbstractClass\AbstractImageType;

final class ContactImageType extends AbstractImageType
{

    /**
     * @return string
     */
    public static function getImageClassName(): string
    {
        return ContactImage::class;
    }

}
