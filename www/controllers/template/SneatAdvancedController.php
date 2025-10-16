<?php

namespace app\controllers\template;

use app\helpers\RouteHelper;

abstract class SneatAdvancedController extends UISneatTemplateController
{
    protected $content;
    protected $view;
    protected $breadcrumb;
    protected $layoutName;
    protected $request;
    protected $session;
    protected $data;
    protected $routes;
    public function __construct()
    {
        parent::__construct();
        $this->routes = new RouteHelper();
    }
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;
    }
    public function setRequest($request)
    {
        $this->request = $request;
    }
    public function setSession($session)
    {
        $this->session = $session;
    }
    public function setData($data)
    {
        $this->data = $data;
    }
    // getters
    public function getContent()
    {
        return $this->content;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    public function getLayoutName()
    {
        return $this->layoutName;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getData()
    {
        return $this->data;
    }
}
