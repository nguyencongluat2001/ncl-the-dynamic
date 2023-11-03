<?php

/*
|-------------------------------------------------------------------------------
| Custom Global Function
|-------------------------------------------------------------------------------
| created at 16/08/2023
| by khuongtq
|
*/

/**
 * ## Chuỗi sql đầy đủ (có cả value của mệnh đề select, where, order ...)
 * 
 * @param object $query
 * @return string
 * @author khuongtq
 */
function toSqlString(object $query): string
{
    $queryNoValue = $query->toSql();
    $bindings     = $query->getBindings();
    $queryNoValue = str_replace('%', '&', $queryNoValue);
    $sql          = str_replace('?', "'%s'", $queryNoValue);
    $sql          = sprintf($sql, ...$bindings);
    $sql          = str_replace('&', '%', $sql);
    $sql          = str_replace("\r\n", '', $sql);

    return $sql;
}

/**
 * ## Chuyển object thành mảng
 * 
 * @param mixed $data
 * @return array
 * @author khuongtq
 */
function toArray(mixed $data): array
{
    return @json_decode(json_encode($data), true);
}

/**
 * Kiểm tra chuỗi có đúng định dạng JSON không
 * 
 * @param string $str
 * @return bool
 * @author khuongtq
 */
function is_json(mixed $str): bool
{
    if (!empty($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    return false;
}

/**
 * Tạo chuỗi ngẫu nhiên
 * 
 * @param int $length Độ dài chuỗi
 * @return string
 * @author khuongtq
 */
function randomString(int $length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
