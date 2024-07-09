<?php

namespace App\Enums;

enum SendingJobStatus : string
{
    case InQueue = 'in_queue';
    case Sent = 'sent';
    case Error = 'error';
}
