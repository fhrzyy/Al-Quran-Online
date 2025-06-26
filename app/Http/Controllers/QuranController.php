<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class QuranController extends Controller
{

    public function index()
    {
        $surah = Http::get('https://equran.id/api/surat')->json();
        return view('quran.index', compact('surah'));
    }

    public function getSurah()
    {
        $response = Http::get('https://equran.id/api/surat');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], 500);
    }

    public function showSurah($id)
{
    $response = Http::get('https://equran.id/api/surat/' . $id);
    $surah = $response->json();

    $all = Http::get('https://equran.id/api/surat');
    $allSurah = $all->json();

    return view('quran.detail', compact('surah', 'allSurah'));

}




}
