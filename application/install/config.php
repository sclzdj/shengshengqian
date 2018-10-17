<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017   [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

/**
 * 安装程序配置文件
 */
return array(
    //产品配置
    'install_product_name'   => 'DolphinPHP', //产品名称
    'install_website_domain' => 'http://www.dolphinphp.com', //官方网址
    'install_company_name'   => ' ', //公司名称
    'original_table_prefix'  => 'dp_', //默认表前缀

    // 安装配置
    'install_table_total' => 207, // 安装时，需执行的sql语句数量
);
