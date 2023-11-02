<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CÁC LAYOUT TRONG HỆ THỐNG
    |--------------------------------------------------------------------------
    |
    | Mỗi layout tương đương một ứng dụng độc lập
    | VD: ecs: Phần mềm một cửa điện tử
    |
    */

    'layouts' => [
        '/' => 'Frontend',     // Trang web
        'system'   => 'System',     // Quản trị hệ thống
        'api'      => 'Api',
    ],

    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH NGÀY NGHỈ LỄ TẾT
    |--------------------------------------------------------------------------
    |
    | Mặc định những ngày được nghỉ trong năm (theo dương lịch)
    | Các ngày còn lại thay đổi âm lịch thì cấu hình theo năm
    | Sử dụng định dạng này tháng: Y/m/d.
    |
    */

    'listHolidays' => [
        // Các ngày nghỉ lễ mặc định dương lịch
        'defaultSolar' => [
            '01/01', // Tết dương lịch
            '02/01',
            '03/01',
            '30/04', // Ngày giải phóng miền nam
            '01/05', // Ngày quốc tế lao động
            '02/09', '03/09', // Quốc khách
        ],
        // Các ngày nghỉ lễ mặc định âm lịch
        'defaultLular' => [
            // không dùng lịch âm
            // '01/01', '02/01', '03/01', '04/01', '05/01', // Tết âm lịch
            // '10/03', // Giỗ tổ Hùng vương
        ],
        // Các ngày nghỉ bù, nghỉ thêm theo từng năm (nhập theo ngày dương lịch, nếu trùng T7, CN thì bỏ)
        '2023' => ['01/01', '02/01', '02/09', '30/04', '01/05', '20/01', '23/01', '24/01', '25/01', '26/01', '01/05', '02/05', '03/05'],
    ],

    /*
    |--------------------------------------------------------------------------
    | CẤU HÌNH QUY TRÌNH WORKFLOW
    |--------------------------------------------------------------------------
    |
    | Đối với những trường hợp quy trình đặc biệt
    | Các etable có quy trình giống nhau
    |
    */
    'workFlow' => [],

    'passDefault'       => 'hN7&DZT^ku$t',
    'passDefaultSystem' => 'I#%6yD#4tT%qEbTptz5B',
    'default_uuid'      => 'AAAAAAAA-AAAA-AAAA-AAAA-AAAAAAAAAAAA',
];
