<?php

namespace Modules\Core\Efy\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Cấu hình gửi mail
 * 
 * @author khuongtq
 * @updated 17/01/2023
 */
class EfyMail extends Mailable
{
    use Queueable, SerializesModels, Dispatchable, InteractsWithQueue;

    protected $data;
    protected $attach;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attach = [])
    {
        $this->data = $data;
        $this->attach = $attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->data['subject'])
            ->html($this->data['content']);
        if (!empty($this->attach)) {
            foreach ($this->attach as $att) {
                $mail->attachData(base64_decode($att['file']), $att['name']);
            }
        }

        return $this;
    }
}
