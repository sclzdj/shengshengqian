<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Config 后台模块
 */
class Config extends Admin
{
    //配置项管理
    public function index()
    {
        // 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        // 读取用户数据
        $data_list = Db::name('config')->where($map)->where('agent_id',session('user_auth.uid'))->order($order)->paginate();
        // 分页数据
        $page = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('配置项列表') // 设置页面标题
            ->setPageTips('视图类型支持WebView：<span style="color:#f00000;">index/appweb/news/id/<b style="color:#0000f0;">%d</b></span>，其中%d为id。<br>配置项取值函数<span style="color:#f00000;">G_CF("<b style="color:#0000f0;">%s</b>")</span>，其中%s为配置项名称，即name，传入的name若没有则返回false。') // 设置页面提示信息
            ->addOrder('id') // 添加排序
            ->setSearch(['id' => 'ID','title' => '标题']) // 设置搜索参数
            ->addColumns([
                    ['id', 'ID'],
                    ['title', '标题'],
                    ['name', '名称'],
                    ['type', '类型','callback','array_v',['text' => '文本框', 'textarea' => '文本域', 'view' => '视图', 'image'=>'图片']],
                    ['load', '接口返回','callback','array_v',['不返回','APP启动加载','主页加载']],
                    ['right_button', '操作', 'btn'],
                ]) //添加多列数据
            ->addRightButtons(['edit','delete'=>['table'=>'config']]) // 批量添加右侧按钮
            ->addTopButtons(['add','delete'=>['table'=>'config']]) // 批量添加顶部按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    //添加配置项
    public function add()
    {
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            $validate = new Validate([
                'title|配置项标题'  => 'require',
                'name|配置项名称'  => 'require',
                'type|配置项类型'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            $config=db('config')->where(['name'=>$data['name'],'agent_id'=>session('user_auth.uid')])->find();
            if($config){
                return $this->error('配置项名称已存在');
            }
            //数据处理
            $insert=array();
            $insert['title']=$data['title'];
            $insert['name']=$data['name'];
            $insert['type']=$data['type'];
            $insert['val']=$data['val_'.$data['type']];
            $insert['load']=$data['load'];
            $insert['agent_id']=session('user_auth.uid');
            //数据入库
            $config_id=Db::name("config")->insert($insert);
            //跳转
            if($config_id>0){
                return $this->success('添加配置项成功','index','',1);
            } else {
                return $this->error('添加配置项失败');
            }
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('添加配置项') // 设置页面标题
            ->setPageTips('请认真填写相关信息') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addText('title', '配置项标题','请最好不要超过20个汉字')
            ->addText('name', '配置项名称','请最好不要超过20个字符')
            ->addSelect('type', '配置项类型','选择后下面会自动触发',['text' => '文本框', 'textarea' => '文本域', 'view' => '视图', 'image'=>'图片'])
            ->addText('val_text', '配置项内容','')
            ->addTextarea('val_textarea', '配置项内容','')
            ->addCkeditor('val_view', '配置项内容','')
            ->addImage('val_image', '配置项内容','')
            ->addRadio('load', '接口返回','选择后对应接口加载将返回',['0'=>'不返回','1'=>'APP启动加载','2'=>'主页加载'],0)
            ->setTrigger('type', 'text', 'val_text')
            ->setTrigger('type', 'textarea', 'val_textarea')
            ->setTrigger('type', 'view', 'val_view')
            ->setTrigger('type', 'image', 'val_image')
            //->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    //修改配置项
    public function edit($id='')
    {
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            $validate = new Validate([
                'title|配置项标题'  => 'require',
                'name|配置项名称'  => 'require',
                'type|配置项类型'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            $config=db('config')->where(['id'=>['neq',$data['id']],'name'=>$data['name'],'agent_id'=>session('user_auth.uid')])->find();
            if($config){
                return $this->error('配置项名称已存在');
            }
            //数据处理
            $update=array();
            $update['id']=$data['id'];
            $update['title']=$data['title'];
            $update['name']=$data['name'];
            $update['type']=$data['type'];
            $update['val']=$data['val_'.$data['type']];
            $update['load']=$data['load'];
            $update['agent_id']=session('user_auth.uid');
            //数据更新
            $rt=Db::name("config")->update($update);
            //跳转
            if($rt!==false){
                return $this->success('修改配置项成功','index','',1);
            } else {
                return $this->error('修改配置项失败');
            }
        }
        if($id>0){
            //选择分类下拉框数据
            $config=db('config')->find($id);
            // 使用ZBuilder快速创建表单
            return ZBuilder::make('form')
                ->setPageTitle('修改配置项') // 设置页面标题
                ->setPageTips('请认真填写相关信息') // 设置页面提示信息
                //->setUrl('add') // 设置表单提交地址
                //->hideBtn(['back']) //隐藏默认按钮
                ->setBtnTitle('submit', '确定') //修改默认按钮标题
                ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
                ->addText('title', '配置项标题','请最好不要超过20个汉字',$config['title'])
                ->addText('name', '配置项名称','请最好不要超过20个字符',$config['name'])
                ->addSelect('type', '配置项类型','选择后下面会自动触发',['text' => '文本框', 'textarea' => '文本域', 'view' => '视图', 'image'=>'图片'],$config['type'])
                ->addText('val_text', '配置项内容','',$config['type']=='text'?$config['val']:'')
                ->addTextarea('val_textarea', '配置项内容','',$config['type']=='textarea'?$config['val']:'')
                ->addCkeditor('val_view', '配置项内容','',$config['type']=='view'?$config['val']:'')
                ->addImage('val_image', '配置项内容','',$config['type']=='image'?$config['val']:'')
                ->addRadio('load', '接口返回','选择后对应接口加载将返回',['0'=>'不返回','1'=>'APP启动加载','2'=>'主页加载'],$config['load'])
                ->setTrigger('type', 'text', 'val_text')
                ->setTrigger('type', 'textarea', 'val_textarea')
                ->setTrigger('type', 'view', 'val_view')
                ->setTrigger('type', 'image', 'val_image')
                ->addHidden('id',$config['id'])
                //->isAjax(false) //默认为ajax的post提交
                ->fetch();
        }
    }
}