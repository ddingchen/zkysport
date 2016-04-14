<?php

namespace App\Http\Controllers;

use App\SellProduction;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Wechat;

class SellController extends Controller
{
    public function index()
    {
        $productions = SellProduction::all();
        return view('sell', compact('productions'));
    }

    public function history()
    {
        $informations = User::inSession()->seller->soldInformations;
        $records = [];
        foreach ($informations as $index => $info) {
            // $record['name'] = $info['name'];
            // $record['tel'] = $info['tel'];
            // $record['amount'] = ;
            $records[] = [
                'name' => $this->nameHide($info->name),
                'tel' => $this->telHide($info->tel, '****', 3, 4),
                'amount' => $info->activity->ticket_price,
            ];
        }
        return view('sell-history', compact('records'));
    }

    public function getSoldCount()
    {
        $informations = User::inSession()->seller->soldInformations;
        $count = $informations->count();
        $response = new StreamedResponse(function () use ($count) {
            echo "data: $count\n\n";
            ob_flush();
            flush();
            sleep(5);
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;
    }

    public function getQRCode(Request $request)
    {
        $this->validate($request, [
            'production_id' => 'required|integer|max:999',
        ]);
        \Log::info(User::inSession());
        \Log::info(User::inSession()->seller);
        $sellerId = User::inSession()->seller->id;
        $productionId = $request->input('production_id');
        $scene = str_pad($sellerId, 3, '0', STR_PAD_LEFT) . str_pad($productionId, 3, '0', STR_PAD_LEFT);
        $qrcode = Wechat::qrcode();
        $result = $qrcode->temporary(intval($scene), 1 * 24 * 3600);
        $ticket = $result->ticket;
        // qrcode url
        return $qrcode->url($ticket);

    }

    public function login()
    {
        return view('sell-auth');
    }

    public function storeName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
        ]);
        $user = User::inSession();
        $user->realname = $request->input('name');
        $user->save();
        return redirect('sell');
    }

    private function telHide($tel)
    {
        if (!$tel) {
            return '';
        }
        return substr_replace($tel, '****', 3, 4);
    }

    private function nameHide($name)
    {
        if (!$name) {
            return '';
        }
        $strlen = mb_strlen($name, 'utf-8');
        if ($strlen == 1) {
            return $name;
        }
        $firstStr = mb_substr($name, 0, 1, 'utf-8');
        $lastStr = mb_substr($name, -1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }
}
