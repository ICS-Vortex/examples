<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImagesService
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parametersBag;

    public function __construct(ParameterBagInterface $parametersBag)
    {
        $this->parametersBag = $parametersBag;
    }

    /**
     * @param $image
     * @param null $sidePrefix
     * @param string $type
     * @return string
     */
    public function checkImage($image, $sidePrefix = null, $type = 'plane') : string
    {
        $filepath = $this->parametersBag->get('kernel.project_dir') . '/public' . $this->parametersBag->get('app.path.planes_images').'/'.$image;
        if (!file_exists($filepath)) {
            switch ($type) {
                case 'plane':
                    if (!empty($sidePrefix)) {
                        return '/images/planes/'.strtolower($image).'_'.$sidePrefix.'.png';
                    }
                    return '/images/planes/'.strtolower($image).'.png';
                case 'article':
                    return '/images/article.png';
                case 'slide':
                    return '/images/slide.jpg';
                default:
                    return $filepath;
            }
        }

        return $filepath;
    }
}
