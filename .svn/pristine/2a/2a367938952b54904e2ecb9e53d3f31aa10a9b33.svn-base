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
use app\common\model\Users;
use app\common\model\VerificationCode;
use think\Db;
/**
 * API action控制器
 * @package app\api\controller
 */
class userbindmobile extends Baseaction
{
    function run()
    {
    //限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
        $this->checkNeedParam(['username'=>'请输入要验证的号码!','password'=>'传入密码','code_tagid'=>'请先获取验证码!','code_value'=>'请输入验证码!']);
        //验证类
        $check = $this->validate($this->_data, 'UserReg.checkname');
        if(true !== $check){
            $this->response([], 201, $check);
        }
        $r = $this->isAppLogin(true);
        if($r->isSuccess())
        {
            $user = $r->getData();
//             //验证类,只验证号码
//             $check = $this->validate($this->_data, 'UserReg.checkname');
//             if(true !== $check){
//                 $this->response([], 201, $check);
//             }
            $username = $this->getRequest('username','');
            $password = $this->getRequest('password','');
            $code_tagid = $this->getRequest('code_tagid','');
            $code_value = $this->getRequest('code_value','');
            
            //查找是否存在用户
            if(intval($user['auth_mobile']) == 1){
                $this->response([], 202, '号码已经绑定!');
            }else{
                //检查号码是否被其他人绑定
                $mobileUser = Db::name("users")->where("mobile = ?",[$username])->field("id")->find();
                if($mobileUser && intval($mobileUser['id']) <> $user['id'])
                {
                    //已经被其他人绑定了号码
                    $this->response([], 205, '该手机号码已被其他用户绑定,请更换手机号码!');
                }else{
                    //验证号码
                    $r = VerificationCode::verify($username, $code_tagid, $code_value);
                    if(!$r->isSuccess())
                    {
                        $this->response([], 203, $r->getMsg());
                    }else{
                        $salt = mt_rand(100000, 999999);
                        $password = Users::makePassword($password, $salt );
                        $isChange = Users::where("id",$user['id'])->update(['auth_mobile'=>1,'mobile'=>$username,'username'=>$username,'password'=>$password,'salt'=>$salt]);
                        if($isChange)
                        {
                            $this->response([], 200, '手机号码绑定成功!');
                        }else{
                            $this->response([], 204, '数据更新失败,请稍后重试!');
                        }
                    } 
                }
            }
        }else{
            return $this->response([], $this->_notLoginCode, '');
        }
        
    }
}