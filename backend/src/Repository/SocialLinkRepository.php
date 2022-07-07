<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SocialLinkRepository
 */
class SocialLinkRepository extends EntityRepository
{
    public const FACEBOOK = 'facebook';
    public const FLICKR = 'flickr';
    public const GOOGLE = 'google';
    public const INSTAGRAM = 'instagram';
    public const DISCORD = 'discord';
    public const LINKEDIN = 'linkedin';
    public const TWITTER = 'twitter';
    public const VK = 'vk';
    public const YOUTUBE = 'youtube';
    public const TWITCH = 'twitch';

    public static array $icons = [
        'Facebook' => self::FACEBOOK,
        'Flickr' => self::FLICKR,
        'Google' => self::GOOGLE,
        'Instagram' => self::INSTAGRAM,
        'Discord' => self::DISCORD,
        'Linkedin' => self::LINKEDIN,
        'Twitter' => self::TWITTER,
        'VK' => self::VK,
        'Youtube' => self::YOUTUBE,
        'Twitch' => self::TWITCH,
    ];
}
