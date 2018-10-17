<?php
namespace app\index\controller;
class Appweb extends Home
{
    /**
     * 关于我们 
     */
    public function aboutme()
    {
        
    }
    /**
     * 使用说明
     */
    public function help()
    {
        
    }
    /**
     * 分销排行榜
     */
    public function sharerank()
    {
        
    }
    /**
     * 分销说明
     */
    public function sharehelp()
    {
        
    }
    /**
     * 消息详情
     */
    public function msgdetail()
    {
        
    }
    /**
     * 注册协议，带上appid
     */
    public function regprotocol()
    {
        
    }
    /**
     * 隐私协议
     */
    public function privacyprotocol()
    {
        
    }
    /**
     * 用户话单 token
     */
    public function usercdr()
    {
        
    }
    /**
     * 配置视图链接
     */
    public function news($id='')
    {
        $config=db('config')->find($id);
        if(!$config || $config['type']!='view'){
            return $this->error('错误访问');
        }
        echo $config['val'];
    }
}