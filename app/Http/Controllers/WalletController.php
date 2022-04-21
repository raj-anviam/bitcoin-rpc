<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Denpa\Bitcoin\Exceptions\BadRemoteCallException;
use App\Models\Wallet;
use DB, Auth, Session;

class WalletController extends BaseController
{
    public function index() {
        try {

            $bitcoinWallets = $this->bitcoind->listWallets()->get();

            $bitcoinWallets  = (is_array($bitcoinWallets))? $bitcoinWallets: array($bitcoinWallets);
            $updateWallets = Wallet::whereNotIn('name', $bitcoinWallets)->update(['exists' => false]);
            
            $wallets = Wallet::where('exists', 1)->get();
            return view('wallets.index', compact('wallets'));
        }
        catch(BadRemoteCallException $e) {
            dd($e->getMessage());
        }
    }

    public function create() {
        return view('wallets.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required'
        ]);

        try {

            DB::beginTransaction();
            
            $wallet = $this->bitcoind->createWallet($data['name'])->get();
            
            $data['details'] = json_encode($this->bitcoind->wallet($data['name'])->getWalletInfo()->get());
            $data['user_id'] = Auth::user()->id;
            Wallet::create($data);
            DB::commit();

            Session::flash('success', 'Wallet Created');
            return redirect()->route('wallets.index');
        }
        catch(BadRemoteCallException $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function show($id) {
        $data = Wallet::findOrFail($id);
        
        $data = $this->bitcoind->wallet($data->name)->getWalletInfo()->get();

        Wallet::whereId($id)->update(['details' => $data]);
        return view('modal-body', compact('data'));
    }
    
    public function listTransactions($walletId) {
        $data = new \StdClass;
        
        $wallet = Wallet::findOrFail($walletId);
        
        $data = $this->bitcoind->wallet($wallet->name)->listTransactions()->get();
        $addresses = $this->bitcoind->wallet('test_wallet')->getAddressesByLabel('')->get();
        
        // $data = $this->bitcoind->wallet('test_wallet')->listReceivedByAddress()->get();
        return view('transactions.index', compact('data', 'walletId', 'addresses'));

    }

    public function getNewAddress($walletId) {
        $wallet = Wallet::findOrFail($walletId);
        $data = $this->bitcoind->wallet($wallet->name)->getNewAddress()->get();
        return $data;
    }
    
}
