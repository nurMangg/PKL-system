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
  </style>
</head>
<body>

    <div class="kop-surat" style="margin-bottom: 30px; display: flex; align-items: center; justify-content: center;">
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
      <td style="border: 0;">: {{ $penempatan->instansi->nama_instansi ?? '............................................................' }}</td>
    </tr>
    <tr>
      <td style="border: 0;">Alamat Lengkap Institusi</td>
      <td style="border: 0;">: {{ $penempatan->instansi->alamat ?? '............................................................' }}</td>
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
      @foreach($mainIndicators as $mainIndicator)
        @php $subCount = $mainIndicator->children->count(); @endphp
        @foreach($mainIndicator->children as $subIndex => $subIndicator)
          <tr>
            @if($subIndex === 0)
              <td rowspan="{{ $subCount }}">{{ $no++ }}</td>
              <td rowspan="{{ $subCount }}">{{ $mainIndicator->indikator }}</td>
            @endif
            <td>{{ $subIndicator->kode ?? ($subIndex + 1) . '.' . ($subIndex + 1) }}</td>
            <td>{{ $subIndicator->indikator }}</td>
            @foreach($allStudents as $student)
              @php
                $studentAssessment = $assessmentData[$student->nis] ?? collect();
                $assessment = $studentAssessment->where('id_prg_obsvr', $subIndicator->id)->first();
                $nilai = $assessment ? $assessment->nilai : '';
              @endphp
              <td style="text-align: center;">{{ $nilai }}</td>
            @endforeach
            <td></td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>

</body>
</html>
