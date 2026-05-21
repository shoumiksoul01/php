<?php

declare(strict_types=1);

class Account
{
    private int $accountNumber;
    private string $holderName;
    private float $balance;

    public function __construct(
        int $accountNumber,
        string $holderName,
        float $balance
    ) {
        $this->accountNumber = $accountNumber;
        $this->holderName = $holderName;
        $this->balance = $balance;
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            echo "Invalid deposit amount\n";
            return;
        }

        $this->balance += $amount;

        echo "$amount deposited successfully\n";
    }

    public function withdraw(float $amount): bool
    {
        if ($amount <= 0) {
            echo "Invalid withdrawal amount\n";
            return false;
        }

        if ($amount > $this->balance) {
            echo "Insufficient balance\n";
            return false;
        }

        $this->balance -= $amount;

        return true;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getHolderName(): string
    {
        return $this->holderName;
    }

    public function getAccountNumber(): int
    {
        return $this->accountNumber;
    }
}

class Transaction
{
    private Account $sender;
    private Account $receiver;
    private float $amount;

    public function __construct(
        Account $sender,
        Account $receiver,
        float $amount
    ) {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->amount = $amount;
    }

    public function getSummary(): string
    {
        return
            "Transferred {$this->amount} from " .
            $this->sender->getHolderName() .
            " to " .
            $this->receiver->getHolderName();
    }
}

class Bank
{
    private array $transactions = [];

    public function transfer(
        Account $sender,
        Account $receiver,
        float $amount
    ): bool {

        if ($amount <= 0) {
            echo "Transfer amount must be positive\n";
            return false;
        }

        $success = $sender->withdraw($amount);

        if (!$success) {
            return false;
        }

        $receiver->deposit($amount);

        $transaction = $this->createTransaction(
            $sender,
            $receiver,
            $amount
        );

        $this->transactions[] = $transaction;

        return true;
    }

    public function createTransaction(
        Account $sender,
        Account $receiver,
        float $amount
    ): Transaction {

        return new Transaction(
            $sender,
            $receiver,
            $amount
        );
    }

    public function showTransactions(): void
    {
        echo "\n===== TRANSACTION HISTORY =====\n";

        foreach ($this->transactions as $transaction) {
            echo $transaction->getSummary() . "\n";
        }
    }
}

$account1 = new Account(
    101,
    "Shoumik",
    5000
);

$account2 = new Account(
    102,
    "Mitu",
    3000
);

$bank = new Bank();

echo "Initial Balances\n";

echo $account1->getHolderName() .
    ": " .
    $account1->getBalance() .
    "\n";

echo $account2->getHolderName() .
    ": " .
    $account2->getBalance() .
    "\n";

echo "\n===== TRANSFER STARTED =====\n";

$bank->transfer(
    $account1,
    $account2,
    1500
);

echo "\nFinal Balances\n";

echo $account1->getHolderName() .
    ": " .
    $account1->getBalance() .
    "\n";

echo $account2->getHolderName() .
    ": " .
    $account2->getBalance() .
    "\n";

$bank->showTransactions();

?>