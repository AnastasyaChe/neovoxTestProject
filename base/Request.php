<?php


namespace app\base;


class Request
{
    protected $requestString = '';
    protected $controllerName = null;
    protected $actionName = null;
    protected $page = null;
    protected $isPost = false;
    protected $isGet = true;
    protected $isAjax = false;
    protected $method;

    //controller/action&id=1

    protected $urlPattern = "#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?[?]?(?P<get>.*)#ui";

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->parseRequest();
    }

    protected function parseRequest()
    {
        if (preg_match_all($this->urlPattern, $this->requestString, $matches)) {
            $this->controllerName = $matches['controller'][0];
            $this->actionName = $matches['action'][0];
            $this->page = $matches['get'][0];
        }
    }

    /**
     * get
     *
     * @param  mixed $name
     * @return void
     */
    public function get(string $name)
    {
        return $_GET[$name];
    }

    /**
     * post
     *
     * @param  mixed $name
     * @return void
     */
    public function post(string $name)
    {
        return $_POST[$name];
    }

    /**
     * param
     *
     * @param  mixed $name
     * @return void
     */
    public function param(string $name)
    {
        return $_REQUEST[$name];
    }


    /**
     * getControllerName
     *
     * @return void
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }


    /**
     * getActionName
     *
     * @return void
     */
    public function getActionName()
    {
        return $this->actionName;
    }
    /**
     * getPage
     *
     * @return void
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * isPost
     *
     * @return bool
     */
    public function isPost()
    {
        return  $this->method == "POST";
    }

    /**
     * isGet
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->method == "GET";
    }

    /**
     * isAjax
     *
     * @return bool
     */
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return $this->method == "Ajax";
        }
    }
}
