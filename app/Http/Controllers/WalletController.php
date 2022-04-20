<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Denpa\Bitcoin\Exceptions\BadRemoteCallException;
use App\Models\Wallet;
use DB, Auth;

class WalletController extends BaseController
{
    public function index() {
        try {
            $wallets = Wallet::all();
            // return $this->bitcoind->wallet('test')->getWalletInfo()->get();
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
            
            $data['details'] = json_encode($wallet);
            $data['user_id'] = Auth::user()->id;
            Wallet::create($data);
            DB::commit();

            return redirect()->route('wallets.index');
        }
        catch(BadRemoteCallException $e) {
            dd($e->getMessage());
        }
    }
    
}
