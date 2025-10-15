<?php
namespace App\Services;

class PaymentService
{
    // Simulate payment: randomly success or failure
    public function process(float $amount): array
    {
        // 80% success chance
        $success = (rand(1,100) <= 80);
        if ($success) {
            return ['status'=>'success','transaction_id'=>uniqid('txn_')];
        }
        return ['status'=>'failed','error'=>'Card declined'];
    }
}
