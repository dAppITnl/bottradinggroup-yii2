<?php

use yii\db\Migration;

class m211016_110900_Botsignal_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_botsignal_index",
            "description" => "backend/botsignal/index"
        ],
        "view" => [
            "name" => "backend_botsignal_view",
            "description" => "backend/botsignal/view"
        ],
        "create" => [
            "name" => "backend_botsignal_create",
            "description" => "backend/botsignal/create"
        ],
        "update" => [
            "name" => "backend_botsignal_update",
            "description" => "backend/botsignal/update"
        ],
        "delete" => [
            "name" => "backend_botsignal_delete",
            "description" => "backend/botsignal/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendBotsignalFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendBotsignalView" => [
            "index",
            "view"
        ],
        "BackendBotsignalEdit" => [
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
