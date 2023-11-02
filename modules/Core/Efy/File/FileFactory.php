<?php

namespace Modules\Core\Efy\File;

use Modules\Core\Efy\File\FileLocal\UploadFileLocal;
use Modules\Core\Efy\File\FileServer\UploadFileServer;

/**
 * File Factory
 * 
 * @author khuongtq
 */
class FileFactory
{
    /**
     * Lấy class để upload hoặc delete file
     */
    const UPLOAD_FILE = 1;

    /**
     * lấy class để view file, lấy url file hoặc lấy thông tin file
     */
    const VIEW_FILE = 2;

    /**
     * lấy class để view file từ Dịch vụ công
     */
    const VIEW_FILE_PORTAL = 3;

    public function __construct()
    {
    }

    /**
     * Khởi tạo File Server hay File Local
     * 
     * @param int $type Loại class muốn sử dụng
     * @param array $params Các thông tin về file
     * @return object
     */
    public static function load($type, $params)
    {
        if ($type === self::UPLOAD_FILE) {
            $config = config('file.UploadFile', 'FileLocal');
            if ($config['type'] == 'FileServer') {
                return new UploadFileServer($config, $params);
            } else {
                return new UploadFileLocal($config, $params);
            }
        } else if ($type === self::VIEW_FILE) {
            return new ViewFile($params);
        } else if ($type === self::VIEW_FILE_PORTAL) {
            return new ViewFilePortal($params);
        }

        return false;
    }

    /*
    |---------------------------------------------------------------------------
    | Các dữ liệu cần khi Upload file
    |---------------------------------------------------------------------------
    >> Upload FILE SERVER
        $params = [
            'filename'     => date('Y_m_d_His_') . time() . '!~!' . $_FILES["file"]["name"], Tên file <bắt buộc>
            'contentFile'  => file_get_contents($_FILES["file"]["tmp_name"]), Nội dung file <bắt buộc>
            'recordId'     => 'AAAAAAAA-AAAA-AAAA-AAAA-AAAAAAAAAAAA', ID hồ sơ (một cửa) hoặc ID user (Dvc) <bắt buộc>
            'rootDir'      => 'khuongtq', Thư mục gốc <bắt buộc>
            'codeTypeFile' => 'TL_KHAC', Mã tài liệu <không bắt buộc>
            'nameTypeFile' => 'Tài liệu khác', Tên tài liệu <không bắt buộc>
            'isOnegate'    => 1, Upload và cập nhật bảng files ở một cửa (1), cập nhật bảng t_files dịch vụ công (0) <không bắt buộc, mặc định là 1>
        ];
    >> Upload FILE LOCAL
        $params = [
            'rootDir' => 'khuongtq',
        ];
        - Nếu rootDir = '' thì sẽ lưu vào public/attach-file/$year/$month/$day
        - Nếu rootDir != '' thì sẽ lưu vào public/attach-file/$rootDir/$year/$month/$day



    |---------------------------------------------------------------------------
    | Các dữ liệu cần khi View file
    |---------------------------------------------------------------------------
    >> View FILE SERVER
        $params = [
            'filename' => '2023_04_27_141809_1682579889!~!c4611_sample_explain.pdf', Tên file <bắt buộc>
        ];
    >> View FILE LOCAL
        $params = [
            'rootDir' => 'khuongtq', Thư mục gốc <không bắt buộc>
            'filename' => '2023_04_27_151857_1682583537!~!HaNoi.pdf', Tên file <bắt buộc>
        ];
        - Nếu rootDir = '' thì sẽ lấy từ public/attach-file/$year/$month/$day
        - Nếu rootDir != '' thì sẽ lấy từ public/attach-file/$rootDir/$year/$month/$day
    >> View FILE PORTAL
        $params = '2023_04_27_151857_1682583537!~!HaNoi.pdf'; Tên file <bắt buộc>


    |---------------------------------------------------------------------------
    | Các dữ liệu cần khi Delete file
    |---------------------------------------------------------------------------
    >> Delete FILE SERVER
        $params = [
            'streamId' => '119A9510-0714-4671-8DCB-64BC6F48BFF0', Stream id trong FileTableTb <bắt buộc>
        ];
    >> Delete FILE LOCAL
        $params = [
            'rootDir' => 'khuongtq', Thư mục gốc <không bắt buộc>
            'filename' => '2023_04_27_151857_1682583537!~!HaNoi.pdf', Tên file <bắt buộc>
        ];
        - Nếu rootDir = '' thì sẽ lấy từ public/attach-file/$year/$month/$day
        - Nếu rootDir != '' thì sẽ lấy từ public/attach-file/$rootDir/$year/$month/$day
    */
}
