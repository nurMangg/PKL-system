@extends('layouts.main')
@section('title')
    Status PKL
@endsection
@section('pagetitle')
    <div class="pagetitle">
        <h1>Status PKL</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">PKL</li>
                <li class="breadcrumb-item active">Status PKL</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h5>Data Status PKL</h5>
                <div>
                    <button class="btn btn-danger btn-sm me-2" onclick="getLaporan('/status-pkl/downloadExcel')"><i class="bi bi-download"></i> Download</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="id_ta1" class="form-label">Tahun Akademik</label>
                <select class="form-select" id="id_ta1" name="id_ta1" required>
                    <option value="">Pilih</option>
                    @foreach ($thnAkademik as $item)
                        @if ($item->id_ta == $aktifAkademik->id_ta)
                            <option value="{{ $item->id_ta }}" selected>{{ $item->tahun_akademik }} (aktif)</option>
                        @else
                            <option value="{{ $item->id_ta }}">{{ $item->tahun_akademik }}</option>
                        @endif
                    @endforeach
                </select>
                <div class="invalid-feedback"> Tahun Akademik wajib dipilih. </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="myTable">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Siswa</th>
                            <th>Jurusan</th>
                            <th>Perusahaan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Lama PKL</th>
                            <th>Guru</th>
                            <th>Instruktur</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.js"></script>
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/select2/js/select2.min.js"></script>
    <script>
        $(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('status-pkl.data')  }} ",
                    type: 'POST',
                    data: function(d) {
                        d.id_ta = $('#id_ta1').val();
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                        data: 'nis',
                        name: 'nis'
                    },
                    {
                        data: 'nisn',
                        name: 'nisn'
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'nama_dudi',
                        name: 'nama_dudi'
                    },
                    {
                        data: 'tanggal_mulai',
                        name: 'tanggal_mulai'
                    },
                    {
                        data: 'tanggal_selesai',
                        name: 'tanggal_selesai'
                    },
                    {
                        data: null,
                        name: 'lama_pkl',
                        render: function(data, type, row) {
                            var tanggalMulai = row.tanggal_mulai;
                            var tanggalSelesai = row.tanggal_selesai;
                            if (!tanggalMulai) return '-';

                            // Konversi ke objek Date (pastikan format yyyy-mm-dd)
                            var mulai = new Date(tanggalMulai);
                            var selesai = tanggalSelesai ? new Date(tanggalSelesai) : null;
                            var hariIni = new Date();
                            hariIni.setHours(0,0,0,0);

                            // Jika tanggal mulai di masa depan, tampilkan 0 hari
                            if (mulai > hariIni) {
                                return '0 hari';
                            }

                            // Hitung total hari PKL (dari mulai sampai selesai, inklusif)
                            var totalHari = 0;
                            if (selesai) {
                                // Jika tanggal selesai di masa depan, total hari = mulai - selesai
                                totalHari = Math.floor((selesai - mulai) / (1000 * 60 * 60 * 24)) + 1;
                                if (totalHari < 0) totalHari = 0;
                            } else {
                                // Jika belum selesai, total hari = mulai - hari ini
                                totalHari = Math.floor((hariIni - mulai) / (1000 * 60 * 60 * 24)) + 1;
                                if (totalHari < 0) totalHari = 0;
                            }

                            // Hitung sudah berapa lama PKL per hari ini (jika belum selesai)
                            var sudahBerapaHari = 0;
                            if (!selesai || selesai > hariIni) {
                                sudahBerapaHari = Math.floor((hariIni - mulai) / (1000 * 60 * 60 * 24)) + 1;
                                if (sudahBerapaHari < 0) sudahBerapaHari = 0;
                            } else if (selesai && selesai <= hariIni) {
                                sudahBerapaHari = totalHari;
                            }

                            // Tampilkan keterangan
                            var result = '';
                            if (selesai) {
                                result = totalHari + ' hari';
                                // Jika PKL masih berjalan (hari ini sebelum tanggal selesai)
                                if (hariIni < selesai) {
                                    result += ' (per hari ini: ' + sudahBerapaHari + ' hari)';
                                }
                            } else {
                                result = totalHari + ' hari (per hari ini: ' + sudahBerapaHari + ' hari)';
                            }
                            return result;
                        }
                    },
                    {
                        data: 'nama_guru',
                        name: 'nama_guru'
                    },
                    {
                        data: 'nama_instruktur',
                        name: 'nama_instruktur'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ]
            });
            $('#id_ta1').on('change', function() {
                table.ajax.reload();
            });

        });
        function getLaporan(link) {
            var id_ta = $('#id_ta1').val();
            window.open(link+'?id_ta='+id_ta, '_blank');
        }
    </script>
@endsection
@section('css')
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection
