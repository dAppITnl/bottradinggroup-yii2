<?php

use yii\db\Migration;

class m211011_152500_Bot_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_bot_index",
            "description" => "backend/bot/index"
        ],
        "view" => [
            "name" => "backend_bot_view",
            "description" => "backend/bot/view"
        ],
        "create" => [
            "name" => "backend_bot_create",
            "description" => "backend/bot/create"
        ],
        "update" => [
            "name" => "backend_bot_update",
            "description" => "backend/bot/update"
        ],
        "delete" => [
            "name" => "backend_bot_delete",
            "description" => "backend/bot/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendBotFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendBotView" => [
            "index",
            "view"
        ],
        "BackendBotEdit" => [
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
