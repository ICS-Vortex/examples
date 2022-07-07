<?php

namespace App\Repository;

use App\Entity\Setting;

class SettingRepository extends BaseRepository
{
    public const SETTING_REPORTS_RECEIVERS = 'setting_reports_receivers';
    public const SETTING_EMAILS_RECIPIENTS = 'setting_emails_recipients';
    public const SETTING_REPORTS_EMAILS_PREFIX = 'setting_reports_emails_prefix';
    public const SETTING_PARSER_SAVE_LOGS = 'setting_parser_save_logs';
    public const SETTING_PARSER_SAVE_JSONS = 'setting_parser_save_jsons';
    public const SETTING_APPLICATION_TITLE = 'setting_application_title';
    public const SETTING_MAINTENANCE_MODE = 'setting_maintenance_mode';
    public const SETTING_FALLBACK_PLANE = 'setting_fallback_plane';
    public const SETTING_IGNORE_UNITS_EVENT = 'setting_ignore_units_event';
    public const SETTING_NOTIFICATIONS_EMAILS_PREFIX = 'setting_notifications_emails_prefix';
    public const SETTING_FCM_API_KEY = 'setting_fcm_api_key';
    public const SETTING_GOOGLE_MAPS_API_KEY = 'setting_google_maps_api_key';
    public const SETTING_FCM_URL = 'setting_fcm_url';
    public const SETTING_PARSER_LOGGING = 'parser_logging';
    public const SETTING_PARSER_ENQUEUE_JSONS = 'setting_parser_enqueue_jsons';
    public const SETTING_PRINT_LOG_MESSAGES = 'print_log_messages';
    public const SETTING_FEATURED_VIDEO_1 = 'setting_featured_video_1';
    public const SETTING_FEATURED_VIDEO_2 = 'setting_featured_video_2';
    public const SETTING_FEATURED_VIDEO_3 = 'setting_featured_video_3';
    public const SETTING_LIFETIME_REWARD_1_USERS = 'setting_lifetime_reward_1_users';
    public const SETTING_LIFETIME_REWARD_2_USERS = 'setting_lifetime_reward_2_users';
    public const SETTING_LIFETIME_REWARD_3_USERS = 'setting_lifetime_reward_3_users';
    public const SETTING_LIFETIME_REWARD_4_USERS = 'setting_lifetime_reward_4_users';
    public const SETTING_LIFETIME_REWARD_5_USERS = 'setting_lifetime_reward_5_users';
    public const SETTING_LIFETIME_REWARD_6_USERS = 'setting_lifetime_reward_6_users';
    public const SETTING_LIFETIME_REWARD_7_USERS = 'setting_lifetime_reward_7_users';
    public const SETTING_LIFETIME_REWARD_8_USERS = 'setting_lifetime_reward_8_users';
    public const SETTING_LIFETIME_REWARD_9_USERS = 'setting_lifetime_reward_9_users';
    public const SETTING_LIFETIME_REWARD_10_USERS = 'setting_lifetime_reward_10_users';
    public const SETTING_LIFETIME_REWARD_11_USERS = 'setting_lifetime_reward_11_users';
    public const SETTING_LIFETIME_REWARD_12_USERS = 'setting_lifetime_reward_12_users';
    public const SETTING_LIFETIME_REWARD_13_USERS = 'setting_lifetime_reward_13_users';
    public const SETTING_LIFETIME_REWARD_14_USERS = 'setting_lifetime_reward_14_users';
    public const SETTING_LIFETIME_REWARD_15_USERS = 'setting_lifetime_reward_15_users';


