<?php

namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SqlController extends Controller
{
    public $categoryService;
    public function __construct()
    {
    }
    public function index()
    {
        return view('Frontend::Dashboard.Sql.index');
    }
    public function loadList(Request $request)
    {
        $arrInput = $request->all();
        $statement = $arrInput['search'];
        $data['datas'] = DB::select($statement);
        return view('Frontend::Dashboard.Sql.loadList', $data);
    }
}