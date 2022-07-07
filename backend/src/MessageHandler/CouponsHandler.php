<?php

namespace App\MessageHandler;

use App\Entity\CouponFile;
use App\Entity\Log;
use App\Entity\TournamentCoupon;
use App\Message\CouponsFileMessage;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use SplFileObject;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CouponsHandler implements MessageHandlerInterface
{
    /** @var ParameterBagInterface */
    private ParameterBagInterface $bag;
    private EntityManagerInterface $manager;

    public function __construct(ParameterBagInterface $bag, EntityManagerInterface $manager)
    {
        $this->bag = $bag;
        $this->manager = $manager;
    }

    public function __invoke(CouponsFileMessage $message)
    {
        /** @var CouponFile $file */
        $file = $this->manager->getRepository(CouponFile::class)->find($message->getFile()->getId());
        $filename = $file->getSource();
        $path = $this->bag->get('app.path.tournaments_coupons_files');
        $dir = $this->bag->get('kernel.project_dir');
        $full = $dir . '/public' . $path . '/' . $filename;
        try {
            $io = new SplFileObject($full);
            $flush = false;
            while (!$io->eof()) {
                $coupon = trim($io->fgets());
                if (!empty($coupon)) {
                    $tCoupon = new TournamentCoupon();
                    $tCoupon->setTournament($file->getTournament());
                    $tCoupon->setCode($coupon);
                    $tCoupon->setActive(true);
                    $this->manager->persist($tCoupon);
                    $flush = true;
                }
            }
            if ($flush) {
                $file->setUploaded(true);
                $file->setWithErrors(false);
                $this->manager->persist($file);
                $this->manager->flush();
            }
            $io = null;
        }catch (\Exception $e) {
            $file->setWithErrors(true);
            $file->setUploaded(true);
            $this->manager->persist($file);
            $this->manager->flush();
            $this->manager->getRepository(Log::class)->log($e->getTraceAsString(), Logger::EMERGENCY, 'amqp');
        }
    }
}
