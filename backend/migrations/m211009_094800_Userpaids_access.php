<?php

use yii\db\Migration;

class m211009_094800_Userpaids_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_userpaids_index",
            "description" => "backend/userpaids/index"
        ],
        "view" => [
            "name" => "backend_userpaids_view",
            "description" => "backend/userpaids/view"
        ],
        "create" => [
            "name" => "backend_userpaids_create",
            "description" => "backend/userpaids/create"
        ],
        "update" => [
            "name" => "backend_userpaids_update",
            "description" => "backend/userpaids/update"
        ],
        "delete" => [
            "name" => "backend_userpaids_delete",
            "description" => "backend/userpaids/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUserpaidsFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUserpaidsView" => [
            "index",
            "view"
        ],
        "BackendUserpaidsEdit" => [
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
