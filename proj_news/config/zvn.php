<?php

return [
    'url' => [
        'prefix_admin' => 'admin',
        'prefix_news' => 'news'
    ],
    'format' => [
        'long_time' => 'H:m:s d/m/Y',
        'short_time' => 'd/m/Y'
    ],
    'template' => [
        'status' => [
            'all' => ['name' => 'Tất cả phần tử', 'class' => 'btn-success'],
            'active' => ['name' => 'Kích hoạt', 'class' => 'btn-success'],
            'inactive' => ['name' => 'Chưa kích hoạt', 'class' => 'btn-info'],
            'block' => ['name' => 'Bị khóa', 'class' => 'btn-danger'],
            'default' => ['name' => 'Chưa xác định', 'class' => 'btn-info'],
        ],
        'search' => [
            'all' => ['name' => 'Search by All'],
            'id' => ['name' => 'Search by ID'],
            'name' => ['name' => 'Search by Name'],
            'username' => ['name' => 'Search by UserName'],
            'fullname' => ['name' => 'Search by FullName'],
            'email' => ['name' => 'Search by Email'],
            'description' => ['name' => 'Search by Description'],
            'link' => ['name' => 'Search by Link'],
            'content' => ['name' => 'Search by Content'],
        ],
        'button' => [
            'edit'   => ['class' => 'btn-success', 'title' => 'Edit', 'icon' => 'fa-pencil', 'route-name' => '/form'],
            'delete' => ['class' => 'btn-danger', 'title' => 'Delete', 'icon' => 'fa-trash', 'route-name' => '/delete'],
            'info'   => ['class' => 'btn-info', 'title' => 'View', 'icon' => 'fa-pencil', 'route-name' => '/delete']
        ]
    ],
    'config' => [
        'search' => [
            'default' => ['all', 'id', 'fullname'],
            'slider' => ['all', 'description', 'link']
        ],
        'button' => [
            'default' => ['edit', 'delete'],
            'slider'  =>  ['edit', 'delete']
        ]
    ]
];