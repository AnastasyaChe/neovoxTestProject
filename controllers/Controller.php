<?php


namespace app\controllers;

use app\base\Application;
use app\exceptions\NotFoundException;
use app\interfaces\RenderInterface;


/**
 * Controller
 */
abstract class Controller
{
    protected $defaultAction = 'index';
    protected $action;
    protected $useLayout = true;
    protected $layout = 'main';
    protected $renderer;
    protected $page;
    protected $session;

    /**
     * __construct
     *
     * @param  mixed $renderer
     * @param  mixed $page
     * @return void
     */
    public function __construct(RenderInterface $renderer, $page)
    {
        $this->renderer = $renderer;
        $this->page = +$page;
        $this->session = Application::getInstance()->session;
    }

    /**
     * runAction
     *
     * @param  mixed $action
     * @return void
     */
    public function runAction($action = null)
    {
        $this->action = $action ?: $this->defaultAction;
        $method = "action" . ucfirst($this->action);

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * render
     *
     * @param  mixed $template
     * @param  mixed $params
     * @return array
     */
    protected function render($template, $params = [])
    {
        $content = $this->renderer->render($template, $params);
        if ($this->useLayout) {
            return $this->renderer->render(
                "layouts/{$this->layout}",
                ['content' => $content]
            );
        }
        return $content;
    }

    /**
     * user_min_browser
     *
     * @param  mixed $agent
     * @return string
     */
    public function user_min_browser($agent)
    {
        preg_match("/(MSIE|Opera|Firefox|Chrome|Version)(?:\/| )([0-9.]+)/", $agent, $browser_info);
        list(, $browser, $version) = $browser_info;
        if ($browser == 'Opera' && $version == '9.80') return 'Opera ' . substr($agent, -5);
        if ($browser == 'Version') return 'Safari ' . $version;
        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko';
        return $browser . ' ' . $version;
    }
    /**
     * uploadfile
     *
     * @param  mixed $origin
     * @param  mixed $dest
     * @param  mixed $tmp_name
     * @return boolean
     */
    public function uploadfile($origin, $dest, $tmp_name)
    {
        $origin = strtolower(basename($origin));
        $fulldest = $dest . $origin;
        $filename = $origin;
        for ($i = 1; file_exists($fulldest); $i++) {
            $fileext = (strpos($origin, '.') === false ? '' : '.' . substr(strrchr($origin, "."), 1));
            $newfilename = substr($origin, 0, strlen($origin) - strlen($fileext)) . '[' . $i . ']' . $fileext;
            $fulldest = $dest . $newfilename;
        }

        if (move_uploaded_file($tmp_name, $fulldest)) {
            return $filename;
        }
        return false;
    }
}
