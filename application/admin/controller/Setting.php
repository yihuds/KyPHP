<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Script Name: Setting.php
 * Create: 2020/5/24 上午10:25
 * Description: 站点配置
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

class Setting extends Base
{
    private $settingM;
    private $tabList;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->settingM = model('setting');
        $this->tabList = [
            'site' => [
                'title' => '站点信息',
                'href' => url('index', ['name' => 'site'])
            ],
            'upload' => [
                'title' => '附件设置',
                'href' => url('index', ['name' => 'upload'])
            ]
        ];
    }

    /**
     * 设置
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        $current_name = input('name', 'site');
        $setting = $this->settingM->getOneByMap(['name' => $current_name]);
        if(request()->isPost()){
            $post_data = input('post.');
            unset($post_data['__token__']);
            if(empty($setting)){
                $res = $this->settingM->addOne([
                    'name' => $current_name,
                    'title' => $this->tabList[$current_name]['title'],
                    'value' => json_encode($post_data)
                ]);
            }else{
                $res = $this->settingM->updateOne([
                    'id' => $setting['id'],
                    'value' => json_encode($post_data)
                ]);
            }
            if($res){
                $this->success('保存成功');
            }else{
                $this->error('保存失败，请刷新重试', '', ['token' => request()->token()]);
            }
        }

        if(empty($setting)){
            $data = [];
        }else{
            $data = json_decode($setting['value'], true);
        }
        $builder = new FormBuilder();
        switch ($current_name){
            case 'site':
                empty($data) && $data['close'] = 0;
                $builder->addFormItem('close', 'radio', '关闭站点', '关闭站点', [1 => '是', 0 => '否'], 'required')
                    ->addFormItem('close_reason', 'textarea', '关闭原因', '不超过100个字', [], 'maxlength=100')
                    ->addFormItem('icp', 'text', '备案号', '备案号')
                    ->addFormItem('logo', 'picture_url', 'LOGO', '250x36')
                    ->addFormItem('keywords', 'text', 'SEO关键词', 'head头部的keywords')
                    ->addFormItem('description', 'textarea', 'SEO描述', 'head头部的description');
                break;
            case 'upload':
                empty($data) && $data = [
                    'driver' => 'local',
                    'file_size' => 53000000,
                    'image_size' => 5000000,
                    'image_ext' => 'jpg,gif,png,jpeg',
                    'file_ext' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf',
                ];
                $builder->addFormItem('driver_title', 'legend', '上传驱动', '上传驱动')
                    ->addFormItem('driver', 'select', '上传驱动', '上传驱动', model('upload')->locations())
                    ->addFormItem('qiniu_ak', 'text', '七牛accessKey', '七牛accessKey')
                    ->addFormItem('qiniu_sk', 'text', '七牛secretKey', '七牛secretKey')
                    ->addFormItem('qiniu_bucket', 'text', '七牛bucket', '七牛bucket')
                    ->addFormItem('qiniu_domain', 'url', '七牛domain', '七牛domain')
                    ->addFormItem('image_title', 'legend', '图片设置', '图片设置')
                    ->addFormItem('image_size', 'number', '图片大小限制', '单位B', [], 'required min=1 max=1000000000')
                    ->addFormItem('image_ext', 'text', '图片格式支持', '多个用逗号隔开', [], 'required')
                    ->addFormItem('file_title', 'legend', '文件设置', '文件设置')
                    ->addFormItem('file_size', 'number', '文件大小限制', '单位B', [], 'required min=1 max=1000000000')
                    ->addFormItem('file_ext', 'text', '文件格式支持', '多个用逗号隔开', [], 'required')
                    ->addFormItem('voice_title', 'legend', '音频设置', '音频设置')
                    ->addFormItem('voice_size', 'number', '音频大小限制', '单位B', [], 'required min=1 max=1000000000')
                    ->addFormItem('voice_ext', 'text', '音频格式支持', '多个用逗号隔开', [], 'required')
                    ->addFormItem('video_title', 'legend', '视频设置', '视频设置')
                    ->addFormItem('video_size', 'number', '视频大小限制', '单位B', [], 'required min=1 max=1000000000')
                    ->addFormItem('video_ext', 'text', '视频格式支持', '多个用逗号隔开', [], 'required')
                    ;
                break;
        }
        $builder->setFormData($data);
        return $builder->show(['tab_nav' => ['tab_list' => $this->tabList, 'current_tab' => $current_name]]);
    }
}