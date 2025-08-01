@extends('layouts.main')
@section('title')
    Pengajuan Surat
@endsection
@section('pagetitle')
    <div class="pagetitle">
        <h1>Pengajuan Surat</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">PKL</li>
                <li class="breadcrumb-item active">Pengajuan Surat</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h5>Data Pengajuan Surat</h5>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pengajuanModal" onclick="resetForm()">
                        <i class="bi bi-plus-square"></i> Tambah
                    </button>
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
                <table class="table table-striped table-sm" id="pengajuan-surat" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Perusahaan Tujuan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Kepada Yth</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="pengajuanModal" tabindex="-1" aria-labelledby="pengajuanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengajuanModalLabel">Pengajuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPengajuan" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="pengajuanId" name="id">
                        <div class="mb-3">
                            <label for="id_ta" class="form-label">Tahun Akademik</label>
                            <select class="form-select" id="id_ta" name="id_ta" required>
                                <option value="">Pilih</option>
                                @foreach ($thnAkademik as $item)
                                    @if ($item->id_ta == $aktifAkademik->id_ta)
                                        <option value="{{ $item->id_ta }}" selected>{{ $item->tahun_akademik }} (aktif)</option>
                                    @else
                                        <option value="{{ $item->id_ta }}">{{ $item->tahun_akademik }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Tahun Akademik wajib dipilih.</div>
                        </div>
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="mb-3">
                                <label for="namaSiswa{{ $i }}" class="form-label">Nama Siswa {{ $i }}</label>
                                <select type="text" id="nis{{ $i }}" class="form-select" name="namaSiswa[]" required></select>
                                <div class="invalid-feedback">Siswa wajib dipilih.</div>
                            </div>
                        @endfor
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Perusahaan Tujuan</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan_tujuan" required>
                            <div class="invalid-feedback">Perusahaan tujuan wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalPengajuan" class="form-label">Tanggal Pengajuan</label>
                            <input type="date" class="form-control" id="tanggalPengajuan" name="tanggal_pengajuan" required>
                            <div class="invalid-feedback">Tanggal pengajuan wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggalMulai" name="tanggal_mulai" required>
                            <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggalSelesai" name="tanggal_selesai" required>
                            <div class="invalid-feedback">Tanggal selesai wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label for="kepadaYth" class="form-label">Kepada Yth</label>
                            <input type="text" class="form-control" id="kepada_yth" name="kepada_yth" required>
                            <div class="invalid-feedback">Kepada Yth wajib diisi.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Keterangan Penolakan -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Keterangan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formReject" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="rejectId" name="id">
                        <div class="mb-3">
                            <label for="keteranganPenolakan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPenolakan" name="keterangan" rows="3" required></textarea>
                            <div class="invalid-feedback">Keterangan wajib diisi.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Surat Persetujuan -->
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

    <!-- Modal Pilih Guru -->
    <div class="modal fade" id="guruModal" tabindex="-1" aria-labelledby="guruModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guruModalLabel">Pilih Guru Pembimbing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGuru" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="guruId" name="id">
                        <div class="mb-3">
                            <label for="guru" class="form-label">Guru</label>
                            <select type="text" id="guru" class="form-select" name="guru" required></select>
                            <div class="invalid-feedback">Guru wajib dipilih.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form No Surat -->
    <div class="modal fade" id="pengajuanSuratModal" tabindex="-1" aria-labelledby="pengajuanSuratModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengajuanSuratModal">Form No Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="nosuratModalBody">
                    <form id="tullisNoSurat" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="noPengajuanSurat" name="noPengajuanSurat">
                        <div class="mb-3">
                            <label for="nomorSurat" class="form-label">Tulis No Surat</label>
                            <input class="form-control" id="nomorSurat" name="nomorSurat" required>
                            <div class="invalid-feedback">Nomor surat wajib diisi.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.js"></script>
    <script src="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            for (let i = 1; i <= 4; i++) {
                $(`#nis${i}`).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $("#pengajuanModal"),
                    placeholder: 'Cari Siswa NIS/Nama...',
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('siswa.search') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                k: 'penempatan',
                                id_ta: $('#id_ta').val() // Tambahkan id_ta untuk filter
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    var txt = '';
                                    var j = 0;
                                    item.penempatan.forEach(el => {
                                        if (el.id_ta == $('#id_ta').val()) {
                                            j++;
                                            txt = ` (Sudah di Tempatkan ${j}x)`;
                                        }
                                    });
                                    return {
                                        id: item.nis,
                                        text: item.nis + ' - ' + item.nama + txt
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }
        });

        // Buton ditolak/disetujui
        $(document).on('click', '.reject-btn', function() {
            var id = $(this).data('id');
            $('#rejectId').val(id);
            $('#rejectModal').modal('show');
        });

        $('#formReject').on('submit', function(e) {
            e.preventDefault();
            var form = $(this)[0];
            if (form.checkValidity() === false) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }

            var id = $('#rejectId').val();
            var keterangan = $('#keteranganPenolakan').val();

            $.ajax({
                url: "{{ route('pengajuan.surat.reject', '') }}/" + id,
                method: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    keterangan: keterangan
                },
                success: function(response) {
                    $('#rejectModal').modal('hide');
                    $('#formReject')[0].reset();
                    $('#pengajuan-surat').DataTable().ajax.reload();
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                },
                error: function() {
                    Toast.fire({
                        icon: "error",
                        title: "Terjadi kesalahan"
                    });
                }
            });
        });

        // Detail siswa
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
                    Toast.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat memuat data."
                    });
                }
            });
        });

        // Pembuatan surat
        $(document).on('click', '.approve-btn', function() {
            var id = $(this).data('id');
            document.getElementById('noPengajuanSurat').value = id;
            $('#pengajuanSuratModal').modal('show');
        });

        $(document).on('click', '.ditempatkan-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('pengajuan.surat.guru', '') }}/" + id,
                method: "GET",
                success: function(response) {
                    $('#guruId').val(id);
                    var select = $('#guru');
                    select.find('option').remove();
                    $.each(response.results, function(key, value) {
                        select.append('<option value="' + value.id_guru + '">' + value.nama + '</option>');
                    });
                    $('#guruModal').modal('show');
                }
            });
        });

        $('#formGuru').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Benar Data ini akan ditempatkan sekarang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Benar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('siswa.pengajuan.isTempatkan') }}",
                        method: "POST",
                        data: form.serialize(),
                        success: function(res) {
                            btn.prop('disabled', false).text('Simpan');
                            if (res.status || res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data Berhasil Ditempatkan.'
                                }).then(() => {
                                    $('#pengajuan-surat').DataTable().ajax.reload();
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
                                    text: res.message || 'Gagal Menempatkan.'
                                });
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).text('Simpan');
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menolak surat.'
                            });
                        }
                    });
                } else {
                    btn.prop('disabled', false).text('Simpan');
                }
            });
        });

        $('#tullisNoSurat').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{ route('pengajuan.surat.approve') }}",
                method: 'POST',
                data: form.serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        $('#pengajuanSuratModal').modal('hide');
                        form[0].reset();
                        $('#pengajuan-surat').DataTable().ajax.reload();
                        Toast.fire({
                            icon: "success",
                            title: res.message
                        });
                    } else if (res.errors) {
                        // Tampilkan error validasi
                        for (const [field, messages] of Object.entries(res.errors)) {
                            var input = form.find('[name="' + field + '"], [id="' + field + '"]');
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').show().text(messages[0]);
                        }
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal membuat surat."
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        // Tampilkan error validasi
                        for (const [field, messages] of Object.entries(xhr.responseJSON.errors)) {
                            var input = form.find('[name="' + field + '"], [id="' + field + '"]');
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').show().text(messages[0]);
                        }
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal membuat surat. Pastikan semua data terisi dengan benar."
                        });
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Simpan');
                }
            });
        });
    </script>

    <script>
        $(function() {
            // Inisialisasi DataTables
            var tablePengajuan = $('#pengajuan-surat').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengajuan.surat.getAll') }}",
                    type: "GET",
                    data: function(d) {
                        d.id_ta = $('#id_ta1').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
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
                        name: 'status'
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
                        data: 'kepada_yth',
                        name: 'kepada_yth'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [2, 'desc']
                ],
                rowId: function(a) {
                    // Pastikan setiap row memiliki id unik, misal dari kolom 'id'
                    return a.id ? 'row_' + a.id : null;
                }
            });

            $('#id_ta1').on('change', function() {
                tablePengajuan.ajax.reload();
            });

            // Submit Form Tambah/Edit
            $('#formPengajuan').on('submit', function(e) {
                e.preventDefault();
                var form = $(this)[0];
                if (form.checkValidity() === false) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }

                var id = $('#pengajuanId').val();
                var url = id ? "{{ route('pengajuan.surat.update', '') }}/" + id : "{{ route('pengajuan.surat.store') }}";
                var method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#pengajuanModal').modal('hide');
                        $('#formPengajuan')[0].reset();
                        $('#pengajuan-surat').DataTable().ajax.reload();
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            console.error(xhr.responseJSON.errors);
                        }
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan"
                        });
                    }
                });
            });

            $('#pengajuan-surat').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pengajuan.surat.delete', '') }}/" + id,
                            method: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                $('#pengajuan-surat').DataTable().ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: "error",
                                    title: "Terjadi kesalahan"
                                });
                            }
                        });
                    }
                });
            });
        });

        // Fungsi Edit
        function editData(id) {
            $.get("{{ route('pengajuan.surat.edit', '') }}/" + id, function(data) {
                $('#pengajuanModalLabel').text("Edit Pengajuan");
                $('#pengajuanId').val(data.id);
                $('#jurusan').val(data.jurusan);
                $('#perusahaan').val(data.perusahaan_tujuan);
                $('#tanggalPengajuan').val(data.tanggal_pengajuan);

                // Set nama siswa
                data.nama_siswa.forEach((nama, index) => {
                    $(`#namaSiswa${index + 1}`).val(nama);
                });

                $('#pengajuanModal').modal('show');
            });
        }

        // Reset Form Tambah/Edit
        function resetForm() {
            $('#pengajuanModalLabel').text("Tambah Pengajuan");
            $('#formPengajuan')[0].reset();
            $('#pengajuanId').val('');
        }
    </script>
@endsection

@section('css')
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection
