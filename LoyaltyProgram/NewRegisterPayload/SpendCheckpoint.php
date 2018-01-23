<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use App\LoyaltyProgram\Checkpoint;
use App\LoyaltyProgram\Condition;
use App\LoyaltyProgram\RecordFlow;
use App\Models\Site\Service;

class SpendCheckpoint extends Checkpoint
{
    /**
     * @var Service
     */
    public $service;

    /**
     * @var string
     */
    public $account;

    /**
     * @var int
     */
    public $amount;

    /**
     * SpendCheckpoint constructor.
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

        parent::__construct();
    }

    protected function condition(): Condition
    {
        return new SpendCondition($this->service);
    }

    protected function record(): RecordFlow
    {
        return new SpendRecord($this->service, $this->account, $this->amount);
    }
}