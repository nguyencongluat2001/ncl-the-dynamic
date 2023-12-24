<?php

return [
  "ADMIN" => [
    'home' => [
        'name' => 'Trang Chủ',
        'icon' => 'fas fa-home',
        'a'    => 'nav-link link-home',
        'href' => '/system/home/index',
    ],
    'users' => [
        'name' => 'Quản trị người dùng',
        'icon' => 'fas fa-users',
        'a'    => 'nav-link link-user',
        'href' => '/system/user/index',
    ],
    'category' => [
        'name' => 'Quản trị danh mục',
        'icon' => 'far fa-calendar-alt',
        'a'    => 'nav-link link-category',
        'href' => '/system/category/index',
    ],
    'login' => [
        'name' => 'Bệnh viện, phòng khám',
        'icon' => 'fas fa-university',
        'a'    => 'nav-link link-hospital',
        'href' => '/system/hospital/index',
    ],
    'blog' => [
        'name' => 'Quản trị bài viết',
        'icon' => 'far fa-calendar-alt',
        'a'    => 'nav-link link-blog',
        'href' => '/system/blog/index',
    ],
    'product' => [
        'name' => 'Quản trị Search',
        'icon' => 'fas fa-medkit',
        'a'    => 'nav-link link-urlsearch',
        'href' => '/system/sql/index',
    ],
    'sql' => [
        'name' => 'Quản trị DATA',
        'icon' => 'fas fa-hand-holding-usd',
        'a'    => 'nav-link link-sql',
        'href' => '/system/sql/index',
    ],
   ],
];
