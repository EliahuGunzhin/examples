<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\LoyaltyProgram\AccountFlow;
use App\LoyaltyProgram\NewRegisterPayload\SpendCondition;
use App\LoyaltyProgram\ReductionCalculate;
use App\Models\Site\Service;
use App\Models\Site\User;
use Illuminate\Http\Request;

class LoyaltyProgram extends Controller
{
    public function getAccountSummary(User $user)
    {
        $flow = new AccountFlow($user);

        return Response::success($flow->getSummary());
    }

    public function getAccountFlow(User $user, Request $request)
    {
        $page = $request->get('page', 1);

        $flow = new AccountFlow($user);

        $paginator = $flow->getPageList($page);
        $summary = $flow->getSummary();

        return Response::success([
            'items' => $paginator->items(),
            'total' => $paginator->total(),
            'summary' => $summary
        ]);
    }

    public function getServiceReduction(User $user, Service $service, int $fee): Response
    {
        if ($fee <= 0) {
            return $this->responseNotAvailable('Fee amount must be above zero');
        }

        $flow = new AccountFlow($user);

        $summary = $flow->getSummaryPoint(AccountFlow::POINT_TYPE_NEW_REGISTER_PAYLOAD);

        if ($summary <= 0) {
            return $this->responseNotAvailable('User don\'t have bonuses');
        }

        $condition = new SpendCondition($service);

        if (!$condition->handle()) {
            return $this->responseNotAvailable('Service is not available for fees reduction');
        }

        $calculate = new ReductionCalculate($service);

        $percent = $calculate->get();

        if ($percent <= 0) {
            return $this->responseNotAvailable('Service is not available for fees reduction');
        }

        $maxReduction = $fee * $percent / 100;

        $reduction = min($maxReduction, $summary);

        return Response::success([
            'available' => true,
            'reduction' => $reduction,
            'percent' => $percent,
        ]);
    }

    protected function responseNotAvailable(string $description): Response
    {
        return Response::success([
            'available' => false,
            'description' => $description
        ]);
    }
}
