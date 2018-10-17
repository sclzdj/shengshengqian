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
 * @package app\api\controller
 */
class usereditdetail extends Baseaction
{
    function run()
    {
      //输入你的代码
        $this->checkNeedParam(['header_ico'=>'请上传头像!','nickname'=>'请设置昵称!','sex'=>'请设置性别!','birthday'=>'请选择生日!','address'=>'请设置地区!']);
        $login = $this->isAppLogin();
        if($login->isSuccess())
        {
            //如果登录
            $header_ico = $this->getRequest('header_ico','');
            $nickname = $this->getRequest('nickname','');
            $sex = $this->getRequest('sex',0);
            $birthday = $this->getRequest('birthday','');
            $address = $this->getRequest('address','');
            $update = [
                'nickname'=>$nickname,
                'header_ico'=>$header_ico,
                'sex'=>$sex,
                'birthday'=>$birthday,
                'address'=>$address
            ];
            Db::name("users_detail")->where("user_id",$this->getUserId())->update($update);
            $row = Db::name("users_detail")->where("user_id = ?",$this->getUserId())->find();
            $this->response($row, 200, '');
        }else{
            $this->response([], $this->_notLoginCode, $login->getMsg());
        }
    }
}