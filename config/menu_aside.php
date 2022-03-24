<?php
// Aside menu
return [
    'items' => [
        // Dashboard
        [
            'title' => 'Dashboard',
            'root' => true,
            'icon' => 'media/svg/icons/Design/Layers.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/admin',
            'new-tab' => false,
        ],

        // Custom
        [
            'section' => 'Nội dung website',
        ],
        [
            'title' => 'Quản lí bài viết',
            'icon' => 'media/svg/icons/Communication/Clipboard-list.svg',
            'bullet' => 'line',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Tất cả bài viết',
                    'bullet' => 'dot',
                    'page'=>'/admin/article',
                ],
                [
                    'title' => 'Danh mục bài viết',
                    'bullet' => 'dot',
                    'page'=>'/admin/article-category',
                ],
                [
                    'title' => 'Nhóm bài viết',
                    'bullet' => 'dot',
                    'page'=>'/admin/article-group',
                ],
            ]
        ],
        [
            'section' => 'Cấu hình',
        ],
        [
            'title' => 'Cấu hình chung',
            'root' => true,
            'icon' => 'media/svg/icons/General/Settings-1.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/admin/setting',
            'new-tab' => false,
        ],
    ]

];
