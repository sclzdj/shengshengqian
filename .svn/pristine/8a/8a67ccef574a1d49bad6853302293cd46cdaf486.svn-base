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
class appmsggetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['page'=>'输入当前页码','pagesize'=>'输入要拉取的数据量长度']);
      $user = $this->isAppLogin(true);
      if($user->isSuccess())
      {
          $page = intval($this->getRequest('page',1));
          $pagesize = intval($this->getRequest('pagesize',10));
          if($page < 1){
              $page = 1;
          }
          $user_id = $this->getUserId();
          $row = Db::name("user_message")->where("user_id = ?",[$user_id])->field("id,title,pic,is_read,created")->page("{$page},{$pagesize}")->order("id DESC")->select();
          foreach ($row as $k=>&$v)
          {
              $v['pic'] = api_get_file_path($v['pic']);//改为URL
          }
          $this->response($row, 200, '');
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}