<?php
namespace App;

class AddingService
{
    private float $total = 0;

    public function add(float $amount): void
    {
        $this->total += $amount;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
