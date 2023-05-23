<?php
namespace Mandatly\CookieCompliance\Model\ResourceModel\DbMccConfig;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'config_id';
    protected $_eventPrefix = 'mdt_config_collection';
    protected $_eventObject = 'mccconfig_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mandatly\CookieCompliance\Model\DbMccConfig', 'Mandatly\CookieCompliance\Model\Model\ResourceModel\DbMccConfig');
    }
}
