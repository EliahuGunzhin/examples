<?php

namespace App\LoyaltyProgram\NewRegisterPayload;

use App\LoyaltyProgram\Condition;
use App\Models\Site\Category;
use App\Models\Site\Service;

class SpendCondition extends Condition
{
    /**
     * @var Service
     */
    public $service;

    public $available_categories = [
        2, // Охрана
        3, // Обучение - Другое
        4, // Обучение - Дополнительное образование
        5, // Обучение - ВУЗы, колледжи
        6, // Обучение - Школы, гимназии, лицеи
        7, // Обучение - Дошкольные учреждения
        8, // Обучение
        9, // Телефон
        10, // Кабельное ТВ
        11, // Интернет
        15, // Домофон
        16, // Электричество
        17, // Газ
        21, // ЖКУ
    ];

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function handle(): bool
    {
        return $this->isSuitableService();
    }

    protected function isSuitableService()
    {
        if ($this->service->fee->rate <= 0) {
            return false;
        }

        $categories = $this->available_categories;

        $result = $this->service->categories->filter(function ($item) use ($categories) {
            /** @var Category $item */
            return in_array($item->id, $categories);
        });

        return $result->isNotEmpty();
    }

}