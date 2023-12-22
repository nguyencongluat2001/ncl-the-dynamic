<?php

namespace Modules\Base\Helpers;

use Illuminate\Support\Facades\Http;
use Modules\Base\Mail\NclMail;
use Modules\Base\Mail\NclMailOtp;
use Illuminate\Support\Facades\Mail;
/**
 * Api gửi mail
 * 
 * @author luatnc
 */
class ForgetPassWordMailHelper
{
    protected $logger;
    protected $url;
    protected $subject;
    protected $mailto;
    protected $nameto;
    protected $content;

    /**
     * Thực hiện gọi api gửi mail
     * 
     * @param string $mailTo Địa chỉ nhận mail
     * @param string $nameTo Tên người nhận
     * @param string $message Nội dung
     * 
     * @return boolean
     */
    public function send($data)
    {
        Mail::to($data['mailto'])->send(new NclMail($data));
        return true;
    }
    public function send_otp($data)
    {
        Mail::to($data['mailto'])->send(new NclMailOtp($data));
        return true;
    }
}
