<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\BangCapRepository;
use Modules\Base\Library;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BangCapService extends Service
{

    private $bangCapRepository;
    private $baseDis;
    public function __construct(
        BangCapRepository $BangCapRepository
    ) {
        parent::__construct();
        $this->bangCapRepository = $BangCapRepository;
        $this->baseDis           = public_path("file-image-client/bangcap") . "/";
    }

    public function repository()
    {
        return BangCapRepository::class;
    }

    /**
     * cập nhật người dùng
     */
    public function store($input, $file)
    {
        try {
            $rules = [
                'name'                => 'required|string|max:255',
                'email'               => 'required|email|max:255',
                'phone'               => 'required|string|max:255',
                'dateOfBirth'         => 'required|date',
                'school'              => 'required|string|max:255',
                'address'             => 'required|string|max:255',
                'industry'            => 'required',
                'graduate_time'       => 'required',
                'level'               => 'required',
                'permanent_residence' => 'required',
                'identity'            => 'required',
                'identity_time'       => 'required',
                'identity_address'    => 'required',
                'sex'                 => 'required|in:male,female',
            ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => $validator->errors()->first()
                ];
            }

            $arrData = [
                'id'                  => (string)Str::uuid(),
                'code_category'       => $input['code_category'],
                'name'                => $input['name'],
                'email'               => $input['email'],
                'phone'               => $input['phone'],
                'date_of_birth'       => $input['dateOfBirth'],
                'school'              => $input['school'],
                'industry'            => $input['industry'],
                'graduate_time'       => $input['graduate_time'],
                'level'               => $input['level'],
                'permanent_residence' => $input['permanent_residence'],
                'identity'            => $input['identity'],
                'identity_time'       => $input['identity_time'],
                'identity_address'    => $input['identity_address'],
                'sex'                 => $input['sex'],
                'address'             => $input['address'],
                'trang_thai'          => 1,
            ];

            if (isset($file) && $file != []) {
                $arrFile = $this->uploadFile($input, $file);
                if (isset($arrFile['image'])) {
                    $arrData['image'] = $arrFile['image'];
                }
                if (isset($arrFile['image_transfer'])) {
                    $arrData['image_transfer'] = $arrFile['image_transfer'];
                }
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
    public function uploadFile($input, $files)
    {
        $path = $this->baseDis;
        $arrFileNames = [];

        // Xử lý file avatar
        if (isset($files['image'])) {
            $avatar = $files['image'];
            $avatarName = $this->_handleFileUpload($avatar, $path);
            $arrFileNames['image'] = $avatarName;
        }

        // Xử lý file chuyển khoản
        if (isset($files['image_transfer'])) {
            $transfer = $files['image_transfer'];
            $transferName = $this->_handleFileUpload($transfer, $path);
            $arrFileNames['image_transfer'] = $transferName;
        }

        return $arrFileNames;
    }

    protected function _handleFileUpload($file, $path)
    {
        $fileName = $file['name'];
        $random = Library::_get_randon_number();
        $fileName = Library::_replaceBadChar($fileName);
        $fileName = Library::_convertVNtoEN($fileName);
        $sFullFileName = date("Y") . '_' . date("m") . '_' . date("d") . "_" . date("H") . date("i") . date("s") . $random . "!~!" . $fileName;
        move_uploaded_file($file['tmp_name'], $path . $sFullFileName);
        return $sFullFileName;
    }
}
