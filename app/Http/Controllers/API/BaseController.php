<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Denpa\Bitcoin\Client as BitcoinClient;

class BaseController extends Controller
{

    protected $bitcoind;

    function __construct() {
        try {
            $this->bitcoind = new BitcoinClient('http://someuser:somepassword@localhost:8332/');
        }
        catch(GuzzleHttp\Exception\ConnectException $e) {
            dd($e->getMessage());
        }
    }
    
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function test() {
        $bitcoind = new BitcoinClient('http://someuser:somepassword@localhost:8332/');
        
        // dd(bitcoind()->wallet('test.dat')->getBalance()->get());
        return $bitcoind->wallet('test')->getWalletInfo()->get();
        
        $hash = '00000000000000000c7f188ea917c0cf33b3b191d0eac75d95ee9bc5333c01d7';
        $block = $bitcoind->getBlock($hash);
        // echo $block->get('tx.0');die;
        return response()->json($block->get());
    }
}
