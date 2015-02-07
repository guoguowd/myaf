<?php

/**
 * Class     Base_Controllers
 * 全局的控制器基类
 *
 * @author   jiaxuan
 */
class Base_Controllers extends Yaf_Controller_Abstract {

    /**
     * Variable  request
     * request对象
     *
     * @author   jiaxuan
     * @var      null
     */
    public $request = null;

    /**
     * Variable  view
     * view 对象
     *
     * @author   jiaxuan
     * @var      null
     */
    public $view = null;

    /**
     * Method  init
     * 初始方法
     *
     * @author jiaxuan
     */
    public function init() {
        $this->request = $this->getRequest();
        $this->view    = $this->getView();
    }
}