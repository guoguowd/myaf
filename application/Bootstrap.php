<?php

/**
 * @name Bootstrap
 * @author guojiaxuan
 * @desc   所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see    http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

    /**
     * Method  _initConfig
     * 初始化配置
     *
     * @author jiaxuan
     */
    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();

        Yaf_Registry::set('config', $arrConfig);
    }

    /**
     * Method  _initRequestId
     * 初始化请求ID
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initRequestId(Yaf_Dispatcher $dispatcher) {
        Yaf_Registry::set('request_id', uniqid());
    }

    /**
     * Method  _initLocalNamespace
     * 注册本地命名空间
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initLocalNamespace(Yaf_Dispatcher $dispatcher) {
        Yaf_Loader::getInstance()->registerLocalNamespace(array(
            'Base',
            'Vendor',
            'Zend'
        ));
    }

    /**
     * Method  _initDebug
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initDebug(Yaf_Dispatcher $dispatcher) {
        Yaf_Loader::import(Yaf_Registry::get('config')->application->library . '/Debug.php');
    }

    /**
     * Method  _initPlugin
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        $defaultPlugin = new DefaultPlugin();

        $dispatcher->registerPlugin($defaultPlugin);

    }

    /**
     * Method  _initRoute
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
    }

    /**
     * Method  _initView
     *
     * @author jiaxuan
     *
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initView(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }
}
