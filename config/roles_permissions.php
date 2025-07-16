<?php
return [
    'roles' => [
        'owner' => 'Owner Role',
        'admin' => 'Administrator Role',
    ],
    'permissions' => [
        'manage_users' => 'Manage Users',
        'manage_products' => 'Manage Products',
        'view_horizon' => 'View Horizon Dashboard',
        'manage_orders' => 'Manage Orders',
    ],
    'role_permissions' => [
        'owner' => ['manage_users', 'manage_shops', 'manage_products', 'view_horizon', 'manage_settings', 'manage_orders'],
        'admin' => ['manage_products', 'manage_settings', 'manage_orders'],
    ],
];