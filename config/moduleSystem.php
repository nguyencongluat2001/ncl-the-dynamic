<?php

return [
    'cms' => [
        'name' => 'Quản trị cms',
        'icon' => 'fas fa-clipboard-list',
        'check_permision' => 'ADMIN_SYSTEM',
        'child' => [
            'categories' => [
                'name' => 'Quản trị chuyên mục',
                'controller' => 'CategoriesController',
                'icon' => 'fas fa-angle-double-right',
            ],
            'articles' => [
                'name' => 'Quản trị bài viết',
                'controller' => 'CmsArticlesController',
                'icon' => 'fas fa-angle-double-right',
            ],
        ],
    ],
    'users' => [
        'name' => 'Quản trị người dùng',
        'icon' => 'fa fa-user-circle',
        'child' => false,
        'check_permision' => 'ADMIN_SYSTEM,ADMIN_OWNER'
    ],
    'listtype' => [
        'name' => 'Quản trị danh mục',
        'icon' => 'fa fa-list',
        'check_permision' => 'ADMIN_SYSTEM',
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
    'examinations' => [
        'name' => 'Quản trị câu hỏi',
        'icon' => 'fas fa-project-diagram',
        'check_permision' => 'ADMIN_SYSTEM',
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
        'check_permision' => 'ADMIN_SYSTEM',
        // 'child' => [
        //     'objects' => [
        //         'name' => 'Đối tượng',
        //         'icon' => 'fa fa-angle-double-right',
        //     ],
        // ]
        ],
    'exams' => [
        'name' => 'Quản trị Bài thi',
        'icon' => 'fas fa-address-card',
        'check_permision' => 'ADMIN_SYSTEM',
        // 'child' => [
        //     'exams' => [
        //         'name' => 'Bài thi',
        //         'icon' => 'fa fa-angle-double-right',
        //     ],
        // ]
    ]
];
