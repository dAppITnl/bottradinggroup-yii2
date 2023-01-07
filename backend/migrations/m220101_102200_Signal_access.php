<?php

use yii\db\Migration;

class m220101_102200_Signal_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_signal_index",
            "description" => "backend/signal/index"
        ],
        "view" => [
            "name" => "backend_signal_view",
            "description" => "backend/signal/view"
        ],
        "create" => [
            "name" => "backend_signal_create",
            "description" => "backend/signal/create"
        ],
        "update" => [
            "name" => "backend_signal_update",
            "description" => "backend/signal/update"
        ],
        "delete" => [
            "name" => "backend_signal_delete",
            "description" => "backend/signal/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendSignalFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendSignalView" => [
            "index",
            "view"
        ],
        "BackendSignalEdit" => [
            "update",
            "create",
            "delete"
        ]
    ];
    
    public function up()
    {
        
        $permisions = [];
        $auth = \Yii::$app->authManager;

        /**
         * create permisions for each controller action
         */
        foreach ($this->permisions as $action => $permission) {
            $permisions[$action] = $auth->createPermission($permission['name']);
            $permisions[$action]->description = $permission['description'];
            $auth->add($permisions[$action]);
        }

        /**
         *  create roles
         */
        foreach ($this->roles as $roleName => $actions) {
            $role = $auth->createRole($roleName);
            $auth->add($role);

            /**
             *  to role assign permissions
             */
            foreach ($actions as $action) {
                $auth->addChild($role, $permisions[$action]);
            }
        }
    }

    public function down() {
        $auth = Yii::$app->authManager;

        foreach ($this->roles as $roleName => $actions) {
            $role = $auth->createRole($roleName);
            $auth->remove($role);
        }

        foreach ($this->permisions as $permission) {
            $authItem = $auth->createPermission($permission['name']);
            $auth->remove($authItem);
        }
    }
}
