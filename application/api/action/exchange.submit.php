<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 成都锐萌软件开发有限公司 [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\Db;

/**
 * API action控制器
 * 
 * @package app\api\controller
 */
class exchangesubmit extends Baseaction
{

    function run()
    {
        // 输入你的代码
        $this->checkNeedParam([
            'pid' => '请传入产品id',
            'contact_name' => '填写收货人名称',
            'contact_phone' => '填写收货人电话',
            'address' => '填写收货地址',
            'recharge_account' => '填写充值账号'
        ]);
        $r = $this->isAppLogin(true);
        if ($r->isSuccess()) {
            $userdata = $r->getData();
            $pid = intval($this->getRequest('pid', 0));
            $contact_name = $this->getRequest('contact_name', '');
            $contact_phone = $this->getRequest('contact_phone', '');
            $address = $this->getRequest('address', '');
            $recharge_account = $this->getRequest('recharge_account', '');
            // 获取产品
            $pinfo = Db::name("exchange_order")->where("id = ?", [
                $pid
            ])->find();
            if (! $pinfo) {
                $this->response([], 203, '兑换产品不存在!');
            } else 
                if ($pinfo['p_type'] == 0 && ! $recharge_account) {
                    // 0虚拟 1实物
                    $this->response([], 201, '请填写充值账号!');
                } else 
                    if ($pinfo['p_type'] == 1 && (! $contact_name || ! $contact_phone || ! $address)) {
                        $this->response([], 202, '请填写完整收货信息!');
                    } else 
                        if ($pinfo->stock < 1) {
                            $this->response([], 204, '对不起,产品已兑换完~');
                        } else 
                            if ($userdata['red_balance'] < $pinfo['red_price']) {
                                $this->response([], 205, '对不起,您的红包余额不足!');
                            } else 
                                if ($userdata['score'] < $pinfo['score']) {
                                    $this->response([], 206, '对不起,您的积分余额不足!');
                                } else {
                                    // 扣除用户金币和红包
                                    $sql = "UPDATE `%s`.`%s` SET score = score - %d,red_balance = red_balance - %.2f WHERE id = %d";
                                    $sql = sprintf($sql, config('database.database'), config('database.prefix') . 'users', $pinfo['score'], $pinfo['red_price']);
                                    Db::execute($sql);
                                    
                                    $orderinfo = [
                                        'orderid' => get_order_id('DH'),
                                        'user_id' => $this->getUserId(),
                                        'pid' => $pid,
                                        'red_balance' => $pinfo['red_price'],
                                        'score' => $pinfo['score'],
                                        'address' => $address,
                                        'recharge_account' => $recharge_account,
                                        'contact_name' => $contact_name,
                                        'contact_phone' => $contact_phone,
                                        'created' => time(),
                                        'p_type' => $pinfo['p_type']
                                    ];
                                    Db::name("exchange_order")->insert($orderinfo);
                                    $this->response([], 200, '兑换成功');
                                }
        } else {
            $this->response([], $this->_notLoginCode, '');
        }
    }
}