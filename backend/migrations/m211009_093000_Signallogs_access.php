<?php

use yii\db\Migration;

class m211009_093000_Signallogs_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_signallogs_index",
            "description" => "backend/signallogs/index"
        ],
        "view" => [
            "name" => "backend_signallogs_view",
            "description" => "backend/signallogs/view"
        ],
        "create" => [
            "name" => "backend_signallogs_create",
            "description" => "backend/signallogs/create"
        ],
        "update" => [
            "name" => "backend_signallogs_update",
            "description" => "backend/signallogs/update"
        ],
        "delete" => [
            "name" => "backend_signallogs_delete",
            "description" => "backend/signallogs/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendSignallogsFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendSignallogsView" => [
            "index",
            "view"
        ],
        "BackendSignallogsEdit" => [
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
