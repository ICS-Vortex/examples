<?php

namespace App\Message;

use App\Entity\CouponFile;

class CouponsFileMessage
{
    private CouponFile $file;

    public function __construct(CouponFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return CouponFile
     */
    public function getFile(): CouponFile
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setCoupon(CouponFile $file): void
    {
        $this->file = $file;
    }
}
