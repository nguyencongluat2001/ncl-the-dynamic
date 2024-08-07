<?php

namespace Modules\Frontend\Controllers;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Frontend\Services\AuthService;
use Modules\Frontend\Services\SubjectService;
use Modules\Core\Ncl\Library;
use Illuminate\Support\Facades\Auth;
use Modules\Frontend\Services\ExamService;
use Modules\Frontend\Models\UnitsModel;
use DB;
use Illuminate\Support\Facades\Http;
use Str;

/**
 * Controller đăng nhập, đăng ký ở Cổng
 * 
 * @author luatnc
 */
class AuthController
{
    private $authService;
    private $subjectService;
    private $examService;

    public function __construct(
        AuthService $a,
        SubjectService $s,
        ExamService $e
    ) {
        $this->authService = $a;
        $this->subjectService = $s;
        $this->examService = $e;
    }

    /**
     * View đăng nhập
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getSignIn(Request $request): View
    {
        return view('Frontend::auth.sign-in');
    }
       /**
     * login  file kết quả
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function signIn(Request $request)
    { 
        $arrInput = $request->input();
            $param = [
                'username'=> $arrInput['username'],
                // $arrInput['pwd']
                'pwd'=> $arrInput['password']
            ];
            $response = Http::withBody(json_encode($param),'application/json')->post('118.70.182.89:89/api/PACS/login');
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            if($response['status'] == true){
                $_SESSION["username"] = $response['loginModel']['username'];
                $_SESSION["pwd"] = $param['pwd'];
                $_SESSION["accessionnumber"] = -1;
                return $response;

            }
    } 
       /**
     * dang xuat
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function logout(Request $request)
    { 
        if(!empty($_SESSION['username'])){
            session_destroy();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('Frontend::auth.sign-in');
    } 
    public function endRecord($input)
    {
		// DB::connection("sqlsrvFS")->beginTransaction();
        DB::connection("sqlsrvEcs")->beginTransaction();
        DB::connection("sqlsrv")->beginTransaction();
		try {
			$getRecord = $this->recordService->where('code', $input['SoBienNhan'])->first();
			$time = strtotime($input['NgayKetThuc']);
			$input['NgayKetThuc'] = date("Y-m-d H:i:s", $time);
			$updateRecord = 3;
			 if($getRecord['current_status'] != 'TRA_KET_QUA'){
				if (isset($getRecord)) {
					//Update ngày hẹn trả bổ sung bảng record
					$arr = [
						'status'        => TrangThaiHoSoMCEnum::TRA_KET_QUA->value,
						'have_to_result_date' => $input['NgayKetThuc']
					];
					$arr_json = [
						'code'        => $input['SoBienNhan'],
						'json' => json_encode($input['DanhSachGiayToKetQua']),
						'created_at' => date("Y-m-d H:i:s"),
					];
					$JsonVbdlisService = JsonVbdlisModel::create($arr_json);
					$updateNextStep = $this->updateRecordNex($input, $getRecord, $arr);
					$updateRecord = $this->recordService->where('id', $getRecord['id'])->update($arr);
					// update record system 
					$updateRecord = $this->recordSystemService->where('code', $input['SoBienNhan'])->update($arr);
					// Update bảng recordWork
					$work_date =  $input['NgayKetThuc'];
					$work_type_name = 'Trả kết quả';
					$note = $input['GhiChu'];
					$work_type = 'TRA_KET_QUA';
					$attack_file = '';
					$stream_id = $this->uploadFile($input, $getRecord);
					$attack_file = $stream_id;
					$updateRecordWork = $this->updateRecordWork($input, $getRecord, $work_date, $work_type_name, $note, $work_type, $attack_file);
					// DB::commit();
				} else {
					$data['sobiennhan'] = $input['SoBienNhan'];
					$data['MaCanBoNhan'] = $input['MaCanBoXuLy'];
					$data['MaTrangThai'] = 'TRA_KET_QUA';
					$data['NgayKetThuc'] = $input['NgayKetThuc'];
					$data['NoiDungXuLy'] = $input['GhiChu'];
					$data['DanhSachTapTin'] = !empty($input['DanhSachGiayToKetQua'][0]['LinkFile']) ? $input['DanhSachGiayToKetQua'][0]['LinkFile'] : null;
					$data['api'] = 'KetThucHoSo';
					$updateRecord = $this->updateRecordVbdlis($data);
					$updateRecord = 2;
				}
			 }
            //  DB::connection("sqlsrvFS")->commit();
            DB::connection("sqlsrvEcs")->commit();
            DB::connection("sqlsrv")->commit();
		  	 return $updateRecord;
		} catch (\Exception $exception) {
			// DB::connection("sqlsrvFS")->rollBack();
            DB::connection("sqlsrvEcs")->rollBack();
            DB::connection("sqlsrv")->rollBack();
			throw $exception;
		}
    }
}
