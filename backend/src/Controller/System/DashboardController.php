<?php

namespace App\Controller\System;

use App\Controller\System\Crud\ServerCrudController;
use App\Entity\AircraftClass;
use App\Entity\Airfield;
use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\ArticleTag;
use App\Entity\Ban;
use App\Entity\CustomPage;
use App\Entity\CustomTourRequest;
use App\Entity\Faq;
use App\Entity\FeaturedVideo;
use App\Entity\Feedback;
use App\Entity\GameDevice;
use App\Entity\Instance;
use App\Entity\Location\Region;
use App\Entity\Log;
use App\Entity\Manual;
use App\Entity\Mission;
use App\Entity\Online;
use App\Entity\Partner;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RaceRun;
use App\Entity\RaceType;
use App\Entity\RegistrationTicket;
use App\Entity\Server;
use App\Entity\Setting;
use App\Entity\Slide;
use App\Entity\SocialLink;
use App\Entity\SystemLog;
use App\Entity\Theatre;
use App\Entity\Tour;
use App\Entity\Tournament;
use App\Entity\TournamentCoupon;
use App\Entity\TournamentCouponRequest;
use App\Entity\TournamentStage;
use App\Entity\Unit;
use App\Entity\UnitType;
use App\Entity\WeatherLimit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/system")
 */
class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/", name="system")
     */
    public function index() : Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ServerCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->getParameter('title'))
            ->setTitle('<img alt="' . $this->getParameter('title') . '" width="200px" src="/images/logo_b.png" />')
            ->setFaviconPath('/images/favicon.png')
            ->setTextDirection('ltr')
            ->disableUrlSignatures()
            ->renderContentMaximized()
        ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('General'),
            MenuItem::subMenu('Content', 'fa fa-folder')->setSubItems([
                MenuItem::linkToCrud('Custom pages', 'fa fa-file', CustomPage::class),
                MenuItem::linkToCrud('Categories', 'fa fa-cubes', ArticleCategory::class),
                MenuItem::linkToCrud('Articles', 'fa fa-newspaper-o', Article::class),
                MenuItem::linkToCrud('Tags', 'fa fa-tags', ArticleTag::class),
                MenuItem::linkToCrud('FAQ', 'fa fa-question-circle', Faq::class),
                MenuItem::linkToCrud('Featured video', 'fa fa-video', FeaturedVideo::class),
                MenuItem::linkToCrud('Links', 'fa fa-link', SocialLink::class),
                MenuItem::linkToCrud('Slides', 'fa fa-image', Slide::class),
                MenuItem::linkToCrud('Partners', 'fa fa-vcard', Partner::class),
            ]),
            MenuItem::subMenu('Location', 'fa fa-folder')->setSubItems([
                MenuItem::linkToCrud('Shop regions', 'fa fa-file', Region::class),
            ]),
            MenuItem::subMenu('DCS', 'fa fa-server')->setSubItems([
                MenuItem::linkToCrud('Airfields', 'fa fa-globe', Airfield::class),
                MenuItem::linkToCrud('Missions', 'fa fa-tasks', Mission::class),
                MenuItem::linkToCrud('Planes', 'fa fa-fighter-jet', Plane::class),
                MenuItem::linkToCrud('Weather limits', 'fa fa-cloud', WeatherLimit::class),
                MenuItem::linkToCrud('Units', 'fa fa-truck', Unit::class),
                MenuItem::linkToCrud('Types', 'fa fa-th-large', UnitType::class),
                MenuItem::linkToCrud('Theatres', 'fa fa-map', Theatre::class),
            ]),
            MenuItem::subMenu('Tournaments', 'fa fa-trophy')->setSubItems([
                MenuItem::linkToCrud('List', 'fa fa-list', Tournament::class),
                MenuItem::linkToCrud('Stages', 'fa fa-ellipsis-h', TournamentStage::class),
                MenuItem::linkToCrud('Aircraft classes', 'fa fa-fighter-jet', AircraftClass::class),
                MenuItem::linkToCrud('Coupons', 'fa fa-cart-plus', TournamentCoupon::class),
                MenuItem::linkToCrud('Coupons Requests', 'fa fa-shopping-cart', TournamentCouponRequest::class),
            ]),
            MenuItem::subMenu('Racing', 'fa fa-truck')->setSubItems([
                MenuItem::linkToCrud('Race runs', 'fa fa-truck', RaceRun::class),
                MenuItem::linkToCrud('Types', 'fa fa-list', RaceType::class),
            ]),
            MenuItem::subMenu('Planing', 'fa fa-calendar')->setSubItems([
                MenuItem::linkToCrud('Tours', 'fa fa-cloud', Tour::class),
                MenuItem::linkToCrud('Custom tour requests', 'fa fa-cloud', CustomTourRequest::class),
            ]),
            MenuItem::subMenu('Community', 'fa fa-user')->setSubItems([
                MenuItem::linkToCrud('Pilots online', 'fa fa-users', Online::class),
                MenuItem::linkToCrud('Game devices', 'fa fa-gamepad', GameDevice::class),
                MenuItem::linkToCrud('Feedbacks', 'fa fa-comment', Feedback::class),
            ]),
            MenuItem::section('Tools'),
            MenuItem::subMenu('System', 'fa fa-cog')->setSubItems([
                MenuItem::linkToCrud('Servers', 'fa fa-server', Server::class),
                MenuItem::linkToCrud('Parser logs', 'fa fa-th-list', Log::class),
                MenuItem::linkToCrud('System logs', 'fa fa-th-list', SystemLog::class),
                MenuItem::linkToCrud('Settings', 'fa fa-cogs', Setting::class),
            ]),
            MenuItem::subMenu('Access', 'fa fa-shield')->setSubItems([
                MenuItem::linkToCrud('Accounts', 'fa fa-users', Pilot::class),
                MenuItem::linkToCrud('Banlist', 'fa fa-ban', Ban::class),
                MenuItem::linkToCrud('Registration requests', 'fa fa-universal-access', RegistrationTicket::class),
            ]),

            MenuItem::section('Software'),
            MenuItem::subMenu('Sender', 'fa fa-laptop')->setSubItems([
                MenuItem::linkToCrud('Serial numbers', 'fa fa-key', Instance::class),
            ]),
            MenuItem::section('External resources'),
            MenuItem::subMenu('Development', 'fa fa-terminal')->setSubItems([
                MenuItem::linkToUrl('Gitlab', 'fa fa-code', 'https://git.dev.virpil.com'),
                MenuItem::linkToUrl('Jenkins', 'fa fa-cogs', 'https://jenkins.virpil-servers.com'),
                MenuItem::linkToUrl('MySQL', 'fa fa-database', 'https://mysql.virpil-servers.com'),
                MenuItem::linkToUrl('RabbitMQ', 'fa fa-industry', 'https://amqp.virpil-servers.com'),
            ]),
        ];
    }
}
