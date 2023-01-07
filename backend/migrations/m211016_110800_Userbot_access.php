<?php

use yii\db\Migration;

class m211016_110800_Userbot_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_userbot_index",
            "description" => "backend/userbot/index"
        ],
        "view" => [
            "name" => "backend_userbot_view",
            "description" => "backend/userbot/view"
        ],
        "create" => [
            "name" => "backend_userbot_create",
            "description" => "backend/userbot/create"
        ],
        "update" => [
            "name" => "backend_userbot_update",
            "description" => "backend/userbot/update"
        ],
        "delete" => [
            "name" => "backend_userbot_delete",
            "description" => "backend/userbot/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendUserbotFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendUserbotView" => [
            "index",
            "view"
        ],
        "BackendUserbotEdit" => [
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
