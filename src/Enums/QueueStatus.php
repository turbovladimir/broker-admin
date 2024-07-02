<?php

namespace App\Enums;

enum QueueStatus: string {
    case New = 'Новая';
    case InProcess = 'В процессе';
    case Sent = 'Отправлено';
}