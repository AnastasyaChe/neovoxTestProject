<?php


namespace app\base;


/**
 * ComponentsFactory
 */
class ComponentsFactory
{
    /**
     * createComponent
     *
     * @param  mixed $name
     * @param  mixed $params
     * @return object
     */
    public function createComponent($name, $params = [])
    {
        $class = $params['class'];
        if (class_exists($class)) {
            unset($params['class']);
            $reflection = new \ReflectionClass($class);
            return $reflection->newInstanceArgs($params);
        }
        throw new \Exception("Не найден класс компонента");
    }
}
