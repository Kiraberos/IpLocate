<?php

namespace Perspective\IpLocate\Model\Source;

use Magento\Shipping\Model\Carrier\Source\GenericInterface;

/**
 * Lang source
 */
class Lang implements GenericInterface
{

    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array<mixed>
     */
    public function toOptionArray(): array
    {
        $options = [
            ['value' => 'ru', 'label' => 'Русский'],
            ['value' => 'ua', 'label' => 'Украинский'],
        ];
        return $options;
    }
}
