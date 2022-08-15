<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessJokeEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ChuckNorrisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (request('emails') && $joke = $this->getJoke()) {
            $unsortedEmailList = request('emails');
            $emailsSortedByDomain = $this->sortEmails($unsortedEmailList);
            $emailsSortedByDomain = array_values($emailsSortedByDomain);

            ProcessJokeEmail::dispatch($emailsSortedByDomain, $joke);

            return response()->json(json_encode(
                [
                    'emailList' => $emailsSortedByDomain,
                    'joke' => $joke
                ]
            ));
        } else {
            return response()->json('Server error occurred!', 400);
        }
    }

    protected function sortEmails(array $arr): array
    {
        $emails = array_unique($arr);
        $splitArray = [];
        foreach ($emails as $email) {
            $splitEmail = explode('@', $email);
            $pair = ['name' => $splitEmail[0], 'domain' => $splitEmail[1]];
            array_push($splitArray, $pair);
        }
        $collection = collect($splitArray)->sortBy('name')->sortBy('domain');
        return $collection->map(function ($item){
           return $item['name'] . '@' . $item['domain'];
        })->toArray();
    }

    protected function getJoke(): ?string
    {
       if(Http::get('http://api.icndb.com/jokes/random')) {
           $call = Http::get('http://api.icndb.com/jokes/random')->json();
           return $call['value']['joke'];
       }
       return null;
    }
}
