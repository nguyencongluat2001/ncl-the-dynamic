<?php

namespace Modules\Api\Helpers;

use Modules\Core\Ncl\LoggerHelpers;
use Modules\Core\Ncl\Mail\SendMailJob;

/**
 * Xử lý dữ liệu để gửi mail
 * 
 * 03/10/2022
 * @author luatnc
 */
class MailHelper
{
    /**
     * Xếp hàng đợi Gửi email
     * 
     * @param array $data Thông tin gửi mail
     * @return bool
     */
    public static function sendMail($data)
    {
        if (!isset($data['mailto']) || $data['mailto'] == '')
            return array('success' => false, 'message' => 'Địa chỉ người nhận (mailto) là bắt buộc');
        if (!isset($data['subject']) || $data['subject'] == '')
            return array('success' => false, 'message' => 'Tiêu đề (subject) là bắt buộc');
        if (!isset($data['content']) || $data['content'] == '')
            return array('success' => false, 'message' => 'Nội dung (content) là bắt buộc');
        $logger = new LoggerHelpers;
        $logger->setFileName('SendMail');
        try {
            SendMailJob::dispatch($data);
            $logger->setChannel('Success')->log('Message: Queue success', $data);
            return array('success' => true, 'message' => 'Xếp hàng chờ mail thành công');
        } catch (\Exception $e) {
            $logger->setChannel('Error')->log('Message: ' . $e->getMessage(), $e);
            return array('success' => false, 'message' => 'Xếp hàng chờ mail thất bại');
        }
    }

    /**
     * Thay thế dữ liệu vào template
     * 
     * @param string $content Chuỗi html gốc
     * @param array $data Dữ liệu thay thế
     * @return void
     */
    public static function replaceData($content, $data)
    {
        $listFile = '';
        if (isset($data['date']))
            $content = str_replace('#date#', $data['date'], $content);
        if (isset($data['name_unit']))
            $content = str_replace('#name_unit#', $data['name_unit'], $content);
        if (isset($data['registor_name']))
            $content = str_replace('#registor_name#', $data['registor_name'], $content);
        if (isset($data['registor_address']))
            $content = str_replace('#registor_address#', $data['registor_address'], $content);
        if (isset($data['registor_phone']))
            $content = str_replace('#registor_phone#', $data['registor_phone'], $content);
        if (isset($data['registor_email']))
            $content = str_replace('#registor_email#', $data['registor_email'], $content);
        if (isset($data['recordtype_name']))
            $content = str_replace('#recordtype_name#', $data['recordtype_name'], $content);
        if (isset($data['message']))
            $content = str_replace('#message#', $data['message'], $content);
        if (isset($data['code']))
            $content = str_replace('#code#', $data['code'], $content);
        if (isset($data['fee']))
            $content = str_replace('#fee#', $data['fee'], $content);
        if (isset($data['process_number_date']))
            $content = str_replace('#process_number_date#', $data['process_number_date'], $content);
        if (isset($data['received_date']))
            $content = str_replace('#received_date#', $data['received_date'], $content);
        if (isset($data['have_to_result_date']))
            $content = str_replace('#have_to_result_date#', $data['have_to_result_date'], $content);
        if (isset($data['return_result_address']))
            $content = str_replace('#return_result_address#', $data['return_result_address'], $content);
        if (
            isset($data['listfile'])
            && is_array($data['listfile'])
            && count($data['listfile']) > 0
        ) {
            $listFile = self::convertStringListFile($data['listfile']);
        }
        $content = str_replace('#listfile#', $listFile, $content);

        return $content;
    }

    /**
     * Conver mảng tlkt thành chuỗi html
     * 
     * @param array $arrListFile
     * @return string
     */
    public static function convertStringListFile(array $arrListFile)
    {
        $strFile = '';
        if (isset($arrListFile) && count($arrListFile) > 0) {
            $strFile .= '<ul>';
            foreach ($arrListFile as $file) {
                $strFile .= '<li>' . $file['name'] . '</li>';
            }
            $strFile .= '</ul>';
        }

        return $strFile;
    }
}
