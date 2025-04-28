<?php

namespace components;
use app\models\Faculties;
use app\models\User;
use app\modules\Accounts\models\AccChartOfAccounts;
use app\modules\Discovery\models\Route;
use Yii;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class CrossHelper extends Component
{

    public static function university(){
        return Yii::$app->user->identity->university_id;
    }

    public static function schoolId()
    {
        return yii::$app->user->identity->campus_id ?? 0;
    }


    public static function salutation(){
        return [
            'Mr.' => 'Mr',
            'Mrs.' => 'Mrs',
            'Ms.' => 'Ms',
            'Dr.' => 'Dr',
            'Prof.' =>'Prof',
            'Fr.' => 'Fr',
            'Rev.' => 'Rev',
            'Sr.' => 'Sr',
        ];
    }

    public static function StaffStatus(){
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Suspended' => 'Suspended',
            'Resigned' => 'Resigned',
            'Retired' => 'Retired',
            'Terminated' => 'Terminated',
            'Deceased' => 'Deceased',
            'Absconded' => 'Absconded',
            'On Leave' => 'On Leave',
            'On Probation' => 'On Probation',
            'On Training' => 'On Training',
            'On Study Leave' => 'On Study Leave',
            'On Maternity Leave' => 'On Maternity Leave',
        ];
    }

    public static function employmentType(){
        return [
            'Contract' => 'Contract',
            'Internship' => 'Internship',
            'Volunteer' => 'Volunteer',
            'Temporary' => 'Temporary',
            'Permanent' => 'Permanent',
            'Probationary' => 'Probationary',
            'Tenured' => 'Tenured',
            'Tenure-track' => 'Tenure-track',
            'Non-tenure track' => 'Non-tenure track',
            'Adjunct' => 'Adjunct',
            'Visiting' => 'Visiting',
            'Emeritus' => 'Emeritus',
            'Sabbatical' => 'Sabbatical',
            'Secondment' => 'Secondment',
            'Contingent' => 'Contingent'
        ];
    }

    public static function routes(){
        return ArrayHelper::map(Route::find()->all(),'route','route') ?? [];
    }

        public static function isLogedin(){
                if(Yii::$app->user->isGuest ) {
                    return yii::$app->user->loginRequired();
                }
                return true;
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
            return array_unique(ArrayHelper::map($routes,
                function($model) use ($mappedRoutes)
                {
                return $mappedRoutes[$model->route];
                }
            , function($model) use ($mappedRoutes) {
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

    public static function initializeAccounts(){
      try {
          if(!Yii::$app->user->isGuest){
              $charts = AccChartOfAccounts::find()->count();
              if($charts === 0 && self::schoolId() !== 0){
                  Yii::$app->db->createCommand("select * from acc_reset_accounting_module(:campus);")
                      ->bindValue(':campus',self::schoolId())
                      ->execute();
              }
          }

          return true;
      }catch (\Exception $exception){
          return false;
      }
    }




}
