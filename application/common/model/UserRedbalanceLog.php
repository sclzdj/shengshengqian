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

use think\Model;
use think\Db;
class UserRedbalanceLog extends Model
{

    private $user_id = 0;

    private $op_redbalance = 0;

    private $op_remark = '';

    private $op_class = '';

    private $before_redbalace = 0;

    /**
     * @return the $user_id
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

 /**
     * @return the $op_redbalance
     */
    public function getOp_redbalance()
    {
        return $this->op_redbalance;
    }

 /**
     * @return the $op_remark
     */
    public function getOp_remark()
    {
        return $this->op_remark;
    }

 /**
     * @return the $op_class
     */
    public function getOp_class()
    {
        return $this->op_class;
    }

 /**
     * @return the $before_redbalace
     */
    public function getBefore_redbalace()
    {
        return $this->before_redbalace;
    }

 /**
     * @param number $user_id
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

 /**
     * @param number $op_redbalance
     */
    public function setOp_redbalance($op_redbalance)
    {
        $this->op_redbalance = $op_redbalance;
    }

 /**
     * @param string $op_remark
     */
    public function setOp_remark($op_remark)
    {
        $this->op_remark = $op_remark;
    }

 /**
     * @param string $op_class
     */
    public function setOp_class($op_class)
    {
        $this->op_class = $op_class;
    }

 /**
     * @param number $before_redbalace
     */
    public function setBefore_redbalace($before_redbalace)
    {
        $this->before_redbalace = $before_redbalace;
    }

 /**
     * 保存积分操作纪录
     */
    public function saveLog()
    {
        $data = [
            'user_id'=>$this->getUser_id(),
            'op_redbalance'=>$this->getOp_redbalance(),
            'op_remark'=>$this->getOp_remark(),
            'op_class'=>$this->getOp_class(),
            'before_redbalace'=>$this->getBefore_redbalace(),
            'created'=>time()
        ];
        $log = Db::name("user_score_log")->insert($data);
    }
}