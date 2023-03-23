<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class RouterInfo
{
    private $router;
    private $parameterBag;

    public function __construct(RouterInterface $router, ParameterBagInterface $parameterBag)
    {
        $this->router = $router;
        $this->parameterBag = $parameterBag;
    }

    public function getRoleByRouteName($routeName, $moduleName, $child = null)
    {
        try {
            $collection = $this->router->getRouteCollection();
            $routeCollection  = $collection->get($routeName);
            $defaults  = $routeCollection->getDefaults();
            $controller = $defaults['_controller'] ?? '';
            if ($controller) {
                $controllers = $this->parameterBag->get("{$moduleName}.controllers");
                [$class, $method] = explode('::', $controller);
                $class = str_replace('Controller', '', class_basename($class));
                
                foreach ($controllers as $controllerMap) {
    
                    if ($controllerMap['name'] == $class) {
                        foreach ($controllerMap['methods'] as $controllerMethod) {
                            [$realMethod, $tmpRole] = explode('@', $controllerMethod);
                            
                            if ($method == $realMethod) {
                                
                                $roleName = $tmpRole . '_' . strtoupper($moduleName) . '_' . strtoupper($class);
                                if ($child) {
                                    $roleName .= '_' . strtoupper($child);
                                }
                                return $roleName;
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {

        }
       
    }
}
