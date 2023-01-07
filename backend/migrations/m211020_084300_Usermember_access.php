<?php

use yii\db\Migration;

class m211020_084300_Usermember_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_usermember_index",
            "description" => "backend/usermember/index"
        ],
        "view" => [
            "name" => "backend_usermember_view",
            "description" => "backend/usermember/view"
        ],
        "create" => [
            "name" => "backend_usermember_create",
            "description" => "backend/usermember/create"
        ],
        "update" => [
            "name" => "backend_usermember_update",
            "description" => "backend/usermember/update"
        ],
        "delete" => [
            "name" => "backend_usermember_delete",
            "description" => "backend/usermember/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUsermemberFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUsermemberView" => [
            "index",
            "view"
        ],
        "BackendUsermemberEdit" => [
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
