<?php

namespace Tests\Unit;

use App\Models\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function test_income_categories_are_defined(): void
    {
        $this->assertNotEmpty(Transaction::$incomeCategories);
        $this->assertContains('Gaji', Transaction::$incomeCategories);
    }

    public function test_expense_categories_are_defined(): void
    {
        $this->assertNotEmpty(Transaction::$expenseCategories);
        $this->assertContains('Makanan & Minuman', Transaction::$expenseCategories);
    }
}
