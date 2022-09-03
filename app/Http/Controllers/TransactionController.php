<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Denpa\Bitcoin\Exceptions\BadRemoteCallException;
use App\Models\Wallet;
use App\Models\Batch;
use App\Models\Transaction;
use DB, Auth, Session;

class TransactionController extends BaseController
{
    public function addBatch(Request $request) {

        $data = $request->validate([
            'number_of_wallets' => 'required|min:1',
            'amount' => 'required',
        ]);

        try {
            $data['user_id'] = Auth::user()->id;
            
            DB::beginTransaction();
            $batch = Batch::create($data);
            // DB::commit();

            $this->processBatch($batch);
            
            Session::flash('success', 'added');
            return redirect()->back();
        }
        catch(\Exception $e) {
            
            DB::rollback();
            dd($e->getMessage());
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }   
    }

    public function processBatch(Batch $batch) {

        // get avalable balance
        $balance = $this->bitcoind->wallet('test_wallet')->listUnspent()->get();

        if(!isset($balance[0]))
            $balance = array(0 => $balance);

        // check available balance
        if(!count($balance))
            throw new \Exception('Unspent Not Found');

        $rawTransactionPayload = array(
            array(
                'txid' => $balance[0]['txid'],
                'vout' => $balance[0]['vout']
            ),
        );

        $defaultWallet = $this->bitcoind->wallet('test_wallet')->listReceivedByAddress()->get();
        $privateKey = $this->bitcoind->dumpPrivKey($defaultWallet['address'] ?? $defaultWallet[0]['address'])->get();

        
        $wallets = $this->bitcoind->listWallets()->get();
        $totalWallets = is_array($wallets)? count($wallets): 1;

        unset($wallets[0]);
        unset($wallets[3]);

        // check account has enough wallets
        if($totalWallets >= $batch->number_of_wallets) {

            $addresses = [];
            $commission = 0;
            foreach($wallets as $key => $wallet) {
                $currentWalletAddress = $this->bitcoind->wallet($wallet)->listReceivedByAddress()->get();

                if(!count($currentWalletAddress))
                    $address = $this->bitcoind->wallet($wallet)->getNewAddress()->get();
                else
                    $address = $currentWalletAddress['address'] ?? $currentWalletAddress[0]['address'];
                    
                    $amount = ($balance[0]['amount'] / count($wallets));

                    $commission =+ (5 / 100) * $amount;
                    $totalCommission =+ (5 / 100) * $amount;
                    
                    $addresses[] = array($address => number_format($amount - $commission, 8));

                    $transactions[] = array($address => $wallet, 'type' => 1);
                }
                
                $address = $this->bitcoind->wallet('commissions')->getNewAddress()->get();
                
                $transactions[] = array($address => 'commissions', 'type' => 2);

                $addresses[] = array($address => number_format($totalCommission, 8 ));
                
                // make transaction
                $rawTransactionOutput = $addresses;

                $unsigedHash = $this->bitcoind->createRawTransaction($rawTransactionPayload, $rawTransactionOutput)->get();
                $rawTransactionPayload[0]['scriptPubKey'] = $balance[0]['scriptPubKey'];
                $hash = $this->bitcoind->signRawTransactionWithKey($unsigedHash, array($privateKey))->get();
                $txid = $this->bitcoind->sendRawTransaction($hash['hex'])->get();
        }

        $this->store($txid, $transactions, $batch->id);
    }

    public function store($txid, $transactions, $batchId) {
        $transaction = $this->bitcoind->wallet('test_wallet')->getTransaction($txid)->get()['details'];

        foreach($transaction as $key => $value) {

            $walletId = Wallet::whereName($transactions[$key][$value['address']])->value('id');

            Transaction::create([
                'wallet_id' => $walletId,
                'batch_id' => $batchId,
                'type' => $transactions[$key]['type'],
                'amount' => $value['amount'],
                'address' => $value['address'],
                'txid' => $txid,
                'btc_details' => json_encode($value),
            ]);
        }

    }
}
