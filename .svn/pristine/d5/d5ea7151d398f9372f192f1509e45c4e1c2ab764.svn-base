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
class UserScoreLog extends Model
{

    private $user_id = 0;

    private $op_score = 0;

    private $op_remark = '';

    private $op_class = '';

    private $before_score = 0;

    /**
     *
     * @return the $user_id
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     *
     * @return the $op_score
     */
    public function getOp_score()
    {
        return $this->op_score;
    }

    /**
     *
     * @return the $op_remark
     */
    public function getOp_remark()
    {
        return $this->op_remark;
    }

    /**
     *
     * @return the $op_class
     */
    public function getOp_class()
    {
        return $this->op_class;
    }

    /**
     *
     * @return the $before_score
     */
    public function getBefore_score()
    {
        return $this->before_score;
    }

    /**
     *
     * @param number $user_id            
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     *
     * @param number $op_score            
     */
    public function setOp_score($op_score)
    {
        $this->op_score = $op_score;
    }

    /**
     *
     * @param string $op_remark            
     */
    public function setOp_remark($op_remark)
    {
        $this->op_remark = $op_remark;
    }

    /**
     *
     * @param string $op_class            
     */
    public function setOp_class($op_class)
    {
        $this->op_class = $op_class;
    }

    /**
     *
     * @param number $before_score            
     */
    public function setBefore_score($before_score)
    {
        $this->before_score = $before_score;
    }
    /**
     * 保存积分操作纪录
     */
    public function saveLog()
    {
        $data = [
            'user_id'=>$this->getUser_id(),
            'op_score'=>$this->getOp_score(),
            'op_remark'=>$this->getOp_remark(),
            'op_class'=>$this->getOp_class(),
            'before_score'=>$this->getBefore_score(),
            'created'=>time()
        ];
        $log = Db::name("user_score_log")->insert($data);
    }
}