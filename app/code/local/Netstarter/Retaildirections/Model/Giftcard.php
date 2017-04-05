<?php
/**
 * Created by JetBrains PhpStorm.
 * User: prasad
 * Date: 9/11/13
 * Time: 12:45 PM
 * To change this template use File | Settings | File Templates.
 */
class Netstarter_Retaildirections_Model_Giftcard extends Netstarter_GiftCardApi_Model_Abstract
{

    protected $_modelCode = 'netstarter_retaildirections';
    protected $_model;
    /**
     * validation pattern
     *
     * @return bool
     */

    private function _getModel()
    {
        if(!$this->_model){
            $this->_model =  Mage::getModel('netstarter_retaildirections/model_giftcard');
        }

        return $this->_model;
    }

    public function validate()
    {
        $isPinNeed = $this->getSettingConfig('/haspin');

        if($isPinNeed && !$this->_pinCode){

            return false;
        }

        $pattern = $this->getSettingConfig('/validation');

        if($pattern && preg_match("$pattern", $this->_giftCardCode)){
            return true;
        }

        return false;
    }

    public function checkBalance()
    {

        $model = $this->_getModel();
        $result = $model->getBalance($this->_giftCardCode, $this->_pinCode);
//        $result = 1000;

        return $result;
    }

    public function redeemGiftCard($amount)
    {
        $model = $this->_getModel();
        $result = $model->redeemGiftCard($this->_giftCardCode, $amount, $this->_pinCode);

        return $result;
    }

    public function multipleRedeemGiftCard($cards)
    {
        $result = null;

        if($cards){

            $model = $this->_getModel();
            $result = $model->multipleRedeemGiftCard($cards);
        }

        return $result;
    }

    public function cancelGiftCardRedeem($transIdsArr)
    {

        foreach($transIdsArr as $transId => $transaction){

            if($transaction){

                $model = $this->_getModel();
                $result = $model->cancelGiftCardRedeem($transaction);

                if($result){
                    unset($transIdsArr[$transId]);
                }
            }
        }

        return $transIdsArr;
    }
}