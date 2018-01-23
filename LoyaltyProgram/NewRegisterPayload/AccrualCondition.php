<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use Carbon\Carbon;
use App\LoyaltyProgram\Condition;
use App\Models\Order;
use App\Models\OrderService;
use Illuminate\Support\Collection;

class AccrualCondition extends Condition
{
    public $date_from = '12.01.2018';
    public $date_to = '31.12.2018';

    public $once = true;

    /** @var array  */
    protected $available_categories = [
        16, // Электроэнергия
        17, // Газоснабжение
        18, // Водоснабжение
        19, // Квартплата
    ];

    /** @var Order */
    protected $order;


    public function __construct(Order $order)
    {
        $this->order = $order;

        $this->date_from = Carbon::createFromFormat('d-m-Y', str_replace('.', '-', $this->date_from));
        $this->date_to = Carbon::createFromFormat('d-m-Y', str_replace('.', '-', $this->date_to));
    }

    /**
     * @return bool
     */
    public function handle(): bool
    {
        if (!(new Carbon())->between($this->date_from, $this->date_to)) {
            return false;
        }

        if (!($this->order instanceof Order)) {
            return false;
        }

        /** @var Collection $services */
        $services = $this->order->services;

        if (
            (new Carbon())->between($this->date_from, $this->date_to) &&
            $this->order->user &&
            $services->isNotEmpty() &&
            $this->hasSuitableService($services) &&
            $this->dontHaveEarlierPayments()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection $services
     * @return bool
     */
    protected function hasSuitableService(Collection $services): bool
    {
        $categories = $this->available_categories;

        $search = function ($item) use ($categories) {
            /** @var OrderService $item */
            return (
                in_array($item->service->main_category_id, $categories) &&
                $item->amount / 100 >= 1000
            );
        };

        return $services->search($search) !== false;
    }

    /**
     * @return bool
     */
    protected function dontHaveEarlierPayments(): bool
    {
        return $this->order->services->every(function ($orderService) {

            $earlierOrderServices = OrderService::query()
                ->with('order')
                ->where('account', $orderService->account)
                ->where('service_id', $orderService->service_id)
                ->whereHas('order', function ($query) {
                    $query->whereNotNull('user_id');
                })
                ->get();

            /** @var Collection $earlierOrderServices */
            if ($earlierOrderServices->count() <= 0) {
                return true;
            } else {
                return $earlierOrderServices->every(function ($earlierOrderService) use ($orderService) {
                    /** @var OrderService $paymentOrderService */
                    return !(
                        in_array($earlierOrderService->status, [Order::STATUS_FINISHED, Order::STATUS_PAID]) &&
                        $earlierOrderService->id !== $orderService->id
                    );
                });
            }
        });
    }
}