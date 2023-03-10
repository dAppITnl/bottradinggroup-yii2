<?php

use yii\db\Migration;

class m220130_144900_Cryptoaddress_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_cryptoaddress_index",
            "description" => "backend/cryptoaddress/index"
        ],
        "view" => [
            "name" => "backend_cryptoaddress_view",
            "description" => "backend/cryptoaddress/view"
        ],
        "create" => [
            "name" => "backend_cryptoaddress_create",
            "description" => "backend/cryptoaddress/create"
        ],
        "update" => [
            "name" => "backend_cryptoaddress_update",
            "description" => "backend/cryptoaddress/update"
        ],
        "delete" => [
            "name" => "backend_cryptoaddress_delete",
            "description" => "backend/cryptoaddress/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendCryptoaddressFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendCryptoaddressView" => [
            "index",
            "view"
        ],
        "BackendCryptoaddressEdit" => [
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
