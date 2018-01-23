<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use App\LoyaltyProgram\AccountFlow;
use App\LoyaltyProgram\RecordFlow;
use App\Models\LoyaltyProgramAccountFlow;
use App\Models\Site\Service;

class SpendRecord extends RecordFlow
{
    public $point = AccountFlow::POINT_TYPE_NEW_REGISTER_PAYLOAD;
    public $type = LoyaltyProgramAccountFlow::TYPE_SPENT;

    /** @var Service */
    protected $service;

    /** @var string  */
    protected $account;


    /**
     * SpendRecord constructor.
     *
     * @param Service $service
     * @param string $account
     * @param int $amount
     */
    public function __construct(Service $service, string $account, int $amount)
    {
        $this->service = $service;
        $this->account = $account;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return "Списание бонусов в счет оплаты комиссии за услугу \"{$this->service->name}\" с лицевым счетом {$this->account} .";
    }
}