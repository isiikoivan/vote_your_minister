<?php

namespace components;
use app\modules\Discovery\models\Route;
use yii\base\Component;
use yii\helpers\ArrayHelper;


class CrossHelperCopy extends Component
{

    public static function routes(){
        return ArrayHelper::map(Route::find()->all(),'route','route') ?? [];
    }

    public static function module(){
        $routes = Route::find()->all();
        $mappedRoutes = [];
        foreach ($routes as $route) {
            $parts = explode('/', $route->route);
            if (count($parts) == 2) {
                $mappedRoutes[$route->route] = $parts[0];
            } elseif (count($parts) >= 3) {
                $mappedRoutes[$route->route] = $parts[0] . '/' . $parts[1];
            } else {
                $mappedRoutes[$route->route] = $route->route;
            }
        }
        return array_unique(ArrayHelper::map($routes, function($model) use ($mappedRoutes){
            return $mappedRoutes[$model->route];
        }, function($model) use ($mappedRoutes) {
            return $mappedRoutes[$model->route];
        })) ?? [];
    }
    public static function modelNameResolver($model){
        $ref = new \ReflectionClass($model);

        $name = str_replace('Search', '', $ref->getShortName());
        $spacedEntityName = preg_replace('/(?<!^)[A-Z]/', ' $0', $name);
        $title = ucwords($spacedEntityName) . ' Report';
        $subtitle = 'List of Registered ' . strtolower($spacedEntityName) . 's';
        $filename = strtolower(str_replace(' ', '_', $spacedEntityName)) . '_report';

        return [
            'title'=>$title,
            'subtitle'=>$subtitle,
            'filename' => $filename
        ];
    }


}
