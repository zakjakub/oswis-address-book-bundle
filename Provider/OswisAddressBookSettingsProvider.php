<?php
/**
 * @noinspection MethodShouldBeFinalInspection
 */

namespace OswisOrg\OswisAddressBookBundle\Provider;

/**
 * Provider of settings for address book module of OSWIS.
 */
class OswisAddressBookSettingsProvider
{
    protected ?array $primary = null;

    public function __construct(?array $primary)
    {
        $this->primary = $primary;
    }

    public function getOrganization(): ?string
    {
        return $this->getPrimary() ? $this->getPrimary()['organization'] ?? null : null;
    }

    public function getPrimary(): ?array
    {
        return $this->primary;
    }
}
