<?php
declare(strict_types=1);
class Account{
    private int $accountnumber;
    private float $balance;
    private string $holdername;
    public function __construct(int $accountnumber, float $balance, string $holdername){

        $this->accountnumber= $accountnumber;
        $this->balance = $balance;
        $this->holdername= $holdername;
    }
    public function deposit(float $amount):void
    {
        if($amount <= 0){
            echo "Invalid deposit amount\n";
            return;
        }
        $this->balance += $amount;
        echo "Deposited Succesfully\n";
    }
    public function withdraw(float $amount):bool{
        if($amount <= 0){
            echo "Invalid withdrawal amount\n";
            return false;
        }
        if ($amount > $this->balance) {
            echo "Insufficient balance\n";
            return false;
        }
        $this->balance -= $amount;
        echo "Withdrawal successful\n";
        return true;
    }
    public function getbalance():float {
        return $this->balance;

    }
    public function getholderName(): string
    {
        return $this->holdername;
    }

    public function getAccountNumber(): int
    {
        return $this->accountnumber;
    }


}
class Transaction{
    private Account $sender;
    private Account $reciever;
    private float $amount;
    public function __construct(Account $sender, Account $reciever, float  $amount){
        $this->sender = $sender;
        $this->reciever = $reciever;
        $this->amount  = $amount;
    }
    public function getsummary(): string{
        return "Transfered {this->amount} from"
        . $this->sender->getholdername()."to"
        . $this->reciever->getholdername();

    }
}
class Bank{
    private array $transaction= [];
    public function transfer(Account $sender, Account $reciever, float $amaount): void{
        if($sender->withdraw($amaount)){
            $reciever->deposit($amaount);
            $transaction = new Transaction($sender, $reciever, $amaount);
            $this->transaction[] = $transaction;
            echo "Transfer successful\n";
        }else{
            echo "Transfer failed\n";
        }
    }
    public function showtransactions():  void{
        foreach($this->transaction as $transaction){
            echo $transaction -> getsummary()."\n";
        }
    }

}
$account1 = new Account(
    101,
    
    5000,
    "Shoumik",
);

$account2 = new Account(
    102,
    
    3000,
    "Mitu",
);

$bank = new Bank();

echo "Initial Balances\n";

echo $account1->getholdername() .
    ": " .
    $account1->getbalance() .
    "\n";

echo $account2->getholdername() .
    ": " .
    $account2->getbalance() .
    "\n";

echo "\n===== TRANSFER STARTED =====\n";

$bank->transfer(
    $account1,
    $account2,
    1500
);

echo "\nFinal Balances\n";

echo $account1->getholdername() .
    ": " .
    $account1->getbalance() .
    "\n";

echo $account2->getholdername() .
    ": " .
    $account2->getbalance() .
    "\n";

$bank->showtransactions();

?>
?>