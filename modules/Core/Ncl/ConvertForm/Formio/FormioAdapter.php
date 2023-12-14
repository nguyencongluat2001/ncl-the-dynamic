<?php

namespace Modules\Core\Ncl\ConvertForm\Formio;

use Modules\Core\Ncl\ConvertForm\Formly\FormlyInterface;

/**
 * Adapter chuyển đổi Formio JSON sang Formly JSON
 * 
 * @author luatnc
 */
class FormioAdapter implements FormlyInterface
{
    protected $formio;

    public function __construct(FormioInterface $formioInterface)
    {
        $this->formio = $formioInterface;
    }

    /**
     * Lấy chuỗi JSON đã convert sang Formly
     * 
     * @return string
     */
    public function get(): string
    {
        return $this->convertToFormly($this->formio->get());
    }

    /**
     * Chuyển thành json của Formly
     * 
     * @param string $strJson Chuỗi json đã encode của Formio
     * @return string
     */
    private function convertToFormly(string $strJson): string
    {
        $none = json_encode([]);
        if (empty($strJson)) return $none;
        $arrFormio = json_decode($strJson, true);
        if (empty($arrFormio) || count($arrFormio) === 0) return $none;
        $formly = $this->genFormly($arrFormio);

        return json_encode($formly);
    }

    /**
     * Lấy array sau khi convert
     * 
     * @param array $arrFormio Mảng các Formio
     * @return array
     */
    private function genFormly(array $arrFormio): array
    {
        $rows = array();
        foreach ($arrFormio['components'] as $formio) {
            if ($formio['type'] === 'columns') {
                $cols = array();
                foreach ($formio['columns'] as $fc) {
                    $col = array();
                    if (isset($fc['components'][0])) {
                        $component = $fc['components'][0];
                        $col = $this->genField($component, $fc);
                        if ($col && count($col) > 0) array_push($cols, $col);
                    }
                }
                if (!$cols || count($cols) === 0) continue;
                array_push($rows, [
                    'fieldGroupClassName' => 'row',
                    'fieldGroup' => $cols
                ]);
            } else {
                $col = $this->genField($formio);
                if (!$col || count($col) === 0) continue;
                array_push($rows, [
                    'fieldGroupClassName' => 'row',
                    'fieldGroup' => array($col),
                ]);
            }
        }

        return $rows;
    }

    /**
     * Tạo mnagr dữ liệu formly cho từng field
     * 
     * @param array $fieldFormio Mảng file từ formio
     * @param array $column Thông tin về cột formio (nếu field đặt trong column)
     * @return array|false
     */
    private function genField(array $fieldFormio, array $column = []): array|false
    {
        $type = $this->getType($fieldFormio['type']);
        if ($type === '') return false;
        $field['className'] = "col-md-12 col-sm-12";
        if (count($column) > 0) $field['className'] = "col-" . $column['size'] . "-" . $column['width'];
        $field['key'] = $fieldFormio['key'];
        $field['type'] = $type;
        $field['templateOptions'] = $this->getTemplateOptions($fieldFormio, $type);
        $field['properties'] = $fieldFormio['properties'] ?? [];
        if (isset($field['templateOptions']['hide']) && $field['templateOptions']['hide'] === true) {
            $field['type'] = 'hidden';
        }
        // if ($field['type'] === 'multicheckbox') {
        //     $field['defaultValue'] = '';
        // }

        return $field;
    }

    /**
     * Lấy kiểu hiển thị thoe Formly
     * 
     * @param string $type Kiểu của Formio
     * @return string
     */
    private function getType(string $type): string
    {
        switch ($type) {
            case 'textfield':
            case 'number':
                return 'input';
            case 'datetime':
                return 'datepicker';
            case 'selectboxes':
                return 'multicheckbox';
            case 'textarea':
            case 'select':
            case 'checkbox':
            case 'radio':
                return $type;

            default:
                return '';
        }
    }

    /**
     * Lấy array phần templateOptions của Formly
     * 
     * @param array $components Thành phần từ Formio
     * @param string $type Kiểu hiển thị Formly
     * @return array
     */
    private function getTemplateOptions(array $component, string $type): array
    {
        $templateOptions = array();
        $templateOptions['appearance'] = 'outline';
        $templateOptions['label'] = $component['label'];
        $templateOptions['required'] = $component['validate']['required'] ?? false;
        $templateOptions['placeholder'] = $component['placeholder'] ?? '';
        $templateOptions['description'] = $component['description'] ?? '';
        $templateOptions['hide'] = $component['hidden'] ?? false;
        $templateOptions['disabled'] = $component['disabled'] ?? false;
        $templateOptions['multiple'] = $component['multiple'] ?? false;
        switch ($type) {
            case 'input':
                break;
            case 'datepicker':
                $templateOptions['format'] = $component['format'] ?? 'date';
                break;
            case 'textarea':
                $templateOptions['rows'] = $component['rows'] ?? 1;
                break;
            case 'select':
                if (isset($component['dataSrc']) && $component['dataSrc'] === 'url') {
                    $templateOptions['listtype_code'] = $component['data']['url'] ?? [];
                }
                $templateOptions['labelProp'] = 'label';
                $templateOptions['valueProp'] = 'value';
                $templateOptions['options'] = $component['data']['values'] ?? [];
                break;
            case 'checkbox':
                $templateOptions['indeterminate'] = false;
                break;
            case 'multicheckbox':
                if (isset($component['dataSrc']) && $component['dataSrc'] === 'url') {
                    $templateOptions['listtype_code'] = $component['data']['url'] ?? [];
                }
                $templateOptions['indeterminate'] = false;
                $templateOptions['labelProp'] = 'label';
                $templateOptions['valueProp'] = 'value';
                $templateOptions['options'] = $component['values'] ?? [];
                break;
            case 'radio':
                if (isset($component['dataSrc']) && $component['dataSrc'] === 'url') {
                    $templateOptions['listtype_code'] = $component['data']['url'] ?? [];
                }
                $templateOptions['labelProp'] = 'label';
                $templateOptions['valueProp'] = 'value';
                $templateOptions['options'] = $component['values'] ?? [];
                break;
        }

        return $templateOptions;
    }
}
