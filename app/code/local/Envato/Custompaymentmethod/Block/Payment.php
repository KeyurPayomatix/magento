<?php

class Envato_Custompaymentmethod_Block_Payment extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('custompaymentmethod/payment.phtml');
    }
}