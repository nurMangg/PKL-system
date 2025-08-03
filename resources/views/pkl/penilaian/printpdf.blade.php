<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lembar Nilai Capaian Pembelajaran PKL</title>
  <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      vertical-align: top;
    }
    th, td {
      padding: 4px;
    }
    body {
      font-family: "Times New Roman", Times, serif;
    }
    .tanda-tangan {
      clear: both;
      overflow: hidden;
    }
    .container {
      clear: both;
      margin-top: 30px;
    }
    @media print {
      .page-break {
        page-break-before: always;
        break-before: page;
      }
      .kop-surat-second {
        page-break-before: always;
        break-before: page;
      }
    }
  </style>
</head>
<body>

    <div class="kop-surat" style="margin-bottom: 30px; display: flex; align-items: center; justify-content: center;">
        <div style="flex: 0 0 100px; text-align: center;">
            <img src="{{ url('/assets/img/SMK_KARBAK.png') }}" alt="Logo SMK Karya Bhakti Brebes" class="logo-small">

        </div>
        <div style="flex: 1; text-align: center;">
            <h3 style="margin: 0 0 2px 0;">YAYASAN PENDIDIKAN EKONOMI</h3>
            <h2 style="margin: 0;">SMK KARYA BHAKTI BREBES</h2>
            <p style="margin: 0; font-size: 12px;">Jl. Taman Siswa No. 1 Telp. (0283) 671248, 673241 Fax. (0283) 67184 Brebes 52212<br>
            Website: www.smkkaryabhaktibbs.tk Email: karyabhakti.brebes@gmail.com</p>
        </div>
        <div style="flex: 0 0 100px; text-align: center;">
            <img src="{{asset('assets/img/logodeltapas.jpg') }}" alt="Logo Kanan" style="height: 50px;">
        </div>
    </div>
    <div style="width: 100%; margin-bottom: 30px;">
        <hr style="border: 0; border-top: 1px solid #000; margin-bottom: 3px;">
        <hr style="border: 0; border-top: 3px solid #000; margin: 0;">
    </div>



  <h3 style="text-align: center; ">LEMBAR NILAI CAPAIAN PEMBELAJARAN PKL<br>TAHUN PELAJARAN {{ $tahunAkademik->tahun_akademik ?? '2025/2026' }}</h3>

  <div class="container" style="padding-left: 30px; padding-right: 30px; margin-bottom: 30px;">
  <table style="width: 100%; border: 0; border-collapse: collapse;">
    <tr>
      <td style="width: 40%; border: 0;">Bidang Keahlian</td>
      <td style="border: 0;">: {{ $siswa->jurusan->jurusan ?? 'TEKNOLOGI INFORMASI' }}</td>
    </tr>
    <tr>
      <td style="border: 0;">Program Keahlian</td>
      <td style="border: 0;">: PENGEMBANGAN PERANGKAT LUNAK DAN GIM</td>
    </tr>
    <tr>
      <td style="border: 0;">Konsentrasi Keahlian</td>
      <td style="border: 0;">: REKAYASA PERANGKAT LUNAK</td>
    </tr>
    <tr>
      <td style="border: 0;">Nama Institusi</td>
      <td style="border: 0;">: {{ $penempatan->dudi->nama ?? '............................................................' }}</td>
    </tr>
    <tr>
      <td style="border: 0;">Alamat Lengkap Institusi</td>
      <td style="border: 0;">: {{ $penempatan->dudi->alamat ?? '............................................................' }}</td>
    </tr>
  </table>
  </div>

  <table style="width: 100%;">
    <thead>
      <tr>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Elemen</th>
        <th colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">Tujuan Pembelajaran</th>
        <th colspan="{{ $allStudents->count() }}" style="text-align: center; vertical-align: middle;">Nama Siswa dan Nilai</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Keterangan</th>
      </tr>
      <tr>
        @foreach($allStudents as $index => $student)
        <th style="text-align: center; vertical-align: middle;">{{ $student->nama }}</th>
        @endforeach
      </tr>
      <tr>
        <th style="text-align: center; vertical-align: middle;">1</th>
        <th style="text-align: center; vertical-align: middle;">2</th>
        <th colspan="2" style="text-align: center; vertical-align: middle;">3</th>
        @for($i = 4; $i <= 3 + $allStudents->count(); $i++)
        <th style="text-align: center; vertical-align: middle;">{{ $i }}</th>
        @endfor
        <th style="text-align: center; vertical-align: middle;">{{ 4 + $allStudents->count() }}</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @if($mainIndicators->count() > 0)
        @foreach($mainIndicators as $mainIndex => $mainIndicator)
          @php $subCount = $mainIndicator->children->count(); @endphp
          @foreach($mainIndicator->children as $subIndex => $subIndicator)
            <tr>
              @if($subIndex === 0)
                <td rowspan="{{ $subCount }}">{{ $no++ }}</td>
                <td rowspan="{{ $subCount }}">{{ $mainIndicator->indikator }}</td>
              @endif
              <td>{{ $subIndicator->kode ?? ($mainIndex + 1) . '.' . ($subIndex + 1) }}</td>
              <td>{{ $subIndicator->indikator }}</td>
              @foreach($allStudents as $student)
              @php
                $nilai = '-';

                // Get PrgObsvr data for this student from assessmentData
                if (isset($assessmentData[$student->nis]) && $assessmentData[$student->nis]->count() > 0) {
                    // Find the PrgObsvr record for this sub indicator by matching indikator
                    $studentPrgObsvr = $assessmentData[$student->nis];

                    // Look for PrgObsvr record that matches this sub indicator
                    foreach ($studentPrgObsvr as $prgObsvrRecord) {
                        if ($prgObsvrRecord->indikator === $subIndicator->indikator) {
                            $nilai = $prgObsvrRecord->is_nilai;
                            break;
                        }
                    }
                }

                // If no assessment data found, show '-'
                if (empty($nilai) && $nilai !== 0) {
                    $nilai = '-';
                }

                // Debug: Show matching info for first few rows
                if ($no <= 3 && $subIndex === 0) {
                    echo "<!-- Debug: Looking for '{$subIndicator->indikator}' in " . count($studentPrgObsvr ?? []) . " records for {$student->nama} -->";
                }
              @endphp
              <td style="text-align: center;">{{ $nilai }}</td>
            @endforeach

              <td></td>
            </tr>
          @endforeach
        @endforeach
      @else
        <tr>
          <td colspan="{{ 4 + $allStudents->count() }}" style="text-align: center; color: red;">
            <strong>Tidak ada data indikator penilaian yang ditemukan.</strong><br>
            Silakan buat template penilaian terlebih dahulu.
          </td>
        </tr>
      @endif
    </tbody>
  </table>

  <div class="skala-penilaian">
  <h4 style="margin-bottom: 0px; ">SKALA PENILAIAN:</h4>
  <table style="width: 350px; border-collapse: collapse; margin-top: 10px;">
    <tr>
      <th style="border: 1px solid #333; padding: 4px;">Rentang Nilai</th>
      <th style="border: 1px solid #333; padding: 4px;">Predikat</th>
      <th style="border: 1px solid #333; padding: 4px;">Keterangan</th>
    </tr>
    <tr>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">90 - 100</td>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">A</td>
      <td style="border: 1px solid #333; padding: 4px;">Sangat Baik</td>
    </tr>
    <tr>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">80 - 89</td>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">B</td>
      <td style="border: 1px solid #333; padding: 4px;">Baik</td>
    </tr>
    <tr>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">70 - 79</td>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">C</td>
      <td style="border: 1px solid #333; padding: 4px;">Cukup</td>
    </tr>
    <tr>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">60 - 69</td>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">D</td>
      <td style="border: 1px solid #333; padding: 4px;">Kurang</td>
    </tr>
    <tr>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">0 - 59</td>
      <td style="border: 1px solid #333; padding: 4px; text-align:center;">E</td>
      <td style="border: 1px solid #333; padding: 4px;">Sangat Kurang</td>
    </tr>
  </table>
  </div>

  <div class="tanda-tangan" style="width: 100%; margin-top: 40px;">
    <div style="float: right; text-align: center;">
      @php
        // Tanggal hari ini dalam format Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');
        $hari = \Carbon\Carbon::now()->isoFormat('dddd');
        $tanggal = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
      @endphp
      <div style="">
        {{ $hari }}, {{ $tanggal }}
      </div>
      <div >
        Pembimbing Institusi
      </div>
      <br><br>
      <div style="margin-top: 60px;"></div>
      <div style="text-decoration: underline; font-weight: bold; margin-bottom: 60px;">
        {{ $penempatan->instruktur->nama ?? '........................................' }}
      </div>
    </div>
  </div>

  <div class="container" style="clear: both;">

    <div class="kop-surat kop-surat-second" style="margin-bottom: 30px; display: flex; align-items: center; justify-content: center; page-break-before: always; break-before: page;">
        <div style="flex: 0 0 100px; text-align: center;">
            <img src="{{ asset('assets/img/SMK_KARBAK.png') }}" alt="Logo Kiri" style="height: 80px;">
        </div>
        <div style="flex: 1; text-align: center;">
            <h3 style="margin: 0 0 2px 0;">YAYASAN PENDIDIKAN EKONOMI</h3>
            <h2 style="margin: 0;">SMK KARYA BHAKTI BREBES</h2>
            <p style="margin: 0; font-size: 12px;">Jl. Taman Siswa No. 1 Telp. (0283) 671248, 673241 Fax. (0283) 67184 Brebes 52212<br>
            Website: www.smkkaryabhaktibbs.tk Email: karyabhakti.brebes@gmail.com</p>
        </div>
        <div style="flex: 0 0 100px; text-align: center;">
            <img src="{{asset('assets/img/logodeltapas.jpg') }}" alt="Logo Kanan" style="height: 50px;">
        </div>
      </div>
      <div style="width: 100%; margin-bottom: 5px;">
          <hr style="border: 0; border-top: 1px solid #000; margin-bottom: 3px;">
          <hr style="border: 0; border-top: 3px solid #000; margin: 0;">
      </div>

      <!-- Konten di bawah kop surat 2 -->
      <div style="padding: 12px;">
        <h3 style="text-align: center; margin-bottom: 12px;">ANGKET PKL<br>TAHUN PELAJARAN {{ $tahunAkademik->tahun_akademik ?? '2025/2026' }}</h3>

        <div style="margin-top: 16px;">
          <h4 style="margin-bottom: 8px;"><strong>I. IDENTITAS</strong></h4>
          <table style="width: 100%; border: none;">
            <tr style="border: none;">
              <td style="width: 40%; padding: 4px; border: none;">1. Nama Institusi</td>
              <td style="width: 60%; padding: 4px; border: none;">: {{ $penempatan->dudi->nama ?? '............................................................' }}</td>
            </tr>
            <tr style="border: none;">
              <td style="padding: 4px; border: none;">2. Alamat</td>
              <td style="padding: 4px; border: none;">: {{ $penempatan->dudi->alamat ?? '............................................................' }}</td>
            </tr>
            <tr style="border: none;">
              <td style="padding: 4px; border: none;">3. Nama Pembimbing Institusi</td>
              <td style="padding: 4px; border: none;">: {{ $penempatan->instruktur->nama ?? '............................................................' }}</td>
            </tr>
          </table>
        </div>

        <div style="margin-top: 16px;">
          <h4 style="margin-bottom: 8px;"><strong>II. KEBIJAKSANAAN SEKOLAH</strong></h4>
          <table style="width: 100%; border-collapse: collapse; border: none;">
            @php
              $kebijaksanaanSekolah = $dataAngket->filter(function($item) {
                  return str_contains(strtolower($item['soal']), 'waktu prakerin') ||
                         str_contains(strtolower($item['soal']), 'tata administrasi') ||
                         str_contains(strtolower($item['soal']), 'pembinaan');
              });
            @endphp
            @foreach($kebijaksanaanSekolah as $index => $item)
            <tr>
              <td style="width: 40%; padding: 4px; border: none;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
              <td style="width: 60%; padding: 4px; border: none;">: {{ $item['nilai'] }}</td>
            </tr>
            @endforeach
          </table>
        </div>

        <div style="margin-top: 16px;">
          <h4 style="margin-bottom: 8px;"><strong>III. KESIMPULAN</strong></h4>
          <table style="width: 100%; border-collapse: collapse; border: none;">
            @php
              $kesimpulan = $dataAngket->filter(function($item) {
                  return str_contains(strtolower($item['soal']), 'hal') &&
                         (str_contains(strtolower($item['soal']), 'positif') ||
                          str_contains(strtolower($item['soal']), 'negatif'));
              });
            @endphp
            @foreach($kesimpulan as $index => $item)
            <tr>
              <td style="width: 40%; padding: 4px; border: none;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
              <td style="width: 60%; padding: 4px; border: none;">: {{ $item['nilai'] }}</td>
            </tr>
            @endforeach
          </table>
        </div>

        <div style="margin-top: 16px;">
          <h4 style="margin-bottom: 8px;"><strong>IV. SARAN-SARAN</strong></h4>
          <table style="width: 100%; border-collapse: collapse; border: none;">
            @php
              $saranSaran = $dataAngket->filter(function($item) {
                  return str_contains(strtolower($item['soal']), 'perbaikan') ||
                         str_contains(strtolower($item['soal']), 'penyempurnaan');
              });
            @endphp
            @foreach($saranSaran as $index => $item)
            <tr>
              <td style="width: 40%; padding: 4px; border: none;">{{ $index + 1 }}. {{ $item['soal'] }}</td>
              <td style="width: 60%; padding: 4px; border: none;">: {{ $item['nilai'] }}</td>
            </tr>
            @endforeach
          </table>
        </div>

        <div style="margin-top: 50px; margin-bottom: 100px;">
          <div style="display: flex; justify-content: space-between;">
            <div style="width: 45%; text-align: left; padding: 4px;">
              <strong>Mengetahui:</strong><br>
              Kepala Institusi/Pimpinan,<br><br><br><br><br>
              <div style="text-decoration: underline; font-weight: bold; margin-top: 40px;">
                {{ $penempatan->instruktur->dudi->nama_pimpinan ?? '........................................' }}
              </div>
            </div>
            <div style="width: 45%; text-align: right; padding: 4px;">
              {{ $hari }}, {{ $tanggal }}
              <br>
              Pembimbing Institusi,<br><br><br><br><br>
              <div style="text-decoration: underline; font-weight: bold; margin-top: 40px;">
                {{ $penempatan->instruktur->nama ?? '........................................' }}
              </div>
            </div>
          </div>
        </div>

        <div style="margin-top: 40px; width: 100%; text-align: center; font-size: 13px; padding-bottom: 10px;">
            <div style="width: 100%; margin-bottom: 5px;">
                <hr style="border: 0; border-top: 3px solid #000;  margin-bottom: 3px;">
                <hr style="border: 0; border-top: 1px solid #000;">

            </div>
          <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
              <td style="width: 15%; border: none; text-align: right; vertical-align: middle;">
                <img src="{{ asset('assets/img/kan.png') }}" alt="Logo KAN" style="height: 48px;">
              </td>
              <td style="width: 70%; border: none; text-align: center; vertical-align: middle;">
                <div style="font-weight: bold; font-size: 15px; margin-bottom: 2px; letter-spacing: 1px;">
                  KONSENTRASI KEAHLIAN
                </div>
                <div style="font-size: 13px;">
                  Pengembangan Perangkat Lunak dan Gim, Teknik Jaringan Komputer dan Telekomunikasi, Bisnis Digital, Manajemen Perkantoran, Akuntansi Keuangan Lembaga, Desain Komunikasi Visual
                </div>
              </td>
              <td style="width: 15%; border: none; text-align: left; vertical-align: middle;">
                <img src="{{ asset('assets/img/iaf.jpg') }}" alt="Logo IAF" style="height: 48px;">
              </td>
            </tr>
          </table>
        </div>
      </div>
  </div>



</body>
</html>
