<?php

namespace Modules\Frontend\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Frontend\Services\Admin\ListService;

/**
 * Lấy các dữ liệu chung
 * 
 * @author luatnc
 */
class CommonController
{

    private $listService;

    public function __construct(ListService $l)
    {
        $this->listService = $l;
    }

    /**
     * Lấy đơn vị theo cấp đơn vị
     * 
     * @param string $level cấp đơn vị
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function unitByLevel(string $level, Request $request): JsonResponse|string
    {
        $accept = explode(', ', $request->header()['accept'][0]);
        $list = $this->listService->getUnitByLevel($level);
        $html = '';
        $arrWard = [];
        $arrKeyName = [];

        if (array_search('text/html', $accept) === false) {
            return response()->json(toArray($list));
        } else {
            $list->map(function ($l) use (&$html, &$arrWard, &$arrKeyName) {
                if ($l->unit_level === 'PHUONG_XA') {
                    if (array_key_exists($l->parent_code, $arrKeyName) === false) $arrKeyName[$l->parent_code] = $l->parent_name;
                    $arrWard[$l->parent_code][] = ['code' => $l->code, 'name' => $l->name];
                } else $html .= '<option value="' . $l->code . '">' . $l->name . '</option>';
            });
        }

        if (count($arrWard) > 0) {
            foreach ($arrWard as $key => $group) {
                $html .= '<optgroup label="' . $arrKeyName[$key] . '">';
                foreach ($group as $ward) {
                    $html .= '<option value="' . $ward['code'] . '">' . $ward['name'] . '</option>';
                }
                $html .= '</optgroup>';
            }
        }

        return $html;
    }

    /**
     * Trang thể lệ
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function viewRule(): View
    {
        return view('Frontend::rule.index');
    }

    /**
     * Trang thể lệ
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function viewSchedule(): View
    {
        return view('Frontend::schedule.index');
    }
}
