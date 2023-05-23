<?php
namespace Mandatly\CookieCompliance\Model\ResourceModel;

class DbMccConfig extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('mdt_config', 'config_id');
    }
}
