<?php

namespace Zakjakub\OswisAddressBookBundle\Controller\MediaObject;

use Zakjakub\OswisAddressBookBundle\Entity\MediaObject\ContactImage;
use Zakjakub\OswisCoreBundle\Controller\AbstractClass\AbstractImageAction;
use Zakjakub\OswisCoreBundle\Entity\AbstractClass\AbstractImage;

final class ContactImageAction extends AbstractImageAction
{
    public static function getFileClassName(): string
    {
        return ContactImage::class;
    }

    public static function getFileNewInstance(): AbstractImage
    {
        return new ContactImage();
    }
}
