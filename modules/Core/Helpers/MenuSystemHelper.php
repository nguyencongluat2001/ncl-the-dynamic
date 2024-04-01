<?php

namespace Modules\Core\Helpers;

/**
 * Helper xử lý liên quan việc zend menu ra html.
 *
 * @author test <skype: test1505>
 */
class MenuSystemHelper
{

	/**
	 * Xuất menu ra html
	 *
	 * @param string $module : module của menu
	 * @param array $menu : menu cha
	 * @param boolean $active : là true là active trên form
	 * @param string $parrenturl : url parent menu
	 *
	 * @return string
	 */
	public static function print_menu($module, $menu, $currentModule, $childModule)
	{
		$html = '';
		$layout = 'system';
		if (!self::check_permission_menu($menu)) return $html;

		// Menu con (thường)
		$childs = $menu['child'] ?? false;
		// Menu con (chỉ admin system mới hiện)
		$childrens = $menu['children'] ?? false;

		$activeParent = '';
		$styleChild = '';
		if ($module === $currentModule) {
			$activeParent = 'active';
			$styleChild = 'style="display: block"';
		}

		$html .= '<li class="nav-item" id="main_' . $module . '">';
		// check menu co menu cap 2 khong
		if (!$childs) {
			$html .= '<a class="nav-link ' . $activeParent . '" role="button" href="' . url($layout . '/' . $module) . '">';
			$html .= '<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas ' . $menu['icon'] . '"></i></div>';
			$html .= '<span class="nav-link-text ms-1">' . $menu['name'] . '</span>';
			$html .= '</a>';
		} else {
			$html .= '<a id="' . $module . '" class="nav-link ' . $activeParent . '" href="javascript::void();" >';
			$html .= '<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas ' . $menu['icon'] . '"></i></div>';
			$html .= '<span class="nav-link-text ms-1" style="flex: 1">' . $menu['name'] . '</span>';
			$html .= '<i class="fa fa-chevron-down"></i> </a>';
			// Menu cap 2
			$html .= '<ul class="nav-item" ' . $styleChild . '>';
			if ($childrens && $_SESSION['role'] === 'ADMIN_SYSTEM') {
				foreach ($childrens as $key => $value) {
					$activeChild = '';
					if ($key === $childModule) $activeChild = 'active';
					$html .= '<li><a id="child_' . $key . '" class="nav-link ' . $activeChild . '" href="' . url($layout . '/' . $module . '/' . $key) . '"><i class="me-1 fas ' . $value['icon'] . '"></i> ' . $value['name'] . '</a></li>';
				}
			} else {
				foreach ($childs as $key => $value) {
					$activeChild = '';
					if ($key === $childModule) $activeChild = 'active';
					$html .= '<li><a id="child_' . $key . '" class="nav-link ' . $activeChild . '" href="' . url($layout . '/' . $module . '/' . $key) . '"><i class="me-1 fas ' . $value['icon'] . '"></i> ' . $value['name'] . '</a></li>';
				}
			}
			$html .= '</ul>';
		}
		$html .= '</li>';

		return $html;
	}

	public static function check_permission_menu($menu)
	{
		if ($menu['check_permision']) {
			$arrRole = explode(',', $menu['check_permision']);
			foreach ($arrRole as $role) {
				if ($role === $_SESSION["role"]) {
					return true;
				}
			}
			return false;
		} else {
			return true;
		}
	}
}
