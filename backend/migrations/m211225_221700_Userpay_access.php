<?php

use yii\db\Migration;

class m211225_221700_Userpay_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_userpay_index",
            "description" => "backend/userpay/index"
        ],
        "view" => [
            "name" => "backend_userpay_view",
            "description" => "backend/userpay/view"
        ],
        "create" => [
            "name" => "backend_userpay_create",
            "description" => "backend/userpay/create"
        ],
        "update" => [
            "name" => "backend_userpay_update",
            "description" => "backend/userpay/update"
        ],
        "delete" => [
            "name" => "backend_userpay_delete",
            "description" => "backend/userpay/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUserpayFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUserpayView" => [
            "index",
            "view"
        ],
        "BackendUserpayEdit" => [
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
