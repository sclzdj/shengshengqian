<?php
namespace app\common\validate;

use think\Validate;

class UserInfo extends Validate
{
    protected $rule = [
        'username' => 'require|max:25|length:11|regex:\d{11}',
        'password' => 'require|length:6,16',
    ];
    protected $message = [
        'username.require' => '手机号码必须输入',
        'username.length'  => '手机号码格式错误',
        'username.regex'   => '手机号码格式不正确',
        'password.require' => '密码必须输入',
        'password.length' => '密码长度只能为6-16位',
    ];
    protected $scene = [
        'checkname' => ['username'],
        'checkpass' => ['password']
    ];
}