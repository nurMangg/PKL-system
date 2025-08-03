<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lembar Nilai Capaian Pembelajaran PKL</title>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      font-size: 12px;
      line-height: 1.4;
      margin: 0;
      padding: 20px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    thead {
  display: table-header-group;
}
tfoot {
  display: table-footer-group;
}
tr {
  page-break-inside: avoid;
}

    th, td {
      border: 1px solid black;
      padding: 4px;
      vertical-align: top;
    }
    .no-border {
      border: none;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
    .text-left {
      text-align: left;
    }
    .kop-surat {
      margin-bottom: 5px;
    }
    .kop-surat table {
      width: 100%;
      border: none;
    }
    .kop-surat td {
      border: none;
      vertical-align: middle;
    }
    .logo-left {
      width: 100px;
      text-align: center;
    }
    .logo-right {
      width: 100px;
      text-align: center;
    }
    .header-content {
      text-align: center;
    }
    .header-content h3 {
      margin: 0 0 2px 0;
      font-size: 14px;
    }
    .header-content h2 {
      margin: 0;
      font-size: 16px;
    }
    .header-content p {
      margin: 0;
      font-size: 10px;
    }
    .divider {
      margin: 20px 0;
    }
    .divider hr {
      border: 0;
      border-top: 1px solid #000;
      margin: 2px 0;
    }
    .main-title {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin: 20px 0;
    }
    .info-table {
      margin: 10px 0;
    }
    .info-table td {
      border: none;
      padding: 2px 4px;
    }
    .assessment-table {
      margin: 20px 0;
    }
    .assessment-table th {
      background-color: #f0f0f0;
      font-weight: bold;
      text-align: center;
    }
    .skala-table {
      width: 350px;
      margin: 10px 0;
    }
    .skala-table th {
      background-color: #f0f0f0;
      font-weight: bold;
    }
    .signature-section {
      margin: 50px 0;

    }
    .signature-box {
      width: 45%;
      text-align: left;
      border: none;
    }
    .signature-box-right {
      width: 45%;
      text-align: right;
      border: none;
    }
    .signature-line {
      text-decoration: underline;
      font-weight: bold;
      margin-top: 40px;
    }
    .page-break {
      page-break-before: always;
    }
    .footer-section {
      margin: 40px 0;
      text-align: center;
    }
    .footer-table {
      width: 100%;
      border: none;
    }
    .footer-table td {
      border: none;
      vertical-align: middle;
    }
    .footer-title {
      font-weight: bold;
      font-size: 13px;
      margin-bottom: 2px;
      letter-spacing: 1px;
    }
    .footer-content {
      font-size: 11px;
    }
    .logo-small {
      height: 80px;
      max-width: 100px;
    }
    .logo-medium {
      height: 50px;
      max-width: 100px;
    }
    .logo-footer {
      height: 48px;
      max-width: 80px;
    }
  </style>
</head>
<body>

  <!-- Kop Surat Pertama -->
  <div class="kop-surat">
    <table>
      <tr>
        <td class="logo-left">
          <img src="{{ public_path('assets/img/SMK_KARBAK.png') }}" alt="Logo SMK Karya Bhakti Brebes" class="logo-small">
        </td>
        <td class="header-content">
          <h3>YAYASAN PENDIDIKAN EKONOMI</h3>
          <h2>SMK KARYA BHAKTI BREBES</h2>
          <p>Jl. Taman Siswa No. 1 Telp. (0283) 671248, 673241 Fax. (0283) 67184 Brebes 52212<br>
          Website: www.smkkaryabhaktibbs.tk Email: karyabhakti.brebes@gmail.com</p>
        </td>
        <td class="logo-right">
          <img src="{{ public_path('assets/img/logodeltapas.jpg') }}" alt="Logo Kanan" style="height: 50px;">
        </td>
      </tr>
    </table>
  </div>

  <div class="divider">
    <hr>
    <hr style="border-top: 3px solid #000;">
  </div>

  <div class="main-title">
    LEMBAR NILAI CAPAIAN PEMBELAJARAN PKL<br>
    TAHUN PELAJARAN {{ $tahunAkademik->tahun_akademik ?? '2025/2026' }}
  </div>

  <div class="info-table">
    <table>
      <tr>
        <td style="width: 40%;">Bidang Keahlian</td>
        <td>: {{ $siswa->jurusan->jurusan ?? 'TEKNOLOGI INFORMASI' }}</td>
      </tr>
      <tr>
        <td>Program Keahlian</td>
        <td>: PENGEMBANGAN PERANGKAT LUNAK DAN GIM</td>
      </tr>
      <tr>
        <td>Konsentrasi Keahlian</td>
        <td>: REKAYASA PERANGKAT LUNAK</td>
      </tr>
      <tr>
        <td>Nama Institusi</td>
        <td>: {{ $penempatan->dudi->nama ?? '............................................................' }}</td>
      </tr>
      <tr>
        <td>Alamat Lengkap Institusi</td>
        <td>: {{ $penempatan->dudi->alamat ?? '............................................................' }}</td>
      </tr>
    </table>
  </div>

  <div class="assessment-table">
    <table>
      <thead>
        <tr>
          <th rowspan="2" style="width: 5%;">No</th>
          <th rowspan="2" style="width: 20%;">Elemen</th>
          <th colspan="2" rowspan="2" style="width: 35%;">Tujuan Pembelajaran</th>
          <th colspan="{{ $allStudents->count() }}" style="width: 35%;">Nama Siswa dan Nilai</th>
          <th rowspan="2" style="width: 5%;">Keterangan</th>
        </tr>
        <tr>
          @foreach($allStudents as $index => $student)
          <th>{{ $student->nama }}</th>
          @endforeach
        </tr>
        <tr>
          <th>1</th>
          <th>2</th>
          <th colspan="2">3</th>
          @for($i = 4; $i <= 3 + $allStudents->count(); $i++)
          <th>{{ $i }}</th>
          @endfor
          <th>{{ 4 + $allStudents->count() }}</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @if($mainIndicators->count() > 0)
          @foreach($mainIndicators as $mainIndex => $mainIndicator)
            @foreach($mainIndicator->children as $subIndex => $subIndicator)
              <tr style="page-break-inside: avoid;">
                {{-- Kolom 1: No --}}
                <td>{{ $subIndex === 0 ? $no : '' }}</td>

                {{-- Kolom 2: Elemen --}}
                <td>{{ $subIndex === 0 ? $mainIndicator->indikator : '' }}</td>

                {{-- Kolom 3: Kode --}}
                <td>{{ $subIndicator->kode ?? ($mainIndex + 1) . '.' . ($subIndex + 1) }}</td>

                {{-- Kolom 4: Tujuan Pembelajaran --}}
                <td>{{ $subIndicator->indikator }}</td>

                {{-- Kolom 5-n: Nilai per siswa --}}
                @foreach($allStudents as $student)
                  @php
                    $nilai = '-';
                    if (isset($assessmentData[$student->nis])) {
                        $studentRecords = $assessmentData[$student->nis];
                        foreach ($studentRecords as $record) {
                            if (trim($record->indikator) === trim($subIndicator->indikator)) {
                                $nilai = $record->is_nilai;
                                break;
                            }
                        }
                    }
                  @endphp
                  <td class="text-center">{{ $nilai }}</td>
                @endforeach

                {{-- Kolom akhir: Keterangan --}}
                <td></td>
              </tr>
            @endforeach
            @php $no++; @endphp
          @endforeach
        @else
          <tr>
            <td colspan="{{ 4 + $allStudents->count() }}" class="text-center">
              <strong>Tidak ada data indikator penilaian yang ditemukan.</strong><br>
              Silakan buat template penilaian terlebih dahulu.
            </td>
          </tr>
        @endif
      </tbody>

    </table>
  </div>

  <div>
    <h4 style="margin-bottom: 5px;">SKALA PENILAIAN:</h4>
    <table class="skala-table">
      <tr>
        <th>Rentang Nilai</th>
        <th>Predikat</th>
        <th>Keterangan</th>
      </tr>
      <tr>
        <td class="text-center">90 - 100</td>
        <td class="text-center">A</td>
        <td>Sangat Baik</td>
      </tr>
      <tr>
        <td class="text-center">80 - 89</td>
        <td class="text-center">B</td>
        <td>Baik</td>
      </tr>
      <tr>
        <td class="text-center">70 - 79</td>
        <td class="text-center">C</td>
        <td>Cukup</td>
      </tr>
      <tr>
        <td class="text-center">60 - 69</td>
        <td class="text-center">D</td>
        <td>Kurang</td>
      </tr>
      <tr>
        <td class="text-center">0 - 59</td>
        <td class="text-center">E</td>
        <td>Sangat Kurang</td>
      </tr>
    </table>
  </div>

  <div class="signature-section">
    <table style="width: 100%; border: none;">
      <tr>
        <td style="width: 70%; border: none;"></td>
        <td style="width: 30%; border: none; text-align: center;">
          @php
            \Carbon\Carbon::setLocale('id');
            $hari = \Carbon\Carbon::now()->isoFormat('dddd');
            $tanggal = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
          @endphp
          <div>{{ $hari }}, {{ $tanggal }}</div>
          <div>Pembimbing Institusi</div>
          <br><br><br>
          <div class="signature-line">
            {{ $penempatan->instruktur->nama ?? '........................................' }}
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- Halaman Kedua -->
  <div class="page-break">
    <!-- Kop Surat Kedua -->
    <div class="kop-surat">
        <table>
            <tr>
              <td class="logo-left">
                <img src="{{ public_path('assets/img/SMK_KARBAK.png') }}" alt="Logo SMK Karya Bhakti Brebes" class="logo-small">
              </td>
              <td class="header-content">
                <h3>YAYASAN PENDIDIKAN EKONOMI</h3>
                <h2>SMK KARYA BHAKTI BREBES</h2>
                <p>Jl. Taman Siswa No. 1 Telp. (0283) 671248, 673241 Fax. (0283) 67184 Brebes 52212<br>
                Website: www.smkkaryabhaktibbs.tk Email: karyabhakti.brebes@gmail.com</p>
              </td>
              <td class="logo-right">
                <img src="{{ public_path('assets/img/logodeltapas.jpg') }}" alt="Logo Kanan" style="height: 50px;">
              </td>
            </tr>
          </table>
    </div>

    <div class="divider">
      <hr>
      <hr style="border-top: 3px solid #000;">
    </div>

    <div class="main-title">
      ANGKET PKL<br>
      TAHUN PELAJARAN {{ $tahunAkademik->tahun_akademik ?? '2025/2026' }}
    </div>

    <div style="margin: 10px 0;">
      <h4 style="margin-bottom: 5px;"><strong>I. IDENTITAS</strong></h4>
      <table class="info-table">
        <tr>
          <td style="width: 40%;">1. Nama Institusi</td>
          <td style="width: 60%;">: {{ $penempatan->dudi->nama ?? '............................................................' }}</td>
        </tr>
        <tr>
          <td>2. Alamat</td>
          <td>: {{ $penempatan->dudi->alamat ?? '............................................................' }}</td>
        </tr>
        <tr>
          <td>3. Nama Pembimbing Institusi</td>
          <td>: {{ $penempatan->instruktur->nama ?? '............................................................' }}</td>
        </tr>
      </table>
    </div>

    <div style="margin: 10px 0;">
      <h4 style="margin-bottom: 5px;"><strong>II. KEBIJAKSANAAN SEKOLAH</strong></h4>
      <table class="info-table">
        @php
          $kebijaksanaanSekolah = $dataAngket->filter(function($item) {
              return str_contains(strtolower($item['soal']), 'waktu prakerin') ||
                     str_contains(strtolower($item['soal']), 'tata administrasi') ||
                     str_contains(strtolower($item['soal']), 'pembinaan');
          });
        @endphp
        @foreach($kebijaksanaanSekolah as $index => $item)
        <tr>
          <td style="width: 40%;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
          <td style="width: 60%;">: {{ $item['nilai'] }}</td>
        </tr>
        @endforeach
      </table>
    </div>

    <div style="margin: 10px 0;">
      <h4 style="margin-bottom: 5px;"><strong>III. KESIMPULAN</strong></h4>
      <table class="info-table">
        @php
          $kesimpulan = $dataAngket->filter(function($item) {
              return str_contains(strtolower($item['soal']), 'hal') &&
                     (str_contains(strtolower($item['soal']), 'positif') ||
                      str_contains(strtolower($item['soal']), 'negatif'));
          });
        @endphp
        @foreach($kesimpulan as $index => $item)
        <tr>
          <td style="width: 40%;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
          <td style="width: 60%;">: {{ $item['nilai'] }}</td>
        </tr>
        @endforeach
      </table>
    </div>

    <div style="margin: 10px 0;">
      <h4 style="margin-bottom: 5px;"><strong>IV. SARAN-SARAN</strong></h4>
      <table class="info-table">
        @php
          $saranSaran = $dataAngket->filter(function($item) {
              return str_contains(strtolower($item['soal']), 'perbaikan') ||
                     str_contains(strtolower($item['soal']), 'penyempurnaan');
          });
        @endphp
        @foreach($saranSaran as $index => $item)
        <tr>
          <td style="width: 40%;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
          <td style="width: 60%;">: {{ $item['nilai'] }}</td>
        </tr>
        @endforeach
      </table>
    </div>

    <div class="signature-section">
      <table style="width: 100%; border: none;">
        <tr>
          <td class="signature-box">
            <strong>Mengetahui:</strong><br>
            Kepala Institusi/Pimpinan,<br><br><br><br><br>
            <div class="signature-line">
              {{ $penempatan->instruktur->dudi->nama_pimpinan ?? '........................................' }}
            </div>
          </td>
          <td style="width: 10%; border: none;"></td>
          <td class="signature-box-right">
            {{ $hari }}, {{ $tanggal }}<br>
            Pembimbing Institusi,<br><br><br><br><br>
            <div class="signature-line">
              {{ $penempatan->instruktur->nama ?? '........................................' }}
            </div>
          </td>
        </tr>
      </table>
    </div>

    <div class="footer-section">
      <div class="divider">
        <hr style="border-top: 3px solid #000;">
        <hr>
      </div>
      <table class="footer-table">
        <tr>
          <td style="width: 15%; text-align: right;">
            <img src="{{ public_path('assets/img/kan.png') }}" alt="Logo KAN" class="logo-footer">
          </td>
          <td style="width: 70%; text-align: center;">
            <div class="footer-title">KONSENTRASI KEAHLIAN</div>
            <div class="footer-content">
              Pengembangan Perangkat Lunak dan Gim, Teknik Jaringan Komputer dan Telekomunikasi, Bisnis Digital, Manajemen Perkantoran, Akuntansi Keuangan Lembaga, Desain Komunikasi Visual
            </div>
          </td>
          <td style="width: 15%; text-align: left;">
            <img src="{{ public_path('assets/img/iaf.jpg') }}" alt="Logo IAF" class="logo-footer">
          </td>
        </tr>
      </table>
    </div>
  </div>

</body>
</html>
