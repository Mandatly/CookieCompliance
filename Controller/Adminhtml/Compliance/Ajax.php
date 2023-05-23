<?php

namespace Mandatly\CookieCompliance\Controller\Adminhtml\Compliance;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Mandatly\CookieCompliance\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;
use Magento\Framework\Validator\Ip;
use Magento\Framework\Validator\Url as UrlValidator;

class Ajax extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curl;
    
    
     protected $logger;

    /**
     * Ajax constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\HTTP\Client\Curl $curl,
        Data $helper,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->curl = $curl;
        $this->logger = $logger;
    }

    /**
     * @return false|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
    
        $result = $this->resultJsonFactory->create();
        if ($this->getRequest()->isAjax()) {
            $action = $this->getRequest()->getParam('action');
            if ($this->getRequest()->getParam('slider_status') == 'true') {
                $status = 'true';
            } else {
                $status = 'false';
            }
            if ($action && $action == 'banner_slider_status') {
                $guid = $this->helper->getMccData('mdt_cookie_banner_guid');
            } elseif ($action && $action == 'save_server_mode') {
                $mode = $this->getRequest()->getParam('mcc_server_mode');
                $this->helper->updateMccComplianceOption($mode);
                $result->setData(['mode' => $mode]);
                return $result;
            } else {
                $guid = $this->getRequest()->getParam('banner_guid');
                $pattern = "/^[a-zA-Z0-9-]{36}+$/";
                $liveUrl = $this->helper->getMccData('mdt_cookie_live_url');
                $filePath = $liveUrl . $guid . '.js';
                
        
                if (!preg_match($pattern, $guid)) {
                    $result->setData(['message' => 'not_validate_guid']);
                    return $result;
                }
                       
             
            }
           
            $options = ['banner_guid' => $guid, 'banner_status' => $status];
            $this->helper->updateMccbannerguid($guid);
            $this->helper->updateMccbannerstatus($status);
            $result->setData($options);
            return $result;
        }
    }
}
