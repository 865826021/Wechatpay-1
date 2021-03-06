<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Lib\Helper;
use Hyperbolaa\Wechatpay\Module\UnifiedOrder;

/**
 * Class Apppay
 * @package Hyperbolaa\Wechatpay\Sdk
 */
class Apppay extends BasePay
{

	protected $trade_type  = 'App';//交易类型

	/**
	 * 签名
	 */
	public function sign(){

		$data = [
			'appid'            => $this->app_id,//--
			'mch_id'           => $this->merchant_id,//--
			'device_info'      => $this->device_info ?: 'App',
			'body'             => $this->body,//todo
			'attach'           => $this->attach,
			'out_trade_no'     => $this->out_trade_no,//todo
			'fee_type'         => $this->fee_type,
			'total_fee'        => $this->total_fee,//todo
			'spbill_create_ip' => $this->spbill_create_ip ?: Helper::get_client_ip(),
			'time_start'       => $this->time_start,
			'time_expire'      => $this->time_expire,
			'goods_tag'        => $this->goods_tag,
			'notify_url'       => $this->notify_url,//--
			'trade_type'       => $this->trade_type,
			'limit_pay'        => $this->limit_pay,
			'nonce_str'        => $this->getNonceStr()
		];

		$data['sign'] = Helper::sign($data, $this->key);
		return $data;
	}

	/**
	 * 统一下单
	 */
	public function prepare(){
		$data = $this->sign();
		$unifiedOrder = new UnifiedOrder();
		return  $unifiedOrder->create($data);
	}


	/**
	 * Generate app payment parameters.
	 *
	 * @param string $prepayId
	 *
	 * @return array
	 */
	public function configForAppPayment($prepayId)
	{
		$params = [
			'appid'         => $this->app_id,
			'partnerid'     => $this->merchant_id,
			'prepayid'      => $prepayId,
			'noncestr'      => uniqid(),
			'timestamp'     => time(),
			'package'       => 'Sign=WXPay',
		];

		$params['sign'] = Helper::sign($params, $this->key);

		return $params;
	}
}