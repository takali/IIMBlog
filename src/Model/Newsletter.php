<?php
namespace App\Model;

class Newsletter
{
    protected $mailer;

    /**
     * @return Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }


    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
