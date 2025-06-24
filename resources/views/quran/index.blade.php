<h1>Daftar Surah</h1>
<ul>
    @foreach ($surah as $s)
        <li>{{ $s['nomor'] }}. {{ $s['nama'] }} ({{ $s['nama_latin'] }})</li>
    @endforeach
</ul>
