<?php

class Envato_Custompaymentmethod_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function redirectAction()
    {
        // Your custom logic before redirect

        // Redirect to another URL
        $this->_redirect('http://example.com'); // Replace with your desired URL
    }
}
