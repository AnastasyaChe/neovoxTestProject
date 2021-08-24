<?php


namespace app\controllers;

use app\base\Application;
use app\exceptions\NotFoundException;
use app\interfaces\RenderInterface;
use mysqli;

abstract class Controller
{
    protected $defaultAction = 'index';
    protected $action;
    protected $useLayout = true;
    protected $layout = 'main';
    protected $renderer;
    protected $page;
    protected $session;
        
    public function __construct(RenderInterface $renderer, $page)
    {
        $this->renderer = $renderer;
        $this->page = +$page;
        $this->session = Application::getInstance()->session;

    }

    public function runAction($action = null)
    {
        $this->action = $action ?: $this->defaultAction;
        $method = "action" . ucfirst($this->action);

        if(method_exists($this, $method)) {
            $this->$method();
        } else {
            throw new NotFoundException("Метод не найден");
        }
    }

    protected function render($template, $params = []) {
        $content = $this->renderer->render($template, $params);
        if($this->useLayout) {
            return $this->renderer->render(
                "layouts/{$this->layout}",
                ['content' => $content]
            );
        }
        return $content;
    }

    public function user_min_browser($agent) {
        preg_match("/(MSIE|Opera|Firefox|Chrome|Version)(?:\/| )([0-9.]+)/", $agent, $browser_info);
        list(,$browser,$version) = $browser_info;
        if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5);
        if ($browser == 'Version') return 'Safari '.$version;
        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko';
        return $browser.' '.$version;
    }



}