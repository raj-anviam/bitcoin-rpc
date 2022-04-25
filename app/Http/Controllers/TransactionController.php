<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Denpa\Bitcoin\Exceptions\BadRemoteCallException;
use App\Models\Wallet;
use App\Models\Batch;
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
        $privateKey = $this->bitcoind->dumpPrivKey($defaultWallet['address'])->get();

        
        $wallets = $this->bitcoind->listWallets()->get();
        $totalWallets = is_array($wallets)? count($wallets): 1;

        // check account has enough wallets
        if($totalWallets >= $batch->number_of_wallets) {

            $addresses = [];
            foreach($wallets as $key => $wallet) {
                $currentWalletAddress = $this->bitcoind->wallet($wallet)->listReceivedByAddress()->get();

                if(!count($currentWalletAddress))
                    $address = $this->bitcoind->wallet($wallet)->getNewAddress()->get();
                else
                    $address = $currentWalletAddress['address'];
                    
                    // make transaction
                    $rawTransactionOutput = array(array($address => 0.00161766));
                    // dd($rawTransactionOutput);
                    
                    $unsigedHash = $this->bitcoind->createRawTransaction($rawTransactionPayload, $rawTransactionOutput)->get();
                    $rawTransactionPayload[0]['scriptPubKey'] = $balance[0]['scriptPubKey'];
                    $hash = $this->bitcoind->signRawTransactionWithWallet($unsigedHash)->get();
                    $txid = $this->bitcoind->sendRawTransaction($hash['hex'])->get();
                    // dd($txid);
            }
        }

        dd('here');
    }
}
