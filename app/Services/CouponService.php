<?php

namespace App\Services;

use App\Repositories\CouponRepository;

class CouponService extends BaseService{

    public function __construct(CouponRepository $couponRepository)
    {
        parent::__construct($couponRepository);
    }

    /**
     * Check for active coupons
     * status
     * expire date has not come yet
     *
     * @param [type] $code
     * @return boolean
     */
    public function isCoupon($code){
//->whereRaw('times < amount')
        $coupon = $this->repo->where(['code' => $code, 'status' => 1])->whereRaw('expire_date >= CURDATE()')->first();
        if($coupon){
           return $coupon;
        }

        return false;
    }

    public function resolveCoupon($coupon, $packagePrice){
        if($coupon->type == "fixed"){
            $packagePrice -= $coupon->amount;
        }else{
            $packagePrice = $packagePrice * (1 - $coupon->amount / 100);	
        }
        return $packagePrice;
    }



}
