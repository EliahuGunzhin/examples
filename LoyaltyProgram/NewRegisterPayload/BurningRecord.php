<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use App\LoyaltyProgram\AccountFlow;
use App\LoyaltyProgram\RecordFlow;
use App\Models\LoyaltyProgramAccountFlow;

class BurningRecord extends RecordFlow
{
    public $point = AccountFlow::POINT_TYPE_NEW_REGISTER_PAYLOAD;
    public $type = LoyaltyProgramAccountFlow::TYPE_BURNED;


    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return "Сгорание бонусов по истечению срока действия.";
    }
}