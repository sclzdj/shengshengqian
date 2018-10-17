<?php
/**
 * TOP API: taobao.tbk.item.get request
 *
 * @author auto create
 * @since 1.0, 2015.06.13
 */
class TbkItemCouponGetRequest
{
       private $pid = '';
       
       
       
        private $apiParas = array();
       
 
        /**
     * @return the $pid
     */
    public function getPid()
    {
        return $this->pid;
    }

  /**
     * @param string $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
		$this->apiParas["pid"] = $pid;
    }

  public function getApiMethodName()
        {
                return "taobao.tbk.item.coupon.get";
        }
       
        public function getApiParas()
        {
                return $this->apiParas;
        }
       
        public function check()
        {
        }
       
        public function putOtherTextParam($key, $value) {
                $this->apiParas[$key] = $value;
                $this->$key = $value;
        }
}