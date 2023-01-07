<?php

use yii\db\Migration;

class m211011_152600_Usersignal_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_usersignal_index",
            "description" => "backend/usersignal/index"
        ],
        "view" => [
            "name" => "backend_usersignal_view",
            "description" => "backend/usersignal/view"
        ],
        "create" => [
            "name" => "backend_usersignal_create",
            "description" => "backend/usersignal/create"
        ],
        "update" => [
            "name" => "backend_usersignal_update",
            "description" => "backend/usersignal/update"
        ],
        "delete" => [
            "name" => "backend_usersignal_delete",
            "description" => "backend/usersignal/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUsersignalFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUsersignalView" => [
            "index",
            "view"
        ],
        "BackendUsersignalEdit" => [
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
