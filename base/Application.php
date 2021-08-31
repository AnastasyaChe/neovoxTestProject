<?php


namespace app\base;

use app\traits\SingletonTrait;

class Application
{
    use SingletonTrait;

    protected $config;
    protected $componentsFactory;
    protected $components;

    /**
     * run
     *
     * @param  mixed $config
     * @return void
     */
    public function run(array $config)
    {
        $this->componentsFactory = new ComponentsFactory();
        $this->config = $config;
        $controllerName = $this->request->getControllerName() ?: $this->config['default_controller'];
        $actionName = $this->request->getActionName();
        $page = $this->request->getPage() ?: $this->config['default_page'];



        $controllerClass = $this->config['controller_namespace'] . ucfirst($controllerName) . "Controller";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass($this->renderer, $page);

            $controller->runAction($actionName);
        }
    }

    /**
     * __get
     *
     * @param  mixed $name
     * @return string
     */
    public function __get($name)
    {
        if (is_null($this->components[$name])) {
            if ($params = $this->config['components'][$name]) {
                $this->components[$name] = $this->componentsFactory
                    ->createComponent($name, $params);
            } else {
                throw new \Exception("Не найдена конфигурация для компонента {$name}");
            }
        }
        return $this->components[$name];
    }


    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
