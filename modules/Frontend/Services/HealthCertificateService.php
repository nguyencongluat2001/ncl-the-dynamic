<?php

namespace Modules\Frontend\Services;

use Modules\Base\Service;
use Modules\Core\Ncl\Date\DateHelper;
use Modules\Frontend\Repositories\HealthRepository;
use Modules\Frontend\Services\Admin\ListService;
use Modules\Base\Library;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * HealthCertificateService
 *
 *
 */
class HealthCertificateService extends Service
{
    private $baseDis;
    public function repository()
    {
        return HealthRepository::class;
    }

    public function __construct()
    {
        parent::__construct();
        $this->baseDis = public_path("file-image-client/giaykham") . "/";
    }

    /**
     * cập nhật người dùng
     */
    public function store($input, $file)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'dateOfBirth' => 'required|date',
                'address' => 'required|string|max:255',
                'history' => 'required|string',
                'weighed' => 'required|numeric',
                'height' => 'required|numeric',
                'sex' => 'required|in:male,female',
            ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => $validator->errors()->first()
                ];
            }

            $image_old = null;
            if (isset($file) && $file != []) {
                $arrFile = $this->uploadFile($input, $file, $image_old);
            }
            $arrData = [
                'id' => (string)Str::uuid(),
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'date_of_birth' => $input['dateOfBirth'],
                'address' => $input['address'],
                'history_of_pathology' => $input['history'],
                'weighed' => $input['weighed'],
                'height' => $input['height'],
                'sex' => $input['sex'],
                'text' => $input['text'],
                'trang_thai' => 1,
            ];
            // nếu có ảnh mới thì cập nhật
            if (!empty($arrFile)) {
                $arrData['image'] = $arrFile;
            }
            $create = $this->repository->create($arrData);
            return array('success' => true, 'message' => 'Thêm mới thành công');
        } catch (\Exception $e) {
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }
    /**
     * Tải file vào thư mục
     */
    public function uploadFile($input, $file, $image_old)
    {
        $path = $this->baseDis;
        $old_path = $path . $image_old;
        if (file_exists($old_path)) {
            @unlink($old_path);
        }
        $fileName = $_FILES['file-attack-0']['name'];
        $random = Library::_get_randon_number();
        $fileName = Library::_replaceBadChar($fileName);
        $fileName = Library::_convertVNtoEN($fileName);
        $sFullFileName = date("Y") . '_' . date("m") . '_' . date("d") . "_" . date("H") . date("i") . date("u") . $random . "!~!" . $fileName;
        move_uploaded_file($_FILES['file-attack-0']['tmp_name'], $path . $sFullFileName);
        return $sFullFileName;
    }
}
