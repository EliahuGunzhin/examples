<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use App\LoyaltyProgram\AccountFlow;
use App\LoyaltyProgram\RecordFlow;
use App\Models\LoyaltyProgramAccountFlow;
use App\Models\Order;
use App\Models\Site\Service;

class AccrualRecord extends RecordFlow
{
    public $point = AccountFlow::POINT_TYPE_NEW_REGISTER_PAYLOAD;
    public $type = LoyaltyProgramAccountFlow::TYPE_ACCRUED;
    public $amount = 100000;

    protected $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        /** @var Service $service */
        $service = $this->order->services->first()->service;

        return "Начисление бонусов за регистрацию и оплату услуги \"{$service->name}\".";
    }
}