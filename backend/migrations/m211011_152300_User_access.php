<?php

use yii\db\Migration;

class m211011_152300_User_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_user_index",
            "description" => "backend/user/index"
        ],
        "view" => [
            "name" => "backend_user_view",
            "description" => "backend/user/view"
        ],
        "create" => [
            "name" => "backend_user_create",
            "description" => "backend/user/create"
        ],
        "update" => [
            "name" => "backend_user_update",
            "description" => "backend/user/update"
        ],
        "delete" => [
            "name" => "backend_user_delete",
            "description" => "backend/user/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUserFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUserView" => [
            "index",
            "view"
        ],
        "BackendUserEdit" => [
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
