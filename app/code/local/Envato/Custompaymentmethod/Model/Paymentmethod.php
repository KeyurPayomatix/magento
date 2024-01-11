<?php

class Envato_Custompaymentmethod_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'custompaymentmethod'; // Unique identifier for your payment method

    protected $_isInitializeNeeded = true;
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;

    public function getOrderPlaceRedirectUrl()
    {
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        $directPaymentAPIResponse = $this->makeHttpRequest($currentUrl);

        $this->processHttpResponse($directPaymentAPIResponse);
//        dd($response);
        
        // This method should return the URL to redirect the user to after placing the order
        return Mage::getUrl('custompaymentmethod/payment/redirect', array('_secure' => true));
    }

    protected function makeHttpRequest($returnUrl)
    {
        // URL and parameters
        $url = 'http://payomatix_admin.test/payment/merchant/transaction';
        $params = array(
            'email' => 'test@example.com',
            'amount' => 1000,
            'currency' => 'INR',
            'return_url' => $returnUrl,
            'first_name' => 'Test',
        );

        // Headers
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'PAY95CSTU1JJXGJWWRWIA1687159538.LXHK17SECKEY',
        );

        // Create HTTP client
        $client = new Zend_Http_Client($url);
        $client->setHeaders($headers);
        $client->setParameterPost($params);
        $client->setMethod(Zend_Http_Client::POST);

        // Execute the request
        $response = $client->request();
        // Process the response as needed
        $body = $response->getBody();
        return $body;
    }

    protected function processHttpResponse($response)
    {
        $responseData = json_decode($response, true);

        if ($responseData && isset($responseData['status']) && $responseData['status'] === 'redirect' && isset($responseData['redirect_url'])) {
            // Perform the redirection
//            header("Location: " . $responseData['redirect_url']);
//            exit;
            Mage::app()->getResponse()->setRedirect($responseData['redirect_url']);
            Mage::app()->getResponse()->sendResponse();
            exit;
        }

        // Handle other cases if needed
    }

    protected function processHttpResponse2($response)
    {
        $responseData = json_decode($response, true);

        if ($responseData && isset($responseData['status']) && $responseData['status'] === 'redirect' && isset($responseData['redirect_url'])) {
            // Perform the redirection using JavaScript
            echo "<script>window.location.href = '{$responseData['redirect_url']}';</script>";
            exit;
        }

        // Handle other cases if needed
    }

    protected function processHttpResponse3($response)
    {
        $responseData = json_decode($response, true);

        if ($responseData && isset($responseData['status']) && $responseData['status'] === 'redirect' && isset($responseData['redirect_url'])) {
            // Add a JavaScript script to the head block to perform the redirection
            $script = "<script>
    var redirectTo = '{$responseData['redirect_url']}';
    require(['jquery'], function ($) {
        $(document).ready(function () {
            window.location.href = redirectTo;
        });
    });
</script>";

            $headBlock = Mage::app()->getLayout()->getBlock('head');
            if ($headBlock) {
                $headBlock->addText($script);
            }
        }

        // Handle other cases if needed
    }

    public function initialize($paymentAction, $stateObject)
    {
        // Initialize method, you can customize as needed
        $state = Mage_Sales_Model_Order::STATE_NEW;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_payment');
        $stateObject->setIsNotified(false);
    }
}