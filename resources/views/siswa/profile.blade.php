@extends('layouts.main')
@section('title')
    Siswa
@endsection

@section('css')
    <link href="{{ asset('assets') }}/vendor/leaflet/leaflet.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection
@section('pagetitle')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Siswa</li>
                    <li class="breadcrumb-item">{{ $siswa->nama }}</li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </nav>
        </div>
        <div>
            <!-- Button Kembali di atas kanan -->
            <a href="{{ url()->previous() }}" class="btn btn-warning"> <i class="bi bi-chevron-left"></i> Kembali</a>
        </div>
    </div><!-- End Page Title -->
@endsection

@section('content')
    <div class="row">
        <!-- Profile Section -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Siswa</h5>

                    <div class="profile-card d-flex flex-column align-items-center">
                        <!-- Menampilkan foto profil -->
                        <img src="{{ $siswa->foto ? asset('storage/' . $siswa->foto) : asset('assets/img/profile.gif') }}"
                            alt="Profile" class="rounded-circle" width="120" id="profileImage">
                        @if (session('nis') == $siswa->nis || in_array(auth()->user()->role, [1, 2]))
                            <!-- Form untuk mengganti foto -->
                            <form id="changePhotoForm" enctype="multipart/form-data" class="mb-1">
                                @csrf
                                <!-- Input file foto (hidden) -->
                                <input type="file" name="foto_profile" id="foto_profile" class="form-control-file"
                                    accept="image/*" style="display:none">

                                <!-- Tombol untuk mengganti foto -->
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill"
                                        onclick="document.getElementById('foto_profile').click()">
                                        <i class="fas fa-camera-retro"></i> Ganti Foto
                                    </button>
                                </div>
                            </form>
                        @endif
                        <button class="btn btn-sm btn-outline-danger rounded-pill mb-3" onclick="getResume()">
                            <i class="bi bi-file-earmark-pdf"></i>Resume
                        </button>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            @if (in_array(auth()->user()->role, [1, 2, 3]) ||
                                    in_array(session('id_instruktur'), $siswa->penempatan->pluck('instruktur.id_instruktur')->toArray()))
                                <div class="form-group">
                                    <select class="form-select" required name="status_bekerja" id="status_bekerja">
                                        <option value="WFO" @if ($siswa->status_bekerja == 'WFO') selected @endif>Work From
                                            Office (WFO)</option>
                                        <option value="WFA" @if ($siswa->status_bekerja == 'WFA') selected @endif>Work From
                                            Any Where (WFA)</option>
                                    </select>
                                    <div class="invalid-feedback">Isian tidak boleh kosong.</div>
                                </div>
                            @else
                                @if ($siswa->status_bekerja == 'WFA')
                                    <span class="badge rounded-pill bg-success">Work From Any Where (WFA)</span>
                                @else
                                    <span class="badge rounded-pill bg-primary ">Work From Office (WFO)</span>
                                @endif
                            @endif

                        </li>
                        <li class="list-group-item"><strong>NIS: </strong>{{ $siswa->nis }}</li>
                        <li class="list-group-item"><strong>Nama: </strong>{{ $siswa->nama }}</li>
                        <li class="list-group-item"><strong>Jurusan: </strong>{{ $siswa->jurusan->jurusan }}</li>
                        <li class="list-group-item"><strong>Email: </strong>{{ $siswa->email }}</li>
                        <li class="list-group-item"><strong>No. Kontak: </strong>{{ $siswa->no_kontak }}</li>


                        <!-- Info tambahan yang bisa dibuka/tutup -->
                        <div class="collapse" id="info-collapse">
                            <li class="list-group-item"><strong>Tempat Lahir: </strong>{{ $siswa->tempat_lahir }}</li>
                            <li class="list-group-item"><strong>Tanggal Lahir:
                                </strong>{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') }}</li>
                            <li class="list-group-item"><strong>Golongan Darah: </strong>{{ $siswa->golongan_darah }}</li>
                            <li class="list-group-item"><strong>Gender:
                                </strong>{{ $siswa->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</li>
                            <li class="list-group-item"><strong>Alamat: </strong>{{ $siswa->alamat }}</li>
                            <li class="list-group-item"><strong>Nama Wali: </strong>{{ $siswa->nama_wali }}</li>
                            <li class="list-group-item"><strong>Alamat Wali: </strong>{{ $siswa->alamat_wali }}</li>
                            <li class="list-group-item"><strong>No. Kontak Wali: </strong>{{ $siswa->no_kontak_wali }}</li>
                        </div>
                    </ul>
                    <!-- Tombol untuk buka/tutup info tambahan -->
                    <button class="btn btn-sm btn-link float-end" type="button" data-bs-toggle="collapse"
                        data-bs-target="#info-collapse" aria-expanded="false" aria-controls="info-collapse"> Tampilkan
                    </button>


                    <h5 class="card-title mt-4">Data Penempatan</h5>

                    @if ($siswa->penempatan ?? false)
                        @foreach ($siswa->penempatan as $penempatan)
                            <ul class="list-group">
                                <!-- Menampilkan data penempatan pada bagian siswa -->
                                <li class="list-group-item"><strong>Instruktur:
                                    </strong>
                                    <a href="{{ url('/d/instruktur?id=' . $penempatan->instruktur->id_instruktur) }}">
                                        {{ $penempatan->instruktur->nama }}</a>
                                </li>
                                <li class="list-group-item"><strong>DUDI:
                                    </strong> <a href="{{ url('/d/dudi?id=' . $penempatan->instruktur->dudi->id_dudi) }}">
                                        {{ $penempatan->instruktur->dudi->nama ?? '-' }}</a> </li>
                                <li class="list-group-item"><strong>Guru:
                                    </strong>
                                    <a href="{{ url('/d/guru?id=' . $penempatan->guru->id_guru) }}">
                                        {{ $penempatan->guru->nama }}</a>
                                </li>
                                <li class="list-group-item"><strong>Periode:
                                    </strong>{{ $penempatan->tahunAkademik->tahun_akademik ?? '-' }}</li>
                            </ul>
                            <br>
                        @endforeach
                    @else
                        <ul class="list-group">
                            <li class="list-group-item">Belum ada penempatan</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="col-xl-8">
            <div class="card pt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="id_ta" class="form-label">Tahun Akademik</label>
                            <select class="form-select" id="id_ta" name="id_ta">
                                <option value="">Pilih</option>
                                @foreach ($ta as $item)
                                    @if ($item->id_ta == $activeAcademicYear->id_ta)
                                        <option value="{{ $item->id_ta }}" selected>{{ $item->tahun_akademik }}
                                            (aktif)
                                        </option>
                                    @else
                                        <option value="{{ $item->id_ta }}">{{ $item->tahun_akademik }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="id_penempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="id_penempatan" name="id_penempatan">
                                <option value="">Pilih</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#profile-presensi">Presensi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-kegiatan">Log
                                Kegiatan</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-catatan">Catatan
                                Instruktur</button>
                        </li>

                        <!-- pembatasan menu tiap role -->
                        @if (session('nis') == $siswa->nis || in_array(auth()->user()->role, [1, 2]))
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nilai-pkl">Nilai
                                    PKL</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pengajuan-surat">Pengajuan
                                    Surat</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                    Akun </button>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content pt-2">
                        <!-- Presensi Tab -->
                        <div class="tab-pane fade show active profile-presensi" id="profile-presensi">
                            @if (session('nis') == $siswa->nis || in_array(auth()->user()->role, [1, 2]))
                                @if ($siswa->penempatan ?? false)
                                    <h5 class="mt-3">Presensi</h5>
                                    <p>Presensi menggunakan validasi lokasi (lat-long). Jika di luar radius
                                        (<span id="radius_text">0</span> meter), presensi tidak
                                        diperbolehkan. Kecuali status anda WFA (Work From Any Where)</p>

                                    <input type="hidden" name="lt" id="lt" value="0">
                                    <input type="hidden" name="lg" id="lg" value="0">
                                    <input type="hidden" name="radius" id="radius" value="0">

                                    <button style="display: none;" id="btnAbsen" class="btn btn-success"
                                        onclick="getLocation()"><i id="icon-absen" class="bi bi-fingerprint"
                                            style="font-size: 2rem;"></i> <br> Absen</button>

                                    <!-- Map Container -->
                                    <div id="map" style="height: 300px; margin-top: 20px;"></div>
                                @endif
                            @endif

                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-success btn-sm me-2"
                                    onclick="getLaporan('/d/siswa/presensiExcel/')"><i
                                        class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger btn-sm" onclick="getLaporan('/d/siswa/presensiPdf/')"><i
                                        class="bi bi-file-earmark-pdf"></i> Pdf</button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="table-presensi" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <!-- Kegiatan Tab -->
                        <div class="tab-pane fade pt-3" id="profile-kegiatan">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success btn-sm me-2"
                                    onclick="getLaporan('/d/siswa/kegiatanExcel/')"><i
                                        class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger btn-sm" onclick="getLaporan('/d/siswa/kegiatanPdf/')"><i
                                        class="bi bi-file-earmark-pdf"></i> Pdf</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="table-kegiatan" width="100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Tanggal</th>
                                            <th rowspan="2">Kegiatan</th>
                                            <th colspan="2">Disetujui</th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th>Instruktur</th>
                                            <th>Guru</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <!-- pengajuan surat Tab-->
                        <div class="tab-pane fade pt-3" id="pengajuan-surat">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Daftar Kegiatan</h5>
                                <div>
                                    <button class="btn btn-primary btn-sm me-2 pengajuanSuratModal"
                                        id="pengajuanSuratModal" data-id="{{ $siswa->nis }}" <i
                                        class="bi bi-plus-circle"></i> Pengajuan Surat
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel data -->
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="table-pengajuan-surat" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>jurusan</th>
                                            <th>Perusahaan Tujuan</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Status</th>
                                            <th>Detail</th>
                                            <th>File Surat</th>
                                            <th>Balasan DuDi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>


                        <!-- Modal Form Pengajuan Surat -->
                        <div class="modal fade" id="pengajuanModal" tabindex="-1" aria-labelledby="pengajuanModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pengajuanModalLabel">Pengajuan Surat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formPengajuan">
                                            <input type="hidden" id="pengajuanId" name="id">
                                            <div id="siswa-input-group">
                                                <div class="row mb-3 siswa-input-row align-items-center flex-nowrap">
                                                    <div class="col-3 text-start">
                                                        <label for="nis1" class="form-label mb-0">Siswa 1</label>
                                                    </div>
                                                    <div class="col position-relative" style="min-width: 0">
                                                        <select id="nis1" class="form-select w-100 siswa-select"
                                                            style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;"
                                                            name="namaSiswa" required readonly disabled>
                                                            <option value="{{ $siswa->nis }}" selected>
                                                                {{ $siswa->nis }} - {{ $siswa->nama }}
                                                            </option>
                                                        </select>
                                                        <select id="nis1" class="form-select w-100 siswa-select"
                                                            style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;"
                                                            name="namaSiswa[]" required readonly hidden>
                                                            <option value="{{ $siswa->nis }}" selected>
                                                                {{ $siswa->nis }} - {{ $siswa->nama }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-auto ps-0">
                                                        <button type="button"
                                                            class="btn btn-success btn-sm add-siswa-btn d-flex align-items-center justify-content-center"
                                                            title="Tambah Siswa" style="width:32px; height:38px;">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="perusahaan" class="form-label">Perusahaan Tujuan</label>
                                                <div class="row g-2 align-items-center">
                                                    <div class="col">
                                                        <select class="form-select w-100" id="perusahaan"
                                                            name="perusahaan_tujuan" required>
                                                            <option value="">Pilih atau cari perusahaan...</option>
                                                            <!-- Opsi akan di-load via AJAX select2 -->
                                                        </select>
                                                    </div>
                                                    <div class="col-auto ps-0">
                                                        <button type="button"
                                                            class="btn btn-success d-flex align-items-center justify-content-center"
                                                            id="addPerusahaanBtn" title="Tambah Perusahaan"
                                                            style="width:38px; height:38px;">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggalMulai" class="form-label">Tahun Akademik</label>
                                                <select class="form-select" id="id_ta" name="id_ta">
                                                    <option value="">Pilih</option>
                                                    @foreach ($ta as $item)
                                                        @if ($item->id_ta == $activeAcademicYear->id_ta)
                                                            <option value="{{ $item->id_ta }}" selected>{{ $item->tahun_akademik }}
                                                                (aktif)
                                                            </option>
                                                        @else
                                                            <option value="{{ $item->id_ta }}">{{ $item->tahun_akademik }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                                                <input type="date" class="form-control" id="tanggalMulai"
                                                    name="tanggal_mulai" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                                                <input type="date" class="form-control" id="tanggalSelesai"
                                                    name="tanggal_selesai" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (session('nis') == $siswa->nis || in_array(auth()->user()->role, [1, 2]))
                        {{-- Data Nilai PKL --}}
                        <div class="tab-pane fade pt-3 nilai-pkl" id="nilai-pkl">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="table-nilai" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Laporan</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>



                        <!-- Change Password Tab -->
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <form id="changePasswordForm">
                                @csrf
                                <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                        Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="currentPassword"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                        Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="newpassword" type="password" class="form-control" id="newPassword"
                                            minlength="8" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                                        Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="renewpassword" type="password" class="form-control"
                                            id="renewPassword" minlength="8" required>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                                </div>
                            </form><!-- End Change Password Form -->
                        </div>
                    @endif

                        <!-- Modal Tambah Perusahaan -->
                        <div class="modal fade" id="modalTambahPerusahaan" tabindex="-1"
                            aria-labelledby="modalTambahPerusahaanLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTambahPerusahaanLabel">Tambah Perusahaan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form id="formTambahPerusahaan">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="dudi_nama" class="form-label">Nama DUDI</label>
                                                <input type="text" class="form-control" id="dudi_nama" name="nama"
                                                    required maxlength="30">
                                                <div class="invalid-feedback">Nama wajib diisi dan maksimal 30 karakter.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dudi_nama_pimpinan" class="form-label">Nama Pimpinan</label>
                                                <input type="text" class="form-control" id="dudi_nama_pimpinan"
                                                    name="nama_pimpinan" required maxlength="50">
                                                <div class="invalid-feedback">Nama wajib diisi dan maksimal 50 karakter.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dudi_no_kontak" class="form-label">Nomor Kontak</label>
                                                <input type="number" class="form-control" id="dudi_no_kontak"
                                                    name="no_kontak" required maxlength="14">
                                                <div class="invalid-feedback">Nomor kontak wajib diisi dan maksimal 14
                                                    karakter.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dudi_alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="dudi_alamat" name="alamat" maxlength="100" required></textarea>
                                                <div class="invalid-feedback">Alamat maksimal 100 karakter.</div>
                                            </div>
                                            <div id="map-tambah-dudi" style="height: 300px;"></div>
                                            <div class="mb-3 mt-3">
                                                <label for="dudi_latitude" class="form-label">Latitude</label>
                                                <input type="text" class="form-control" id="dudi_latitude1" readonly
                                                    required>
                                                <input type="hidden" id="dudi_latitude" name="latitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="dudi_longitude" class="form-label">Longitude</label>
                                                <input type="text" class="form-control" id="dudi_longitude1" readonly
                                                    required>
                                                <input type="hidden" id="dudi_longitude" name="longitude">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kegiatan Tab -->
                    <div class="tab-pane fade pt-3" id="profile-catatan">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success btn-sm me-2" onclick="getLaporan('/d/siswa/catatanExcel/')"><i
                                    class="bi bi-file-earmark-excel"></i> Excel</button>
                            <button class="btn btn-danger btn-sm" onclick="getLaporan('/d/siswa/catatanPdf/')"><i
                                    class="bi bi-file-earmark-pdf"></i> Pdf</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="table-catatan" width="100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>


                </div><!-- End Bordered Tabs -->
            </div>
        </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="myForm" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="id_presensi" name="id_presensi">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" disabled>
                            <div class="invalid-feedback"> Tanggal wajib diisi. </div>
                        </div>

                        <div class="mb-3">
                            <label for="kegiatan" class="form-label">Kegiatan</label>
                            <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required></textarea>
                            <div class="invalid-feedback"> Kegiatan wajib diisi. </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModalAbsensi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="myFormAbsensi" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="nis" value="{{ $siswa->nis }}">
                        <input type="hidden" name="id_penempatan" id="id_penempatan1" value="">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto (Max:2 MB)</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                            <div class="invalid-feedback"> Foto wajib diunggah. </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Instruktur -->
    <div class="modal fade" id="modalTambahInstruktur" tabindex="-1" aria-labelledby="modalTambahInstrukturLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahInstrukturLabel">Tambah Instruktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahInstruktur" action="" method="POST">

                        <div class="mb-3">
                            <label for="id_instruktur" class="form-label">ID Instruktur</label>
                            <input type="text" class="form-control @error('id_instruktur') is-invalid @enderror" id="id_instruktur" name="id_instruktur" value="{{ old('id_instruktur') }}" required maxlength="15">
                            <div class="invalid-feedback">
                                ID Instruktur wajib diisi dan maksimal 15 karakter.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required maxlength="50">
                            <div class="invalid-feedback">
                                Nama wajib diisi dan maksimal 50 karakter.
                            </div>
                        </div>
                        <!-- Gender Radio Buttons -->
                        <div class="mb-3">
                            <label for="gender">Gender</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender"
                                        id="gender_L" value="L"
                                        {{ old('gender') == 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label"
                                        for="gender_L">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender"
                                        id="gender_P" value="P"
                                        {{ old('gender') == 'P' ? 'checked' : '' }} required>
                                    <label class="form-check-label"
                                        for="gender_P">Perempuan</label>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Gender wajib dipilih.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="no_kontak" class="form-label">Nomor Kontak</label>
                            <input type="number" class="form-control @error('no_kontak') is-invalid @enderror" id="no_kontak" name="no_kontak" value="{{ old('no_kontak') }}" required maxlength="14">
                            <div class="invalid-feedback">
                                Nomor kontak wajib diisi dan maksimal 14 karakter.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required maxlength="35">
                            <div class="invalid-feedback">
                                Email wajib diisi dan maksimal 35 karakter.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" maxlength="100">{{ old('alamat') }}</textarea>
                            <div class="invalid-feedback">
                                Alamat maksimal 100 karakter.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">DuDi</label>
                            <select class="form-select" id="idPerusahaan" name="idPerusahaan" readonly>
                                <option value="">{{ old('perusahaan_tujuan') }}</option>
                            </select>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Surat --}}
    <div class="modal fade" id="suratModal" tabindex="-1" aria-labelledby="suratModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suratModalLabel">Surat Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="suratModalBody">
                    <!-- Surat akan dimuat di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Balasan Dudi -->
    <div class="modal fade" id="modalBalasanDudi" tabindex="-1" aria-labelledby="modalBalasanDudiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBalasanDudiLabel">Balasan DUDI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('d.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File:</label>
                            <input type="hidden" name="id_pengajuan" id="id_pengajuan">
                            <input type="file" class="form-control" name="file" id="file"
                                accept=".pdf,.doc,.docx,.png,." required>
                        </div>
                        <div class="mb-3">
                            <label for="instruktur_isd" class="form-label">Pilih Instruktur</label>
                            <select class="form-select" id="instruktur_id" name="instruktur_id" style="width: 100%;">
                                <option value="">-- Pilih Instruktur --</option>
                            </select>
                            <div class="invalid-feedback"> Instruktur wajib dipilih. </div>
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <p class="me-3">Tidak ada instruktur, silahkan</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahInstruktur">
                                Tambah Instruktur
                            </button>
                        </div>


                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.js"></script>
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/leaflet/leaflet.js"></script>
    <script src="{{ asset('assets') }}/vendor/select2/js/select2.min.js"></script>
    {{-- jikatidak ada penempatan maka tidak tampil --}}

    <script>
        // Inisialisasi peta
        var map2 = L.map('map-tambah-dudi').setView([-6.982814303476982, 109.13654360065006],
            13); // Koordinat awal (Surabaya)

        // Tile Layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map2);

        var marker;
        var radiusCircle2;

        // Event klik di peta untuk mendapatkan latitude dan longitude
        map2.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Tampilkan marker di lokasi yang diklik
            if (marker) {
                map2.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map2);

            // Masukkan koordinat ke input field
            document.getElementById('dudi_latitude').value = lat;
            document.getElementById('dudi_longitude').value = lng;
            document.getElementById('dudi_latitude1').value = lat;
            document.getElementById('dudi_longitude1').value = lng;

            // Tambahkan lingkaran radius jika radius diinput
            var radius = document.getElementById('radius').value;
            if (radius) {
                // Hapus lingkaran radius sebelumnya jika ada
                if (radiusCircle2) {
                    map2.removeLayer(radiusCircle2);
                }
                radiusCircle2 = L.circle([lat, lng], {
                    color: 'blue',
                    fillColor: '#cce5ff',
                    fillOpacity: 0.4,
                    radius: parseFloat(radius) // Radius dalam meter
                }).addTo(map2);
            }
        });

        // Event perubahan pada input radius
        document.getElementById('radius').addEventListener('input', function() {
            var radius = this.value;
            if (marker) {
                // Hapus lingkaran radius sebelumnya jika ada
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                }
                radiusCircle = L.circle([marker.getLatLng().lat, marker.getLatLng().lng], {
                    color: 'blue',
                    fillColor: '#cce5ff',
                    fillOpacity: 0.4,
                    radius: parseFloat(radius) // Radius dalam meter
                }).addTo(map);
            }
        });
    </script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    $('#instruktur_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalBalasanDudi'),
        placeholder: 'Pilih Instruktur',
        ajax: {
            url: "{{ route('siswa.instruktur.search') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    pengajuan_id: $('#id_pengajuan').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.results, function (item) {
                        return {
                            id: item.id,
                            text: item.text
                        };
                    })
                };
            },
            cache: true
        }
    });
});



