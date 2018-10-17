<?php
class Helper_qiniu
{
//     $accessKey = 'mQyEyI6JF2VE0NVY90UO1PQhVRJcLZPEQJGvtSiN';
//     $secretKey = 'xhWucD2QlTko8jIR2kuRVDj97JpLqufepTBPp3RQ';
//     $bucket = 'jmwgame';
    private $accessKey;
    private $secretKey;
    private $bucket;
    private $auth;
    private $domain = '';
    /**
     * @return the $accessKey
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

 /**
     * @return the $secretKey
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

 /**
     * @return the $bucket
     */
    public function getBucket()
    {
        return $this->bucket;
    }

 /**
     * @return the $auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

 /**
     * @return the $domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

 /**
     * @param string $accessKey
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
    }

 /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

 /**
     * @param string $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

 /**
     * @param \Qiniu\Auth $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

 /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

 /**
     * 初始化
     * @param string $accessKey
     * @param string $secretKey
     * @param string $bucket
     */
    function __construct($accessKey,$secretKey,$bucket)
    {
        $this->accessKey = trim($accessKey);
        $this->secretKey = trim($secretKey);
        $this->bucket = trim($bucket);
        require_once  ROOT_PATH.'lib/qiniu/vendor/autoload.php';
        $this->auth = new Qiniu\Auth($this->accessKey, $this->secretKey);
    }
    /**
     * 上传文件
     * @param 文件名称 $filename
     * @param 绝对路径 $filepath
     */
    function uploadFile($filename,$filepath)
    {
        $token = $this->auth->uploadToken($this->bucket);
        $uploadMgr = new Qiniu\Storage\UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $filename, $filepath);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }
    }
}