<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surah['nama_latin'] }} - Al-Qur'an Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Amiri&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        h1 {
            font-size: 32px;
            text-align: center;
            margin-bottom: 5px;
        }

        .arabic {
            font-family: 'Amiri', serif;
            font-size: 30px;
            direction: rtl;
            text-align: right;
            margin-bottom: 10px;
        }

        .ayat-card {
            background-color: #fff;
            border-left: 5px solid #00a884;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .nomor {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .terjemahan {
            font-style: italic;
            font-size: 14px;
            color: #555;
        }

        .info {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        audio {
    outline: none;
    border-radius: 4px;
    background-color: #f5f5f5;
}

a:hover {
    opacity: 0.9;
    transform: scale(1.03);
    transition: all 0.2s ease;
}

body.dark-mode {
    background-color: #1e1e1e;
    color: #e0e0e0;
}

body.dark-mode .ayat-card {
    background-color: #2c2c2c;
    border-left-color: #00c69f;
}

body.dark-mode .terjemahan {
    color: #ccc;
}

body.dark-mode .info {
    color: #aaa;
}

body.dark-mode a {
    background-color: #00c69f !important;
    color: white !important;
}

    </style>
</head>

<body id="body">

    <button id="toggle-dark" style="position: fixed; top: 20px; right: 20px; z-index: 999; padding: 8px 12px; border: none; border-radius: 5px; background-color: #00a884; color: white; cursor: pointer;">
        🌙 Dark Mode
    </button>

    <h1>{{ $surah['nama_latin'] }} ({{ $surah['nama'] }})</h1>
    <div class="info">
        Jumlah Ayat: {{ $surah['jumlah_ayat'] }} | Tempat Turun: {{ $surah['tempat_turun'] }}
    </div>

<form id="searchForm" onsubmit="return redirectToSurah(event)" style="text-align: center; margin: 20px 0;">
    <input type="text" id="searchInput" placeholder="Cari nama surat..." style="padding: 10px; width: 60%; border-radius: 5px; border: 1px solid #ccc;">
    <button type="submit" style="padding: 10px 15px; border: none; border-radius: 5px; background-color: #00a884; color: white; margin-left: 10px;">🔍 Cari</button>
</form>

    {{-- 🔻 TOMBOL NAVIGASI DISINI --}}
    @php
        $current = $surah['nomor'];
        $prev = $current > 1 ? $current - 1 : null;
        $next = $current < 114 ? $current + 1 : null;

        // Ambil nama surat dari $allSurah
        $getSurahName = function($nomor) use ($allSurah) {
            foreach ($allSurah as $s) {
                if ($s['nomor'] == $nomor) {
                    return $s['nama_latin'];
                }
            }
            return null;
        };
    @endphp

    <div style="text-align: center; margin-bottom: 30px;">
        @if($prev)
            <a href="{{ url('/surah/' . $prev) }}"
               style="margin-right: 20px; padding: 10px 15px; background-color: #00a884; color: white; border-radius: 6px; text-decoration: none;">
                ← {{ $getSurahName($prev) }}
            </a>
        @endif

        @if($next)
            <a href="{{ url('/surah/' . $next) }}"
               style="padding: 10px 15px; background-color: #00a884; color: white; border-radius: 6px; text-decoration: none;">
                {{ $getSurahName($next) }} →
            </a>
        @endif
    </div>

    @if(isset($surah['audio']))
    <audio controls style="width: 100%; margin-bottom: 20px;">
        <source src="{{ $surah['audio'] }}" type="audio/mpeg">
        Browser tidak mendukung pemutar audio.
    </audio>
@endif

{{-- 🔻 AYAT MULAI DARI SINI --}}
@foreach ($surah['ayat'] as $ayat)
    <div class="ayat-card">
        <div class="nomor">Ayat {{ $ayat['nomor'] }}</div>
        <div class="arabic">{{ $ayat['ar'] }}</div>
        <div class="terjemahan">{{ $ayat['idn'] }}</div>
    </div>
@endforeach

<script>
    const toggleBtn = document.getElementById('toggle-dark');
    const body = document.getElementById('body');

    // Load mode dari localStorage
    if (localStorage.getItem('mode') === 'dark') {
        body.classList.add('dark-mode');
        toggleBtn.innerText = '🌞 Light Mode';
    }

    toggleBtn.addEventListener('click', function () {
        body.classList.toggle('dark-mode');
        if (body.classList.contains('dark-mode')) {
            toggleBtn.innerText = '🌞 Light Mode';
            localStorage.setItem('mode', 'dark');
        } else {
            toggleBtn.innerText = '🌙 Dark Mode';
            localStorage.setItem('mode', 'light');
        }
    });
</script>


</body>
<script>
    const allSurah = @json($allSurah); // Data semua surat dari controller

    function redirectToSurah(e) {
    e.preventDefault();

    const input = normalize(document.getElementById('searchInput').value);

    const found = allSurah.find(surah =>
        normalize(surah.nama_latin).startsWith(input) ||
        normalize(surah.nama_latin).includes(input)
    );

    if (found) {
        window.location.href = '/surah/' + found.nomor;
    } else {
        alert('Surah tidak ditemukan!');
    }
}

// Tambahkan fungsi bantu ini
function normalize(str) {
    return str.toLowerCase()
              .replace(/[-\s]/g, '') // hilangkan strip dan spasi
              .normalize("NFD")      // hilangkan tanda baca Latin (jika ada)
              .replace(/[\u0300-\u036f]/g, '');
}


</script>
</body>

</html>
