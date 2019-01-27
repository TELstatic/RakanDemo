<?php

namespace App\Service;

use Illuminate\Support\Facades\Route;

class PermissionService
{
    public $list = [];

    //命名空间
    public $permissions = [
        'Web' => [],
        'Api' => [],
        'Admin'=>[],
    ];

    //排除控制器
    public $blackList = [
        'Barryvdh\Debugbar\Controllers\CacheController',
        'Barryvdh\Debugbar\Controllers\OpenHandlerController',
        'Barryvdh\Debugbar\Controllers\AssetController',
        'App\Http\Controllers\Admin\LoginController',
        'App\Http\Controllers\Auth\LoginController',
        'App\Http\Controllers\Auth\RegisterController',
        'App\Http\Controllers\Auth\ForgotPasswordController',
        'App\Http\Controllers\Auth\ResetPasswordController',
        'App\Http\Controllers\Auth\BladeController',
        'App\Http\Controllers\HomeController',
    ];

    public function __construct()
    {
        $this->getControllers();
    }

    /**
     * 获取所有路由中使用的控制器
     */
    public function getControllers()
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $arr = array();
            $actionName = $route->getActionName();

            preg_match('/\@/', $actionName, $end, PREG_OFFSET_CAPTURE);
            if (!isset($end[0])) {
                continue;
            }

            $controller = substr($actionName, 0, $end[0][1]);

            if (in_array($controller, $this->blackList)) {
                continue;
            }

            $arr['controller'] = $controller;

            $action = substr($actionName, $end[0][1] + 1);
            $arr['action'] = $action;

