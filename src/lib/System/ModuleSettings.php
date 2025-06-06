<?php

namespace Vendor\Skeleton\System;

use Bitrix\Main\ArgumentNullException;
use Vasoft\Core\Settings\Exceptions\RequiredOptionException;
use Vasoft\Core\Settings\Normalizers\Normalizer;

class ModuleSettings extends \Vasoft\Core\Settings\ModuleSettings
{
    public const MODULE_ID = 'vendor.skeleton';

    public const PROP_EXAMPLE = 'EXAMPLE';

    /**
     * @return string
     * @throws RequiredOptionException
     */
    public function getExample(): string
    {
        if (!array_key_exists(self::PROP_EXAMPLE, $this->options)) {
            if ($this->sendThrow) {
                throw new RequiredOptionException(self::PROP_EXAMPLE, 'Example prop');
            }
            return '';
        }
        return (int)($this->options[self::PROP_EXAMPLE] ?? '1');
    }

    /**
     * @param bool $sendThrow
     * @return static
     * @throws ArgumentNullException
     */
    public static function getInstance(bool $sendThrow = true): static
    {
        return self::initInstance(self::MODULE_ID, $sendThrow);
    }

    protected function initNormalizers(): void
    {
        $this->normalizer = [
            self::PROP_EXAMPLE => [Normalizer::class, 'normalizeString'],
        ];
    }
}