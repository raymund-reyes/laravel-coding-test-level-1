<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class CoinDeskServices {

    private string $url;

    public function __construct () {
        $this->url = config('services.coindesk.url');
    }

    public function priceIndex()
    {
        $response = Http::acceptJson()
            ->get($this->url);

        $response->throw();

        return $response->json();
    }
}
