<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use Carbon\Carbon;
use App\LoyaltyProgram\Checkpoint;
use App\LoyaltyProgram\Condition;
use App\LoyaltyProgram\RecordFlow;
use App\Models\Order;
use App\Models\Site\LoyaltyProgramNotification;
use App\Models\Site\User;

class AccrualCheckpoint extends Checkpoint
{
    /**
     * @var Order
     */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

        parent::__construct();
    }

    public function handle(User $user)
    {
        if (!parent::handle($user)) {
            return false;
        }

        $notification = new LoyaltyProgramNotification();

        $notification->user_id = $user->id;
        $notification->type = $this->record->type;
        $notification->data = json_encode([
            'amount' => $this->record->amount,
            'description' => $this->record->description(),
            'date_to_burn' => Carbon::now()->addMonths(3)->format('d.m.Y'),
        ]);

        $notification->save();

        return true;
    }

    protected function condition(): Condition
    {
        return new AccrualCondition($this->order);
    }

    protected function record(): RecordFlow
    {
        return new AccrualRecord($this->order);
    }
}