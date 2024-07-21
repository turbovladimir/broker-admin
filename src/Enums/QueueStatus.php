<?php

namespace App\Enums;

enum QueueStatus: string {
    case New = 'Новая';
    case Adjusted = 'Настроена';
    case InProcess = 'В процессе';
    case Sent = 'Отправлено';
    case Stop = 'Остановлена';
}