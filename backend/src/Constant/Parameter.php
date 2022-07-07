<?php

namespace App\Constant;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Parameter
{
    public const DCS_PILOT_HEADER = 'X-DCS-UCID';
    public const DCS_SERIAL_HEADER = 'X-DCS-SERIAL';
    public const DCS_SERVER_HEADER = 'X-DCS-SERVER';

    public const EVENT_SIMPLE_RADIO_DATA = 'srs-data';
    public const EVENT_SERVER_Online = 'start';
    public const EVENT_SERVER_Offline = 'stop';
    public const EVENT_PILOT_Enter = 'enter';
    public const EVENT_PILOT_Join = 'join';
    public const EVENT_PILOT_Left = 'left';
    public const EVENT_PILOT_Takeoff = 'takeoff';
    public const EVENT_PILOT_Landed = 'land';
    public const EVENT_PILOT_CrashLanded = 'pilot-landed-emergency';
    public const EVENT_PILOT_Death = 'dead';
    public const EVENT_PILOT_Crash = 'crash';
    public const EVENT_PILOT_Eject = 'eject';
    public const EVENT_PILOT_Kill = 'kill';
    public const EVENT_PILOT_Shot = 'shot';
    public const EVENT_PILOT_Won = 'won';
    public const EVENT_PILOT_FriendlyFire = 'friendfire';
    public const LOG_FILE = 'app.log';

    public const MIME_TYPE_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    public const DEFAULT_PORT = '10308';

    public static array $events = [
        self::EVENT_SERVER_Online,
        self::EVENT_SERVER_Offline,
        self::EVENT_PILOT_Enter,
        self::EVENT_PILOT_Join,
        self::EVENT_PILOT_Left,
        self::EVENT_PILOT_Takeoff,
        self::EVENT_PILOT_Landed,
        self::EVENT_PILOT_CrashLanded,
        self::EVENT_PILOT_Death,
        self::EVENT_PILOT_Crash,
        self::EVENT_PILOT_Eject,
        self::EVENT_PILOT_Kill,
        self::EVENT_PILOT_FriendlyFire,
        self::EVENT_SIMPLE_RADIO_DATA,
        self::EVENT_PILOT_Won,
        self::EVENT_PILOT_Shot,
    ];

    public static array $radioModulationConstants = [0, 1, 2];

    public const MODULATION_UHF = 0;
    public const MODULATION_AM = 1;
    public const MODULATION_FM = 2;

    public const EMAIL = 'projectdcsuser@gmail.com';
    public const EMAIL_PREFIX = '[VirpilServers]';
    public const REPORT_EMAILS = ['vasyl@starsam.net', 'admin@smile-pilots.ru', 'eekzmail@gmail.com', 'spc.m1tek@gmail.com'];
    public const BUTTON_SAVE = 'btn btn-sm btn-info';
    public const FOLDER_UPLOAD_AVATARS = '/uploads/avatars';
    public const FOLDER_PUBLIC = '/public';

    public const GMAIL_PROTOCOL = 'ssl';

    public const XLS_ALIGN_LEFT = [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ];

    public const XLS_BORDERS_DOUBLE = [
        'allBorders' => [
            'borderStyle' => Border::BORDER_DOUBLE,
        ],
    ];

    public const XLS_BORDERS_THIN = [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ];
    public const XLS_FONT = [
        'name' => 'Calibri',
        'size' => 12,
        'bold' => false,
        'color' => ['argb' => '000000']
    ];

    public static $style = [
        'table' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_THIN,
        ],
        'warning' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_DOUBLE,
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFD700',
                ],
            ],
        ],
        'error' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_DOUBLE,
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF8C00',
                ],
            ],
        ],
        'alert' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_DOUBLE,
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF7F50',
                ],
            ],
        ],
        'critical' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_DOUBLE,
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF6347',
                ],
            ],
        ],
        'emergency' => [
            'font' => self::XLS_FONT,
            'alignment' => self::XLS_ALIGN_LEFT,
            'borders' => self::XLS_BORDERS_DOUBLE,
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF4500',
                ],
            ],
        ]
    ];
}
