<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

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
    $surah = Http::get("https://equran.id/api/surat/{$id}")->json();
    $allSurah = Http::get("https://equran.id/api/surat")->json();

    return view('quran.detail', [
        'surah' => $surah,
        'allSurah' => $allSurah
    ]);
}


}
