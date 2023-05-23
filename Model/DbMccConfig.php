<?php

namespace Mandatly\CookieCompliance\Model;

class DbMccConfig extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'mdt_config';

    protected $_cacheTag = 'mdt_config';

    protected $_eventPrefix = 'mdt_config';

    protected function _construct()
    {
        $this->_init('Mandatly\CookieCompliance\Model\ResourceModel\DbMccConfig');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