    public static array $options = array(
        [
            'name' => self::SETTING_REPORTS_RECEIVERS,
            'key' => self::SETTING_REPORTS_RECEIVERS,
            'value' => '',
            'default_value' => '',
            'description' => 'message.' . self::SETTING_REPORTS_RECEIVERS,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_REPORTS_EMAILS_PREFIX,
            'key' => self::SETTING_REPORTS_EMAILS_PREFIX,
            'value' => '[VPCReport]',
            'default_value' => '[VPCReport]',
            'description' => 'message.' . self::SETTING_REPORTS_EMAILS_PREFIX,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_PARSER_SAVE_LOGS,
            'key' => self::SETTING_PARSER_SAVE_LOGS,
            'value' => 0,
            'default_value' => 0,
            'description' => 'message.' . self::SETTING_PARSER_SAVE_LOGS,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_APPLICATION_TITLE,
            'key' => self::SETTING_APPLICATION_TITLE,
            'value' => 'Application title',
            'default_value' => 'Application title',
            'description' => 'message.' . self::SETTING_APPLICATION_TITLE,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_MAINTENANCE_MODE,
            'key' => self::SETTING_MAINTENANCE_MODE,
            'value' => '0',
            'default_value' => '0',
            'description' => 'message.' . self::SETTING_MAINTENANCE_MODE,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_IGNORE_UNITS_EVENT,
            'key' => self::SETTING_IGNORE_UNITS_EVENT,
            'value' => '0',
            'default_value' => '0',
            'description' => 'message.' . self::SETTING_IGNORE_UNITS_EVENT,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_PARSER_SAVE_JSONS,
            'key' => self::SETTING_PARSER_SAVE_JSONS,
            'value' => '0',
            'default_value' => '0',
            'description' => 'message.' . self::SETTING_PARSER_SAVE_JSONS,
            'deleted' => false,
        ],
        [
            'name' => self::SETTING_FALLBACK_PLANE,
            'key' => self::SETTING_FALLBACK_PLANE,
            'value' => 'Su-25T',
            'default_value' => 'Su-25T',
            'choices' => [
                'Su-25T', 'TF-51D', 'P-51D', 'FA-18C', 'SU-27', 'SU-33'
            ],
            'description' => 'message.' . self::SETTING_FALLBACK_PLANE,
            'hidden' => false,
        ],
        array(
            'name' => self::SETTING_LIFETIME_REWARD_1_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_1_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_1_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_2_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_2_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_2_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_3_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_3_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_3_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_4_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_4_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_4_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_5_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_5_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_5_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_6_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_6_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_6_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_7_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_7_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_7_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_8_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_8_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_8_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_9_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_9_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_9_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_10_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_10_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_10_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_11_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_11_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_11_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_12_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_12_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_12_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_13_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_13_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_13_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_14_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_14_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_14_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_LIFETIME_REWARD_15_USERS,
            'key' => self::SETTING_LIFETIME_REWARD_15_USERS,
            'value' => '1|2|3',
            'default_value' => '1|2|3',
            'description' => 'message.' . self::SETTING_LIFETIME_REWARD_15_USERS,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_FEATURED_VIDEO_1,
            'key' => self::SETTING_FEATURED_VIDEO_1,
            'value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'default_value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'description' => 'message.' . self::SETTING_FEATURED_VIDEO_1,
            'deleted' => true,
        ),
        array(
            'name' => self::SETTING_FEATURED_VIDEO_2,
            'key' => self::SETTING_FEATURED_VIDEO_2,
            'value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'default_value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'description' => 'message.' . self::SETTING_FEATURED_VIDEO_2,
            'deleted' => true,
        ),
        array(
            'name' => self::SETTING_FEATURED_VIDEO_3,
            'key' => self::SETTING_FEATURED_VIDEO_3,
            'value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'default_value' => 'https://www.youtube.com/embed/live_stream?channel=UCgJRhtnqA-67pKmQ3A2GsgA',
            'description' => 'message.' . self::SETTING_FEATURED_VIDEO_3,
            'deleted' => true,
        ),
        array(
            'name' => self::SETTING_FCM_API_KEY,
            'key' => self::SETTING_FCM_API_KEY,
            'value' => 'AIzaSyAY3qLSICEfISuRgo2HTmB27cYtLWyvf0U',
            'default_value' => 'AIzaSyAY3qLSICEfISuRgo2HTmB27cYtLWyvf0U',
            'description' => 'message.' . self::SETTING_FCM_API_KEY,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_GOOGLE_MAPS_API_KEY,
            'key' => self::SETTING_GOOGLE_MAPS_API_KEY,
            'value' => 'AIzaSyCQbP08jXnVRZGGGLCfHLRkpNvuiQ_qMsA',
            'default_value' => 'AIzaSyCQbP08jXnVRZGGGLCfHLRkpNvuiQ_qMsA',
            'description' => 'message.' . self::SETTING_GOOGLE_MAPS_API_KEY,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_FCM_URL,
            'key' => self::SETTING_FCM_URL,
            'value' => 'https://fcm.googleapis.com/fcm/send',
            'default_value' => 'https://fcm.googleapis.com/fcm/send',
            'description' => 'message.' . self::SETTING_FCM_URL,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_PARSER_LOGGING,
            'key' => self::SETTING_PARSER_LOGGING,
            'value' => '1',
            'default_value' => '0',
            'description' => 'message.' . self::SETTING_PARSER_LOGGING,
            'deleted' => false,
        ),
        array(
            'name' => self::SETTING_PARSER_ENQUEUE_JSONS,
            'key' => self::SETTING_PARSER_ENQUEUE_JSONS,
            'value' => '1',
            'default_value' => '1',
            'description' => 'message.' . self::SETTING_PARSER_ENQUEUE_JSONS,
            'deleted' => false,
        ),
    );

    public function getOption($optionKey): ?Setting
    {
        if (empty($optionKey)) {
            return null;
        }
        $options = $this->findBy([]);

        $optionSearch = array_filter($options, static function ($option) use ($optionKey) {
            /** @var Setting $option */
            if ($optionKey === $option->getKeyword()) {
                return $option;
            }
        });
        if (!empty($optionSearch)) {
            /** @var Setting $option */
            $option = reset($optionSearch);
            return $option;
        }

        return null;

    }


    public function getSettings()
    {
        $options = [];
        $result = $this->findAll();
        /** @var Setting $setting */
        foreach ($result as $setting) {
            $options[$setting->getKeyword()] = $setting;
        }

        return $options;
    }
}
