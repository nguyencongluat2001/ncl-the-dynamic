<?php

return [
    'home' => [
        'name' => 'Trang chủ',
        'icon' => 'fa fa-user-circle',
        'child' => false,
        'check_permision' => 'ADMIN'
    ],
    'user' => [
        'name' => 'Quản trị người dùng',
        'icon' => 'fa fa-user-circle',
        'child' => false,
        'check_permision' => 'ADMIN'
    ],
    'category' => [
        'name' => 'Quản trị danh mục',
        'icon' => 'fa fa-list',
        'check_permision' => 'ADMIN',
        'child' => [
            'listtype' => [
                'name' => 'Loại danh mục ',
                'icon' => 'fa fa-angle-double-right',
            ],
            'list' => [
                'name' => 'Danh mục đối tượng',
                'icon' => 'fa fa-angle-double-right',
            ]
        ]
    ],
    'product' => [
        'name' => 'Quản trị câu hỏi',
        'icon' => 'fas fa-project-diagram',
        'check_permision' => 'ADMIN',
        // 'child' => [
        //     'examinations' => [
        //         'name' => 'Đợt thi',
        //         'icon' => 'fa fa-angle-double-right',
        //     ],
        // ]
        ],
    'objects' => [
        'name' => 'Quản trị đối tượng',
        'icon' => 'fas fa-user-tag',
        'check_permision' => 'ADMIN',
        // 'child' => [
        //     'objects' => [
        //         'name' => 'Đối tượng',
        //         'icon' => 'fa fa-angle-double-right',
        //     ],
        // ]
        ],
    'sql' => [
        'name' => 'Quản trị Bài thi',
        'icon' => 'fas fa-address-card',
        'check_permision' => 'ADMIN',
        // 'child' => [
        //     'exams' => [
        //         'name' => 'Bài thi',
        //         'icon' => 'fa fa-angle-double-right',
        //     ],
        // ]
        ],
        'dashboard' => [
            'name' => 'Quản trị Bài thi',
            'icon' => 'fas fa-address-card',
            'check_permision' => 'ADMIN',
            // 'child' => [
            //     'exams' => [
            //         'name' => 'Bài thi',
            //         'icon' => 'fa fa-angle-double-right',
            //     ],
            // ]
        ]
];
