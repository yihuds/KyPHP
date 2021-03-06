<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <461960962@qq.com>
// +----------------------------------------------------------------------
/**
 * Created by PhpStorm.
 * Script Name: BaseCtl.php
 * Create: 2020/3/1 15:13
 * Description: 
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\controller;

use think\Controller;

class BaseCtl extends Controller
{
    protected $assign = [];
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        model('common/setting')->settings();
    }

    /**
     * 统一视图
     * @param string $view
     * @param array $assign
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     */
    public function show($assign = [], $view = ''){
        $assign['controller'] = strtolower(request()->controller());
        $assign['action'] = strtolower(request()->action());
        $assign['static_version'] = config("app_debug") ? time() : config('version');

        $this->assign = array_merge($this->assign, $assign);

        if (!$view) {
            $view = $assign['action'];
        }
        return view($view, $this->assign);
    }
}