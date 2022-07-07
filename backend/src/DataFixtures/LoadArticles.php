<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadArticles extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['articles_fixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $category = new ArticleCategory();
        $category->setTitle($faker->company);
        $category->setTitleEn($faker->company);
        $manager->persist($category);


        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create();
            $article = new Article();
            $article->setTitle($faker->company);
            $article->setTitleEn($faker->company);
            $article->setDescription($faker->text(150));
            $article->setDescriptionEn($faker->text(150));
            $article->setEn($faker->realText(400));
            $article->setRu($faker->realText(400));
            $article->setIsVideoPost(($i % 2) == 0);
            $article->setMetaDescription($faker->text(150));
            $article->setMetaDescriptionEn($faker->text(150));
            $article->setMetaH1($faker->title);
            $article->setMetaH1En($faker->title);
            $article->setMetaKeyword($faker->jobTitle);
            $article->setMetaKeywordEn($faker->jobTitle);
            $article->setMetaTitle($faker->company);
            $article->setMetaTitleEn($faker->company);
            $article->setPublic(true);
            $article->setYoutubeShortCode('PuVNLaH2i5Q');
            $article->setCategory($category);
            $article->setImage('article.png');

            $manager->persist($article);
        }

        $manager->flush();
    }
}