            $arr['method'] = method_exists($route, 'getMethods') ? $route->getMethods()[0]:$route->methods[0];
            $arr['uri'] = method_exists($route, 'getPath') ? $route->getPath():$route->uri;
            array_push($this->list, $arr);
        }
    }

    /**
     * 返回控制器注释
     */
    public function index()
    {
        $data = [];

        for ($i = 0; $i < count($this->list); $i++) {
            $class = $this->list[$i]['controller'];
            $className = explode('\\', str_replace('App\Http\Controllers\\', '', $class));

            $reflection = new \ReflectionClass($class);

            $actions = $this->getActions($class);

            switch (count($className)) {
                case 1:
                    //$data['/'][$className[0]] = $this->getActionDoc($actions, $reflection);
                    break;
                case 2:
                    $data[$className[0]][$className[0] . '/' . $className[1]]['class'] = $this->getClassDoc($reflection);
                    $data[$className[0]][$className[0] . '/' . $className[1]]['actions'] = $this->getActionDoc($actions, $reflection, $className[0]);
                    $data[$className[0]][$className[0] . '/' . $className[1]]['uri'] = $this->list[$i]['uri'];
                    break;
                case 3:
                    $data[$className[0]][$className[0] . '/' . $className[1] . '/' . $className[2]]['class'] = $this->getClassDoc($reflection);
                    $data[$className[0]][$className[0] . '/' . $className[1] . '/' . $className[2]]['actions'] = $this->getActionDoc($actions, $reflection, $className[0]);
                    $data[$className[0]][$className[0] . '/' . $className[1] . '/' . $className[2]]['uri'] = $this->list[$i]['uri'];
                    break;
            }
        }
        return $data;
    }

    /**
     * 获取所有权限
     */
    public function getPermissions($guard = "Admin")
    {
        $permissions = array_unique($this->permissions[$guard]);
        sort($permissions);

        return $permissions;
    }

    /**
     * 解析类注释
     */
    public function getClassDoc($reflection)
    {
        $doc = $reflection->getDocComment();

        $arr = $this->formatClassDoc($doc);

        return $arr;
    }

    /**
     * 获取控制器中方法
     */
    public function getActions($controller)
    {
        $obj = [];
        foreach ($this->list as $value) {
            if ($value['controller'] == $controller) {
                $obj[] = $value['action'];
            }
        }
        return $obj;
    }

    /**
     * 获取方法Url
     */
    public function getRequestUrl($controller, $action)
    {
        foreach ($this->list as $one) {
            if ($one['controller'] == $controller && $one['action'] == $action) {
                return env('APP_URL') . $one['uri'];
            }
        }
        return '';
    }

    /**
     * 获取请求类型
     */
    public function getRequestMethod($controller, $action)
    {
        foreach ($this->list as $one) {
            if ($one['controller'] == $controller && $one['action'] == $action) {
                return $one['method'];
            }
        }
        return '';
    }

    /**
     * 获取方法注释
     */
    public function getActionDoc($methodArray, \ReflectionClass $reflection, $nameSpace)
    {
        $arr = [];
        $methods = $reflection->getMethods();
        $i = 0;
        foreach ($methods as $key => $property) {
            if (!in_array($property->getName(), $methodArray))
                continue;
            $doc = $property->getDocComment();

            $arr[$i]['name'] = $property->getName();
            $controller = $reflection->getName();
            $arr[$i]['url'] = $this->getRequestUrl($controller, $arr[$i]['name']);
            $arr[$i]['auth'] = str_replace(config('app.url'), '', $arr[$i]['url']);

            array_push($this->permissions[$nameSpace], $arr[$i]['auth']);
            $arr[$i]['method'] = $this->getRequestMethod($controller, $arr[$i]['name']);
            $arr[$i]['_expanded'] = true;

            $arr[$i]['doc'] = $this->formatDoc($doc, $nameSpace);

            $i++;
        }
        return $arr;
    }

    /**
     * 格式化注释代码
     */
    public function formatClassDoc($doc)
    {
        if (!$doc) {
            return [
                'title' => '',
                'check' => false,
                'desc'  => ''
            ];
        }

        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false) {
            return [];
        }

        if (preg_match_all('#^\s*\*(.*)#m', trim($comment[1]), $lines) === false) {
            return [];
        }

        $title = $this->formatTitle($lines[1]);
        $desc = $this->formatDesc($lines[1]);

        return [
            'title' => $title,
            'check' => false,
            'desc'  => $desc
        ];
    }

    /**
     * 格式化注释代码
     */
    public function formatDoc($doc, $nameSpace)
    {
        if (!$doc) {
            return [
                'title'   => '',
                'desc'    => '',
                'params'  => '',
                'returns' => ''
            ];
        }

        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false) {
            return [];
        }


        if (preg_match_all('#^\s*\*(.*)#m', trim($comment[1]), $lines) === false) {
            return [];
        }

        $title = $this->formatTitle($lines[1]);
        $desc = $this->formatDesc($lines[1]);
        $params = $this->formatParams($lines[1]);
        $return = $this->formatReturn($lines[1]);

        return [
            'title'   => $title,
            'desc'    => $desc,
            'params'  => $params,
            'returns' => $return
        ];
    }

    /**
     * 格式化标题
     */
    public function formatTitle($line)
    {
        if (count($line) > 0) {
            return trim($line[0]);
        } else {
            return '';
        }
    }

    /**
     * 格式化描述
     */
    public function formatDesc($lines)
    {
        $reg = '/@desc.*/i';
        $desc = [];

        foreach ($lines as $k => $line) {
            if (preg_match($reg, trim($line), $tmp) !== false)
                if (!empty($tmp)) {
                    $desc[] = trim(str_replace('@desc', "", $tmp[0]));
                }
        }

        return $desc;
    }


    /**
     * 格式化参数
     * @desc 名称 类型 是否必须 默认值 最大值 最小值 描述
     */
    public function formatParams($lines)
    {
        $reg = '/@params.*/i';
        $params = [];

        foreach ($lines as $k => $line) {
            if (preg_match($reg, trim($line), $tmp) !== false)
                if (!empty($tmp)) {
                    $temp = explode(' ', trim(str_replace('@params', "", $tmp[0])));

                    if (count($temp) == 7) {
                        $params[$k]['name'] = $temp[0];
                        $params[$k]['type'] = $temp[1];
                        $params[$k]['require'] = $temp[2];
                        $params[$k]['default'] = $temp[3];
                        $params[$k]['min'] = $temp[4];
                        $params[$k]['max'] = $temp[5];
                        $params[$k]['comment'] = $temp[6];
                    }
                }
        }

        sort($params);
        return $params;
    }

    /**
     * 格式化返回值
     */
    public function formatReturn($lines)
    {
        $reg = '/@return.*/i';
        $return = [];

        foreach ($lines as $k => $line) {
            if (preg_match($reg, trim($line), $tmp) !== false) {
                if (!empty($tmp)) {
                    $temp = explode(' ', trim(str_replace('@return', "", $tmp[0])));
                    if (count($temp) == 2) {
                        $return[$k]['self_type'] = $temp[0];
                        $return[$k]['data_type'] = $temp[1];
//                        $return[$k]['success'] = $temp[2];
//                        $return[$k]['fail'] = $temp[3];
                    }
                }
            }
        }

        sort($return);
        return $return;
    }
}
