<?php
namespace Mandatly\CookieCompliance\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Filesystem\DirectoryList;

class MandatlyInfo implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var DirectoryList
     */
    private $directory;

    /**
     * MandatlyInfo constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        DirectoryList $directory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->jsonHelper = $jsonHelper;
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;

        $tableName = $this->moduleDataSetup->getTable('mdt_config');
        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $json = file_get_contents($this->directory->getPath('app') . "/code/Mandatly/CookieCompliance/plugin-settings.json");
            $jsonData = $this->jsonHelper->jsonDecode($json);
            $baseUrl = $jsonData['baseURL'];
            $liveUrl = $baseUrl. $jsonData['bannerFolder'];
            $demoFile = file_get_contents($baseUrl. $jsonData['demoSettingFileName']);
            $demoData = $this->jsonHelper->jsonDecode($demoFile);
            $demoUrl = $demoData['baseURL'] . $demoData['demoBannerPath'];

            $data = [
                [
                    'mcc_config_key' => 'mdt_cookie_demo_status',
                    'mcc_config_value' => 'true',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'mcc_config_key' => 'mdt_cookie_banner_guid',
                    'mcc_config_value' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'mcc_config_key' => 'mdt_cookie_demo_url',
                    'mcc_config_value' => $demoUrl,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'mcc_config_key' => 'mdt_cookie_live_url',
                    'mcc_config_value' => $liveUrl,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'mcc_config_key' => 'mdt_cookie_banner_status',
                    'mcc_config_value' => 'true',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ];
            foreach ($data as $item) {
                $setup->getConnection()->insert($tableName, $item);
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
