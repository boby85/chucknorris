<?php

namespace App\Http\Controllers;

use App\Models\ChuckNorris;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $unsorted = request('emails');
        $domainSorted = $this->domainSort($unsorted);

        return response()->json(json_encode(
            [
                "emailList" => $domainSorted,
                'joke' => $this->getJoke()
            ]
        ));
    }

    protected function domainSort(array $arr): array
    {
        $myArray = array_unique($arr);
        usort($myArray, function($a, $b){
            preg_match_all("/(.*)@(.*)\./", $a, $m1);
            preg_match_all("/(.*)@(.*)\./", $b, $m2);

            if(($cmp = strcmp($m1[2][0], $m2[2][0])) == 0) {
                return strcmp($m1[1][0], $m2[1][0]);
            } else {
                return ($cmp < 0 ? -1 : 1);
            }

        });
        return $myArray;
    }

    protected function getJoke(): string
    {
        $call = Http::get('http://api.icndb.com/jokes/random')->json();
        return $call['value']['joke'];
    }
}
