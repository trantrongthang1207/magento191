<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Amp
 * @copyright   Copyright (c) 2017 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

class Plumrocket_Amp_Model_TM_SuggestPage_Observer extends TM_SuggestPage_Model_Observer
{

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     * Rewrite parent method
     */
    public function addToCartComplete($observer)
    {
        /**
         * Get pramp helper and check its status
         */
        $helper = Mage::helper('pramp');
        if ($helper->moduleEnabled() && strpos(Mage::app()->getRequest()->getOriginalPathInfo(), 'pramp/cart') !== false) {
            return;
        }

        return parent::addToCartComplete($observer);
    }


}
