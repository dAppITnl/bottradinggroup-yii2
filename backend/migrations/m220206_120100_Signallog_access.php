<?php

use yii\db\Migration;

class m220206_120100_Signallog_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_signallog_index",
            "description" => "backend/signallog/index"
        ],
        "view" => [
            "name" => "backend_signallog_view",
            "description" => "backend/signallog/view"
        ],
        "create" => [
            "name" => "backend_signallog_create",
            "description" => "backend/signallog/create"
        ],
        "update" => [
            "name" => "backend_signallog_update",
            "description" => "backend/signallog/update"
        ],
        "delete" => [
            "name" => "backend_signallog_delete",
            "description" => "backend/signallog/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendSignallogFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendSignallogView" => [
            "index",
            "view"
        ],
        "BackendSignallogEdit" => [
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