</script>

    <!-- validasi tanggal PKL -->
    <script>
        $(document).ready(function() {
            function cekJangkaPKL() {
                var tglMulai = $('#tanggalMulai').val();
                var tglSelesai = $('#tanggalSelesai').val();
                var errorMsg = '';
                var isValid = true;

                if (tglMulai && tglSelesai) {
                    var start = new Date(tglMulai);
                    var end = new Date(tglSelesai);

                    var months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start
                        .getMonth());
                    if (end < start) {
                        errorMsg = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
                        isValid = false;
                    } else if (months < 3 || months > 4 || (months === 4 && end.getDate() < start.getDate())) {
                        errorMsg = 'Jangka waktu PKL minimal 3 bulan dan maksimal 4 bulan.';
                        isValid = false;
                    }
                }

                // Tampilkan pesan error di bawah tanggalSelesai
                var $err = $('#tanggalSelesai').next('.invalid-feedback');
                if ($err.length === 0) {
                    $('#tanggalSelesai').after('<div class="invalid-feedback"></div>');
                    $err = $('#tanggalSelesai').next('.invalid-feedback');
                }
                if (!isValid) {
                    $('#tanggalSelesai').addClass('is-invalid');
                    $err.text(errorMsg).show();
                    $('#formPengajuan button[type=submit]').prop('disabled', true);
                } else {
                    $('#tanggalSelesai').removeClass('is-invalid');
                    $err.hide();
                    $('#formPengajuan button[type=submit]').prop('disabled', false);
                }
            }

            $('#tanggalMulai, #tanggalSelesai').on('change', cekJangkaPKL);
        })
    </script>


    <!-- pengajuan js -->
    <script>
        $(document).ready(function() {
            for (let i = 1; i <= 4; i++) {
                $(`#nis${i}`).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $("#pengajuanModal"),
                    placeholder: 'Cari Siswa NIS/Nama...',
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('siswa.searchh') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                k: 'penempatan',
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    var j = 0;
                                    item.penempatan.forEach(el => {
                                        if (el.id_ta == $('#id_ta').val()) {
                                            j++;
                                        }
                                    });
                                    var txt = j > 0 ? ` (Sudah di Tempatkan ${j}x)` : '';
                                    return {
                                        id: item.nis,
                                        text: item.nis + ' - ' + item.nama + txt,
                                        disabled: j >= 3
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }
        });
    </script>
    {{-- ABSENSI --}}
    <script>
        let map;
        let currentMarker;
        let targetMarker;
        let radiusCircle;

        function initMap(targetLat, targetLong, radiusInMeters, targetName) {
            if (!map) {
                // Inisialisasi map jika belum ada
                map = L.map('map').setView([targetLat, targetLong], 15);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
            }

            // Hapus circle dan marker lama jika ada
            if (radiusCircle) {
                map.removeLayer(radiusCircle);
            }
            if (targetMarker) {
                map.removeLayer(targetMarker);
            }

            // Tambahkan circle dengan radius baru
            radiusCircle = L.circle([targetLat, targetLong], {
                color: 'blue',
                fillColor: '#cce5ff',
                fillOpacity: 0.4,
                radius: radiusInMeters // Radius dalam meter
            }).addTo(map);

            // Tambahkan marker baru
            targetMarker = L.marker([targetLat, targetLong]).addTo(map)
                .bindPopup(targetName).openPopup();

            // Pusatkan peta pada lokasi target
            map.setView([targetLat, targetLong], 15);
        }


        function updateCurrentLocation(lat, long) {
            if (currentMarker) {
                currentMarker.setLatLng([lat, long]);
            } else {
                currentMarker = L.marker([lat, long], {
                        color: 'red'
                    }).addTo(map)
                    .bindPopup("Your Location").openPopup();
            }
            map.setView([lat, long]);
        }

        function getLocation() {
            // Ubah ikon menjadi spinner (loading)
            const icon = document.getElementById('icon-absen');
            icon.className = 'spinner-border spinner-border-sm';
            var cek_absen = '';
            $.ajax({
                url: '/d/siswa/cek-absen',
                type: 'GET',
                data: {
                    nis: '{{ $siswa->nis }}',
                    id: $('#id_penempatan').val(),
                },
                success: function(response) {
                    icon.className = 'bi bi-fingerprint';
                    cek_absen = response.message;
                    Swal.fire({
                        title: cek_absen + "?",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (response.data.status_bekerja == 'WFO') {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(successCallback,
                                        errorCallback);
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        title: 'Geolocation is not supported by this browser.'
                                    });
                                }
                            } else {
                                $('#myModalAbsensi').modal('show');
                            }

                        }
                    });
                },
                error: function(error) {
                    Toast.fire({
                        icon: "error",
                        title: 'Gagal absen: Terjadi kesalahan saat mencoba mengecek absen.'
                    });
                }
            });




        }

        function successCallback(position) {
            const lat = position.coords.latitude;
            const long = position.coords.longitude;
            console.log(lat, long);

            updateCurrentLocation(lat, long);

            const targetLat = $('#lt').val();
            const targetLong = $('#lg').val();
            const radiusInMeters = $('#radius').val(); // Radius in meters
            const radiusInDegrees = radiusInMeters / 111320; // Convert meters to degrees

            if (!map) {
                initMap(targetLat, targetLong, radiusInMeters);
            }

            const distance = Math.sqrt(Math.pow(lat - targetLat, 2) + Math.pow(long - targetLong, 2)) * 111320;

            if (distance <= radiusInMeters) {
                $('#myModalAbsensi').modal('show');
            } else {
                Toast.fire({
                    icon: "error",
                    title: 'Gagal absen: Lokasi Anda di luar radius yang diizinkan.'
                });
            }
        }

        function errorCallback(error) {
            Toast.fire({
                icon: "error",
                title: "Error in getting location: " + error.message
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const targetLat = $('#lt').val();
            const targetLong = $('#lg').val();
            const radiusInMeters = $('#radius').val(); // Radius in meters
            initMap(targetLat, targetLong, radiusInMeters, "Lokasi Target");
        });
    </script>

    @if (in_array(auth()->user()->role, [1, 2, 3]) ||
            in_array(session('id_instruktur'), $siswa->penempatan->pluck('instruktur.id_instruktur')->toArray()))
        <script>
            $(document).ready(function() {
                // Event onchange pada select
                $('#status_bekerja').on('change', function() {
                    // Ambil nilai yang dipilih
                    var selectedValue = $(this).val();
                    $.ajax({
                        url: "{{ route('d.siswa.change_status_kerja') }}",
                        method: "POST",
                        data: {
                            status_bekerja: selectedValue,
                            nis: "{{ $siswa->nis }}",
                        },
                        success: function(response) {
                            if (response.status) {
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: response.message
                                });
                            }
                        },
                        error: function(response) {
                            Toast.fire({
                                icon: "error",
                                title: 'Woops! Fatal Error.'
                            });
                        }
                    });
                });
            });
        </script>
    @endif

    {{-- KEGIATAN --}}
    <script>
        $(function() {
            if ($.fn.DataTable.isDataTable('#table-pengajuan-surat')) {
                $('#table-pengajuan-surat').DataTable().destroy();
            }

            var table1 = $('#table-presensi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('d.siswa.kegiatan.data') }}",
                    type: 'GET',
                    data: function(d) {
                        // Menambahkan parameter tambahan yang akan dikirim ke server
                        d.nis =
                            '{{ $siswa->nis }}'; // Ambil nilai NIS dari elemen input atau lainnya
                        d.stt = '1';
                        d.id_penempatan = $('#id_penempatan').val();
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'presensi_masuk',
                        name: 'masuk'
                    },
                    {
                        data: 'presensi_pulang',
                        name: 'pulang'
                    }
                ]
            });

            var table2 = $('#table-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('d.siswa.kegiatan.data') }}",
                    type: 'GET',
                    data: function(d) {
                        // Menambahkan parameter tambahan yang akan dikirim ke server
                        d.nis =
                            '{{ $siswa->nis }}'; // Ambil nilai NIS dari elemen input atau lainnya
                        d.stt = '2';
                        d.id_penempatan = $('#id_penempatan').val();
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'kegiatan',
                        name: 'kegiatan'
                    },
                    {
                        data: 'is_acc_instruktur',
                        name: 'is_acc_instruktur',
                        render: function(data) {
                            return data === 1 ? 'Ya' : (data === 0 ? 'Tidak' : '');
                        }
                    },
                    {
                        data: 'is_acc_guru',
                        name: 'is_acc_guru',
                        render: function(data) {
                            return data === 1 ? 'Ya' : (data === 0 ? 'Tidak' : '');
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            var table3 = $('#table-catatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('d.siswa.kegiatan.data') }}",
                    type: 'GET',
                    data: function(d) {
                        // Menambahkan parameter tambahan yang akan dikirim ke server
                        d.nis =
                            '{{ $siswa->nis }}'; // Ambil nilai NIS dari elemen input atau lainnya
                        d.stt = '3';
                        d.id_penempatan = $('#id_penempatan').val();
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    }
                ]
            });

            var table5 = $('#table-nilai').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('d.siswa.penilaian') !!}',
                    type: 'GET',
                    data: function(d) {
                        // Menambahkan parameter tambahan yang akan dikirim ke server
                        d.nis =
                            '{{ $siswa->nis }}'; // Ambil nilai NIS dari elemen input atau lainnya
                    }
                },
                columns: [{
                        data: 'laporan',
                        name: 'laporan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    }
                ]
            });

            var tablePengajuan = $('#table-pengajuan-surat').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengajuan.surat.get') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'namasiswa',
                        name: 'namasiswa'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'perusahaan_tujuan',
                        name: 'perusahaan_tujuan'
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'detailsiswa',
                        name: 'detailsiswa',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'balasan_dudi',
                        name: 'balasan_dudi',
                        orderable: false,
                        searchable: false
                    }
                ],
            });


            $('#id_penempatan').on('change', function() {
                $('#btnAbsen').hide();
                table1.ajax.reload();
                table2.ajax.reload();
                table3.ajax.reload();
                table5.ajax.reload();
                $('#id_penempatan1').val($('#id_penempatan').val());

                $.ajax({
                    url: "{{ route('d.siswa.get_penempatan_detail') }}", // Rute untuk mendapatkan instruktur berdasarkan siswa
                    type: "GET",
                    data: {
                        id: $('#id_penempatan').val(),
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const lt = response.data.dudi.latitude;
                            const lg = response.data.dudi.longitude;
                            const radius = response.data.dudi.radius;
                            const nama = response.data.dudi.nama;
                            $('#lt').val(lt);
                            $('#lg').val(lg);
                            $('#radius').val(radius);
                            $('#radius_text').text(radius);
                            initMap(lt, lg, radius, nama);
                            $('#btnAbsen').show();
                        } else {
                            alert('Detail penempatan gagal diload.');
                        }
                    },
                    error: function() {
                        alert('Gagal mendapatkan detail Penempatan.');
                    }
                });
            });
            $('#id_ta').on('change', function() {
                $('#id_penempatan').empty();
                // Lakukan AJAX untuk mendapatkan instruktur terkait
                $.ajax({
                    url: "{{ route('d.siswa.get_penempatan') }}", // Rute untuk mendapatkan instruktur berdasarkan siswa
                    type: "GET",
                    data: {
                        nis: '{{ $siswa->nis }}',
                        id_ta: $('#id_ta').val(),
                    },
                    success: function(response) {

                        if (response.status === 'success') {
                            // Loop data untuk dimasukkan ke dalam select
                            response.data.forEach(item => {
                                const optionText =
                                    `DUDI: ${item.dudi.nama} - Instruktur: ${item.instruktur.nama} - Guru: ${item.guru.nama} `;
                                $('#id_penempatan').append(
                                    `<option value="${item.id_penempatan}" >${optionText}</option>`
                                );
                            });
                            $('#id_penempatan').change();
                            if (typeof getQuestion === "function") {
                                getQuestion(); // Panggil fungsi jika ada
                            }
                        } else {
                            alert('Penempatan tidak ditemukan untuk siswa ini.');
                        }
                    },
                    error: function() {
                        alert('Gagal mendapatkan data Penempatan.');
                    }
                });
            });
            $('#id_ta').change();

            $('#table-kegiatan').on('click', '.btn-edit', function() {
                var data = table2.row($(this).closest('tr')).data();
                $('#id_presensi').val(data.id_presensi);
                $('#tanggal').val(data.tanggal);
                $('#kegiatan').val(data.kegiatan);

                $('#myModal').modal('show');
            });

            $('#myForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                console.log(formData);
                var url = "{{ route('d.siswa.kegiatan.update') }}";

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#myModal').modal('hide');
                        if (response.status) {
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                            $('#id_penempatan').change();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: 'Woops! Fatal Error.'
                        });
                    }
                });
            });

            $('#myFormAbsensi').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = "{{ route('d.siswa.absen') }}";

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#myModalAbsensi').modal('hide');
                        if (response.status) {
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                            $('#id_penempatan').change();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: 'Woops! Fatal Error.'
                        });
                    }
                });
            });

            // js pengajuan surat
            $('#formPengajuan').on('submit', function(e) {
                e.preventDefault();
                // Ambil nilai tanggal
                var tglMulai = $('#tanggalMulai').val();
                var tglSelesai = $('#tanggalSelesai').val();

                if (tglMulai && tglSelesai) {
                    var start = new Date(tglMulai);
                    var end = new Date(tglSelesai);

                    // Hitung selisih bulan
                    var months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start
                        .getMonth());
                    // Jika tanggal selesai di hari yang sama atau lebih awal, atau selisih hari < 0, invalid
                    if (end < start) {
                        alert('Tanggal selesai tidak boleh sebelum tanggal mulai.');
                        e.preventDefault();
                        return false;
                    }
                    // Jika selisih bulan < 3 atau > 4, invalid
                    if (months < 3 || months > 4 || (months === 4 && end.getDate() < start.getDate())) {
                        alert('Jangka waktu PKL minimal 3 bulan dan maksimal 4 bulan.');
                        e.preventDefault();
                        return false;
                    }
                }
                var id = $('#pengajuanId').val();
                var url = id ? "{{ route('pengajuan.surat.update', '') }}/" + id :
                    "{{ route('pengajuan.surat.store') }}";
                var method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this)
                        .serialize(), // Pastikan field "tanggal_pengajuan" ada dalam serialize
                    success: function(response) {
                        $('#pengajuanModal').modal('hide');
                        $('#formPengajuan')[0].reset();
                        $('#table-pengajuan-surat').DataTable().ajax.reload();
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Tampilkan error pada console untuk debug
                            console.error(xhr.responseJSON.errors);
                        }
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan"
                        });
                    }
                });
            });
        });





        function getLaporan(link) {
            var id_ta = $('#id_ta').val();
            var id_penempatan = $('#id_penempatan').val();
            window.open(link + '{{ $siswa->nis }}/' + id_penempatan + '/' + id_ta, '_blank');
        }

        function getResume() {
            var id_penempatan = $('#id_penempatan').val();
            window.open("{{ route('d.siswa.resumePdf', $siswa->nis) }}" + '?id_penempatan=' + id_penempatan, '_blank');
        }
    </script>

    @if (session('nis') == $siswa->nis || in_array(auth()->user()->role, [1, 2]))
        <script>
            $(document).ready(function() {
                // Handle password change submission
                $('#changePasswordForm').on('submit', function(e) {
                    e.preventDefault();

                    // Ambil nilai input dari form
                    const currentPassword = $('#currentPassword').val();
                    const newPassword = $('#newPassword').val();
                    const renewPassword = $('#renewPassword').val();

                    // Validasi sederhana sebelum mengirim request
                    if (newPassword !== renewPassword) {
                        Toast.fire({
                            icon: "error",
                            title: "Password baru tidak cocok. Silakan coba lagi."
                        });
                        return;
                    }

                    // AJAX POST request
                    $.ajax({
                        url: "{{ route('d.siswa.akun.update') }}", // Ganti dengan URL endpoint untuk mengubah password
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF token
                            current_password: currentPassword,
                            new_password: newPassword,
                            renew_password: renewPassword,
                            nis: '{{ $siswa->nis }}'
                        },
                        success: function(response) {
                            // Menangani respons sukses
                            if (response.status === 'success') {
                                Toast.fire({
                                    icon: "success",
                                    title: "Password berhasil diubah."
                                });
                                $('#changePasswordForm')[0].reset(); // Reset form
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: response.message ||
                                        "Terjadi kesalahan saat mengubah password."
                                });
                            }
                        },
                        error: function(xhr) {
                            // Menangani respons error
                            console.log(xhr.message);

                            Toast.fire({
                                icon: "error",
                                title: xhr.message ||
                                    "Terjadi kesalahan. Silakan coba lagi."
                            });
                        }
                    });
                });


            });
        </script>

        
        <script>
            const editModal = document.getElementById('editModal');
            if (editModal) editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button yang ditekan
                const fileId = button.getAttribute('data-id'); // ID file
                const fileName = button.getAttribute('data-name'); // Nama file

                const form = document.getElementById('editForm');
                form.action = `/d/update/${fileId}`; // Update action URL

                const nameInput = document.getElementById('new_name');
                nameInput.value = fileName; // Isi input dengan nama file
            });
        </script>


        {{-- foto --}}
        <script>
            $(document).ready(function() {
                // Menangani perubahan file foto
                $('#foto_profile').on('change', function(e) {
                    var formData = new FormData();
                    formData.append('foto_profile', this.files[0]); // Menambahkan file yang dipilih ke formData
                    formData.append('_token', '{{ csrf_token() }}'); // Menambahkan CSRF token

                    // AJAX untuk meng-upload foto ke server
                    $.ajax({
                        url: "{{ route('d.siswa.updateFoto', $siswa->nis) }}", // Sesuaikan dengan route yang meng-handle upload foto
                        type: 'POST',
                        data: formData,
                        contentType: false, // Menghindari pengaturan contentType secara otomatis
                        processData: false, // Agar data tidak diproses sebelum dikirim
                        success: function(response) {
                            if (response.success) {
                                // Update gambar profil setelah berhasil di-upload
                                $('#profileImage').attr('src', response.imageUrl); // Update src img
                                Toast.fire({
                                    icon: "success",
                                    title: 'Foto profil berhasil diperbarui!'
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: 'Gagal mengganti foto!'
                                });
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat meng-upload foto.');
                        }
                    });
                });
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#perusahaan').select2({
                theme: 'bootstrap-5',
                dropdownParent: $("#pengajuanModal"),
                placeholder: 'Cari atau pilih perusahaan...',
                allowClear: true,
                ajax: {
                    url: "{{ route('siswa.perusahaan.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.results, function(item) {
                                return {
                                    id: item.id_dudi,
                                    text: item.text
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#addPerusahaanBtn').on('click', function() {
                $('#modalTambahPerusahaan').modal('show');
            });

            $('#formTambahPerusahaan').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).text('Menyimpan...');
                // Reset error
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').hide();
                $.ajax({
                    url: "{{ route('siswa.dudi.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success) {
                            // Tambahkan ke select2 dan pilih otomatis
                            var newOption = new Option(res.text, res.id_dudi, true, true);
                            $('#perusahaan').append(newOption).trigger('change');
                            $('#modalTambahPerusahaan').modal('hide');
                            form[0].reset();
                            // Reset map marker
                            if (typeof markerDudi !== 'undefined' && markerDudi) {
                                mapDudi.removeLayer(markerDudi);
                                markerDudi = null;
                            }
                            if (typeof radiusCircleDudi !== 'undefined' && radiusCircleDudi) {
                                mapDudi.removeLayer(radiusCircleDudi);
                                radiusCircleDudi = null;
                            }
                            $('#dudi_latitude1, #dudi_longitude1, #dudi_latitude, #dudi_longitude')
                                .val('');
                        } else if (res.errors) {
                            // Tampilkan error validasi
                            for (const [field, messages] of Object.entries(res.errors)) {
                                var input = form.find('[name="' + field + '"], [id="' + field +
                                    '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').show().text(messages[0]);
                            }
                        } else {
                            alert('Gagal menambah perusahaan.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Tampilkan error validasi
                            for (const [field, messages] of Object.entries(xhr.responseJSON
                                    .errors)) {
                                var input = form.find('[name="' + field + '"], [id="' + field +
                                    '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').show().text(messages[0]);
                            }
                        } else {
                            alert(
                                'Gagal menambah perusahaan. Pastikan semua data terisi dengan benar.'
                                );
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Simpan');
                    }
                });
            });
        });
    </script>

    <!-- pengajuan surat: siswa dinamis -->
    <script>
        function initSelect2Siswa(selector) {
            $(selector).select2({
                theme: 'bootstrap-5',
                dropdownParent: $("#pengajuanModal"),
                placeholder: 'Cari Siswa NIS/Nama...',
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('siswa.searchh') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            k: 'presensi',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                var j = 0;
                                item.penempatan.forEach(el => {
                                    if (el.id_ta == $('#id_ta').val()) {
                                        j++;
                                    }
                                });
                                var txt = j > 0 ? ` (Sudah di Tempatkan ${j}x)` : '';
                                return {
                                    id: item.nis,
                                    text: item.nis + ' - ' + item.nama + txt,
                                    disabled: j >= 3
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        $(document).ready(function() {
            $(document).on('click', '.pengajuanSuratModal', function() {
                var nisSiswa = $(this).data('id');
                $.ajax({
                    url: "{{ route('siswa.pengajuan.cekPengajuan') }}",
                    method: "POST",
                    data: {
                        nisSiswa: nisSiswa,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            // ✅ Tampilkan modal
                            $('#pengajuanModal').modal('show');
                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message || 'Pengajuan tidak bisa dilanjutkan.'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                'Terjadi kesalahan saat memproses pengajuan.'
                        });
                    }
                });

            });

            // Inisialisasi select2 untuk input pertama
            initSelect2Siswa('#nis1');
            let siswaCount = 1;
            const maxSiswa = 4;
            // Tambah siswa
            $(document).on('click', '.add-siswa-btn', function() {
                if (siswaCount >= maxSiswa) return;
                siswaCount++;
                const row = $(`
            <div class=\"row mb-3 siswa-input-row align-items-center flex-nowrap\">
                <div class=\"col-3 text-start\">
                    <label for=\"nis${siswaCount}\" class=\"form-label mb-0\">Siswa ${siswaCount}</label>
                </div>
                <div class=\"col position-relative\">
                    <select id=\"nis${siswaCount}\" class=\"form-select w-100 siswa-select\" name=\"namaSiswa[]\" required></select>
                </div>
                <div class=\"col-auto ps-0\">
                    <button type=\"button\" class=\"btn btn-danger btn-sm remove-siswa-btn d-flex align-items-center justify-content-center\" title=\"Hapus Siswa\" style=\"width:32px; height:38px;\"><i class=\"bi bi-dash\"></i></button>
                </div>
            </div>
        `);
                $('#siswa-input-group').append(row);
                initSelect2Siswa(`#nis${siswaCount}`);
                if (siswaCount >= maxSiswa) {
                    $('.add-siswa-btn').prop('disabled', true);
                }
            });
            // Hapus siswa
            $(document).on('click', '.remove-siswa-btn', function() {
                $(this).closest('.siswa-input-row').remove();
                siswaCount--;
                $('.add-siswa-btn').prop('disabled', false);
                // Update label dan id
                $('#siswa-input-group .siswa-input-row').each(function(idx, el) {
                    $(el).find('label').text('Siswa ' + (idx + 1));
                    $(el).find('label').attr('for', 'nis' + (idx + 1));
                    $(el).find('select').attr('id', 'nis' + (idx + 1));
                });
            });


            // nampilkan modal diterima untuk upload surat balasan
            $(document).on('click', '.btn-diterima', function() {
                var id_pengajuan = $(this).data('id');
                $('#id_pengajuan').val(id_pengajuan);

                var idPerusahaan = $(this).data('iddudi');
                var namaPerusahaan = $(this).data('namadudi');

                // Isi elemen <select> dengan data yang diambil
                $('#idPerusahaan').html('<option value="' + idPerusahaan + '">' + namaPerusahaan + '</option>');

                $('#modalBalasanDudi').modal('show');
            });

            $(document).on('click', '.btn-ditolak', function() {
                var id_pengajuan = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Benar Balasan DUDI nya menolak surat kamu?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('siswa.pengajuan.isDitolak') }}",
                            method: "POST",
                            data: {
                                id_pengajuan: id_pengajuan,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                if (res.status || res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Surat berhasil ditolak.'
                                    }).then(() => {
                                        // reload datatable jika ada
                                        $('#table-pengajuan-surat').DataTable().ajax.reload();
                                        if (typeof $('#pengajuanTable')
                                            .DataTable === 'function') {
                                            $('#pengajuanTable').DataTable()
                                                .ajax.reload();
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: res.message ||
                                            'Gagal menolak surat.'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: xhr.responseJSON && xhr.responseJSON
                                        .message ? xhr.responseJSON.message :
                                        'Terjadi kesalahan saat menolak surat.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>


{{-- tambah Instruktur --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.detail-btn', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "/pengajuan/surat/details/" + id,
                    method: "GET",
                    success: function(response) {
                        var tableRows = response.siswa.map(function(siswa, index) {
                            return `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${siswa.nim}</td>
                            <td>${siswa.nama}</td>
                            <td>${siswa.kelas}</td>
                            <td>${siswa.jurusan}</td>
                        </tr>
                    `;
                        }).join('');

                        $('#suratModalBody').html(`
                    <h5>Daftar Siswa</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                `);
                        $('#suratModal').modal('show');
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat memuat data.');
                    }
                });
            });
        $('#formTambahInstruktur').on('submit', function(e) {
            e.preventDefault();
            $(this).removeClass('was-validated');

            if (this.checkValidity() === false) {
                $(this).addClass('was-validated');
                return;
            }

            var formData = new FormData(this);
            var url = "{{ route('siswa.instruktur.upsert') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message || 'Instruktur berhasil disimpan.'
                        }).then(() => {
                            $('#instrukturTable').DataTable().ajax.reload();
                            if ($.fn.DataTable.isDataTable('#pengajuanTable')) {
                                $('#pengajuanTable').DataTable().ajax.reload();
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Gagal menyimpan instruktur.'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors)
                            .map(msgArr => msgArr.join(', '))
                            .join('\n');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: messages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menambahkan instruktur.'
                        });
                    }
                }
            });
        });
    });
    </script>
@endsection

@section('css')
    <link href="{{ asset('assets') }}/vendor/leaflet/leaflet.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .siswa-select,
        .select2-container,
        .select2-selection--single {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            overflow: hidden !important;
        }
    </style>
@endsection
