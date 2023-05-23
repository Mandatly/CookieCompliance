<?php

namespace Mandatly\CookieCompliance\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Serialize\Serializer\Json;

class Data extends AbstractHelper
{
    /**
     * @var ResourceConnection
     */
    public $resource;

    /**
     * @var Json
     */
    protected $_json;


    /**
     * Data constructor.
     * @param ResourceConnection $resource
     * @param Json $json
     * @param Context $context
     */
    public function __construct(
        ResourceConnection $resource,
        Json $json,
        Context $context
    ) {
        $this->resource = $resource;
        $this->_json = $json;
        parent::__construct($context);
    }

    /**
     * @param $value
     */
    public function updateMccbannerguid($value)
    {
        $data = ["mcc_config_value" => $value];
        $where = ['mcc_config_key = ?' => (string)'mdt_cookie_banner_guid'];
        $this->resource->getConnection()->update(
            $this->resource->getTableName('mdt_config'),
            $data,
            $where
        );
    }

    /**
     * @param $value
     */
    public function updateMccbannerstatus($value)
    {
        $data = ["mcc_config_value" => $value];
        $where = ['mcc_config_key = ?' => (string)'mdt_cookie_banner_status'];
        $this->resource->getConnection()->update(
            $this->resource->getTableName('mdt_config'),
            $data,
            $where
        );
    }

    /**
     * @param $value
     */
    public function updateMccComplianceOption($value)
    {
        $data = ["mcc_config_value" => $value];
        $where = ['mcc_config_key = ?' => (string)'mdt_cookie_demo_status'];
        $this->resource->getConnection()->update(
            $this->resource->getTableName('mdt_config'),
            $data,
            $where
        );

        if ($value == 'true') {
            $demo = 'true';
        } else {
            $demo = 'false';
        }
        $data = ["mcc_config_value" => $demo];
        $where = ['mcc_config_key = ?' => (string)'mcc_demo_status'];
        $this->resource->getConnection()->update(
            $this->resource->getTableName('mdt_config'),
            $data,
            $where
        );
    }

    /**
     * @return false|string
     */
    public function getMccComplianceOption()
    {
        $tableName = $this->resource->getTableName('mdt_config');
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from(
                ['c' => $tableName],
                ['mcc_config_value']
            )
            ->where(
                "`mcc_config_key` = 'mdt_cookie_demo_status'"
            );

        $result = $connection->fetchOne($select);
        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function getMccData($key)
    {
        $tableName = $this->resource->getTableName('mdt_config');
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from(
                ['c' => $tableName],
                ['mcc_config_value']
            )
            ->where(
                "`mcc_config_key` = '".$key."'"
            );

        $result = $connection->fetchOne($select);
        if ($result) {
            return $result;
        }

        return false;
    }
    public function insertscript()
    {
        $banner_status = $this->getMccData('mdt_cookie_banner_status');
        $demo_status = $this->getMccData('mdt_cookie_demo_status');
        $demoUrl = $this->getMccData('mdt_cookie_demo_url');
        $liveUrl = $this->getMccData('mdt_cookie_live_url');
        $guid = $this->getMccData('mdt_cookie_banner_guid');
        //error_log("\n  test data", 3, "/var/www/html/magento2/app/code/Mandatly/CookieCompliance/log/Mplugin.log");
        //error_log("\n ".$banner_status.'|'.$demo_status.'|'.$liveUrl.'|'.$demoUrl.'|'.$guid, 3, "/home/feren/log/Mplugin.log");

        if ('true' == $banner_status) {
            if ('true' == $demo_status) {
                if ('' != $demoUrl) { ?>
                    <script id="mandatlycookie" src="<?= $demoUrl; ?>" src_type="head"></script>
                <?php }
            } else {
                if ('' != $liveUrl && '' !=$guid) {
                    $live_guid_url = $liveUrl . $guid . '.js';
                    ?>

                    <script id="mandatlycookie" src="<?= $live_guid_url; ?>" src_type="head"></script>
                <?php }
            }
        }
    }
}
