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

namespace app\common\model;


use think\Db;
/**
 * 公共模型
 * @package app\common\model
 */
class UserRedpackage 
{
    private $_title = '系统红包';
    
    private $_remark = '注册有礼,狂抢不停';
    
    private $_pic = 0;
    
    private $_red_class = 'reg';
    
    private $_red_class_param = '';
    
    private $_app_pop = 0;
    
    private $_weight = 0;
    
    /**
     * @return the $_weight
     */
    public function getWeight()
    {
        return $this->_weight;
    }

 /**
     * @param number $_weight
     */
    public function setWeight($_weight)
    {
        $this->_weight = $_weight;
    }

 /**
     * @return the $_app_pop
     */
    public function getApp_pop()
    {
        return $this->_app_pop;
    }

 /**
     * @param number $_app_pop
     */
    public function setApp_pop($_app_pop)
    {
        $this->_app_pop = $_app_pop;
    }

 /**
     * @return the $_title
     */
    public function getTitle()
    {
        return $this->_title;
    }

 /**
     * @return the $_remark
     */
    public function getRemark()
    {
        return $this->_remark;
    }

 /**
     * @return the $_pic
     */
    public function getPic()
    {
        return $this->_pic;
    }

 /**
     * @return the $_red_class
     */
    public function getRed_class()
    {
        return $this->_red_class;
    }

 /**
     * @return the $_red_class_param
     */
    public function getRed_class_param()
    {
        return $this->_red_class_param;
    }

 /**
     * @param string $_title
     */
    public function setTitle($_title)
    {
        $this->_title = $_title;
    }

 /**
     * @param string $_remark
     */
    public function setRemark($_remark)
    {
        $this->_remark = $_remark;
    }

 /**
     * @param number $_pic
     */
    public function setPic($_pic)
    {
        $this->_pic = $_pic;
    }

 /**
     * @param string $_red_class
     */
    public function setRed_class($_red_class)
    {
        $this->_red_class = $_red_class;
    }

 /**
     * @param string $_red_class_param
     */
    public function setRed_class_param($_red_class_param)
    {
        $this->_red_class_param = $_red_class_param;
    }

 /**
     * 给用户发送初始化红包
     * @param int $user_id
     * @param int 单位分 $money
     * @param int 个数 $rednum
     * @param string $title
     * @param string $remark
     * @param int $pic
     */
    public function sendRedPack($user_id,$money,$rednum)
    {
        $rednum = intval($rednum); // 不大于此
        $money = intval($money);
        $user_id = intval($user_id);
        if ($money < $rednum)
            return false;
        $redArr = array();
        $total = $money; // 红包总额
        $num = $rednum; // 分成8个红包，支持8人随机领取
        $min = 1; // 每个人最少能收到0.01元
        
        for ($i = 1; $i < $num; $i ++) {
            $safe_total = ceil(($total - ($num - $i) * $min) / ($num - $i)); // 随机安全上限
            $tmp = mt_rand($min, $safe_total);
            $total = $total - $tmp;
            $redArr[] = $tmp;
        }
        $redArr[] = $total;
        
        $time = time();
        $batch_number = $time;
        $sql = "INSERT INTO `%s`.`%s`(user_id,red_balance,red_title,red_remark,pic,created,batch_number,red_class,red_class_param,app_pop) VALUES";
        $sql = sprintf($sql,config('database.database'),config('database.prefix').'user_redpackage');
        $template = "(%d,%d,'%s','%s',%d,%d,%d,'%s','%s',%d)";
        $item = false;
        foreach ($redArr as $k=>$v)
        {
            $item[] = sprintf($template,$user_id,$v,$this->getTitle(),$this->getRemark(),$this->getPic(),$time,$batch_number,$this->getRed_class(),$this->getRed_class_param(),$this->getApp_pop());
            if( ($k % 100) == 0)
            {
                Db::execute($sql.implode(',', $item));
                $item = false;
            }
        }
        if($item)
        {
            Db::execute($sql.implode(',', $item));
            $item = false;
        }
    }
}