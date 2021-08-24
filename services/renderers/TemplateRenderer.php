<?php


namespace app\services\renderers;

use app\base\Application;
use app\interfaces\RenderInterface;

class TemplateRenderer implements RenderInterface
{

    public function render($template, $params = []) {
        ob_start();
        $templatePath = Application::getInstance()->getConfig()['views_dir'] . $template . ".php";
        extract($params);
        include $templatePath;
        return ob_get_clean();
    }
}