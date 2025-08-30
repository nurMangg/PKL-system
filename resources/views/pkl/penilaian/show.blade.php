@extends('layouts.main')
@section('title')
    Detail Penilaian PKL
@endsection
@section('pagetitle')
<div class="pagetitle">
    <h1>Detail Penilaian PKL</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">PKL</li>
            <li class="breadcrumb-item"><a href="">Penilaian</a></li>
            <li class="breadcrumb-item active">Detail Penilaian</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Data Siswa</h5>
            <div>
                <a href="{{ route('penilaian.edit', $siswa->nis) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Penilaian
                </a>
                <a href="{{ url('penilaian/' . $siswa->nis . '/' . ($penempatan->id_ta ?? '1') . '/' . ($penempatan->kelompok ?? 'all') . '/print') }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Penilaian
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">NIS</th>
                        <td>{{ $siswa->nis }}</td>
                    </tr>
                    <tr>
                        <th>Nama Siswa</th>
                        <td>{{ $siswa->nama }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas ?? 'N/A' }}</td>
                    </tr> --}}
                    {{-- <tr>
                        <th>Judul Project PKL</th>
                        <td>{{ $projectpkl->projectpkl ?? 'N/A' }}</td>
                    </tr> --}}
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Jurusan</th>
                        <td>{{ $siswa->jurusan->jurusan ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Guru Pembimbing</th>
                        <td>{{ $penempatan->guru->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Akhir</th>
                        <td>
                            <span class="badge bg-primary fs-6">{{ number_format($nilaiAkhir, 2) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <h5 class="card-title">Hasil Penilaian</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="60%">Indikator Penilaian</th>
                        <th width="20%">Nilai/Status</th>
                        <th width="15%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($mainIndicators as $mainIndicator)
                        {{-- Level 1 - Main Indicator --}}
                        <tr class="">
                            <td><strong>{{ $no++ }}</strong></td>
                            <td>
                                <strong>{{ $mainIndicator->indikator }}</strong>
                            </td>
                            <td class="text-center">

                            </td>
                            <td>

                            </td>
                        </tr>

                        {{-- Level 2 - Sub Indicators --}}
                        @if($mainIndicator->children && $mainIndicator->children->count() > 0)
                            @foreach($mainIndicator->children as $subIndicator)
                                <tr class="">
                                    <td></td>
                                    <td class="ps-3">
                                        <span class="badge bg-info me-2">Level 2</span>
                                        {{ $subIndicator->indikator }}
                                    </td>
                                    <td class="text-center">
                                        @if($subIndicator->level3Children && $subIndicator->level3Children->count() > 0)
                                            {{-- Jika ada level 3, tidak tampilkan nilai di level 2 --}}
                                            <span class="text-muted">-</span>
                                        @else
                                            {{-- Jika tidak ada level 3, tampilkan nilai di level 2 --}}
                                            @if($subIndicator->is_nilai !== null)
                                                <span class="">{{ $subIndicator->is_nilai }}</span>
                                            @else
                                                <span class="">-</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($subIndicator->level3Children && $subIndicator->level3Children->count() > 0)
                                            {{-- Jika ada level 3, tidak tampilkan keterangan di level 2 --}}
                                            <span class="text-muted">-</span>
                                        @else
                                            {{-- Jika tidak ada level 3, tampilkan keterangan di level 2 --}}
                                            @if($subIndicator->is_nilai !== null)
                                                @if($subIndicator->is_nilai >= 90)
                                                    <span class="">Sangat Baik</span>
                                                @elseif($subIndicator->is_nilai >= 80)
                                                    <span class="">Baik</span>
                                                @elseif($subIndicator->is_nilai >= 70)
                                                    <span class="">Cukup</span>
                                                @elseif($subIndicator->is_nilai >= 60)
                                                    <span class="">Kurang</span>
                                                @else
                                                    <span class="">Sangat Kurang</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Belum Dinilai</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>

                                {{-- Level 3 - Sub-Sub Indicators --}}
                                @if($subIndicator->level3Children && $subIndicator->level3Children->count() > 0)
                                    @foreach($subIndicator->level3Children as $subSubIndicator)
                                        <tr class="">
                                            <td></td>
                                            <td class="ps-5">
                                                <span class="badge bg-warning me-2">Level 3</span>
                                                {{ $subSubIndicator->indikator }}
                                            </td>
                                            <td class="text-center">
                                                @if($subSubIndicator->is_nilai !== null)
                                                    <span class="">{{ $subSubIndicator->is_nilai }}</span>
                                                @else
                                                    <span class="">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subSubIndicator->is_nilai !== null)
                                                    @if($subSubIndicator->is_nilai >= 90)
                                                        <span class="">Sangat Baik</span>
                                                    @elseif($subSubIndicator->is_nilai >= 80)
                                                        <span class="">Baik</span>
                                                    @elseif($subSubIndicator->is_nilai >= 70)
                                                        <span class="">Cukup</span>
                                                    @elseif($subSubIndicator->is_nilai >= 60)
                                                        <span class="">Kurang</span>
                                                    @else
                                                        <span class="">Sangat Kurang</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Belum Dinilai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Catatan Penilaian</h5>
                        <p>{{ $catatanText ?: 'Tidak ada catatan' }}</p>

                        <h6 class="mt-3">Informasi Penilaian</h6>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Dinilai Oleh</th>
                                <td>{{ $penempatan->instruktur->nama ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Penilaian</th>
                                <td>{{ $mainIndicatorPenilaian->first() ? date('d-m-Y H:i', strtotime($mainIndicatorPenilaian->first()->created_at)) : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getNilaiKeterangan($nilai) {
    if ($nilai >= 86) {
        return 'Sangat Baik (A)';
    } elseif ($nilai >= 71) {
        return 'Baik (B)';
    } elseif ($nilai >= 56) {
        return 'Cukup Baik (C)';
    } else {
        return 'Perlu Perbaikan (D)';
    }
}
@endphp
