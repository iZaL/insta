<?php
namespace App\Core\Contracts;

interface MailerContract
{

    public function fire(array $data);

} 