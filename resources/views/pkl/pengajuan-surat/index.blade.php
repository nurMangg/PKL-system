@extends('layouts.main')
@section('title', 'Pengajuan Surat')
@section('css')
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <h4 class="my-4">Pengajuan Surat</h4>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#pengajuanModal"
                onclick="resetForm()">Tambah Pengajuan</button>
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
                        <th>Tgl Selai</th>
                        <th>Kepada Yth</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat oleh DataTables -->
                </tbody>
            </table>
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
                    <form id="formPengajuan">
                        <input type="hidden" id="pengajuanId" name="id">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="mb-3">
                                <label for="namaSiswa{{ $i }}" class="form-label">Nama Siswa
                                    {{ $i }}</label>
                                <select type="text" id="nis{{ $i }}" class="form-select"
                                    id="namaSiswa{{ $i }}" name="namaSiswa[]" required></select>
                            </div>
                        @endfor
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Perusahaan Tujuan</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan_tujuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalPengajuan" class="form-label">Tanggal Pengajuan</label>
                            <input type="date" class="form-control" id="tanggalPengajuan" name="tanggal_pengajuan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggalMulai" name="tanggal_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggalSelesai" name="tanggal_selesai" required>
                        </div>
                        <div class="mb-3">
                            <label for="kepadaYth" class="form-label">Kepada Yth</label>
                            <input type="text" class="form-control" id="kepada_yth" name="kepada_yth" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- buton modal ditolak/distujui -->
<section>
    <!-- Modal Keterangan Penolakan -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Keterangan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formReject">
                        <input type="hidden" id="rejectId" name="id">
                        <div class="mb-3">
                            <label for="keteranganPenolakan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPenolakan" name="keterangan" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- pembuatan surat -->
<section>
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

    <div class="modal fade" id="pengajuanSuratModal" tabindex="-1" aria-labelledby="pengajuanSuratModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengajuanSuratModal">Form No Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="nosuratModalBody">
                    <form id="tullisNoSurat">
                        <input type="hidden" id="noPengajuanSurat" name="noPengajuanSurat">
                        <div class="mb-3">
                            <label for="nomorSurat" class="form-label">Tulis No Surat</label>
                            <input class="form-control" id="nomorSurat" name="nomorSurat" rows="3"
                                required></input>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
    <!-- DataTables CSS & JS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                                        text: item.nis + ' - ' + item.nama +
                                            txt // Tampilkan data siswa
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

    <!-- butoon ditolak/distujui -->
    <script>
        $(document).on('click', '.reject-btn', function() {
            var id = $(this).data('id');
            $('#rejectId').val(id);
            $('#rejectModal').modal('show');
        });

        $('#formReject').on('submit', function(e) {
            e.preventDefault();
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
    </script>

    <!-- detail siswa -->
    <script>
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
    </script>

    <!-- pembuatan surat -->
    <script>
        $(document).on('click', '.approve-btn', function() {
            var id = $(this).data('id');
            document.getElementById('noPengajuanSurat').value = id;

            $('#pengajuanSuratModal').modal('show');

        });

        $(document).on('click', '.ditempatkan-btn', function() {
            var id = $(this).data('id');

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
                        data: {
                            id_pengajuan: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.status || res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data Berhasil Ditempatkan.'
                                }).then(() => {
                                    // reload datatable jika ada
                                    $('#pengajuan-surat').DataTable().ajax.reload();

                                    $('#pengajuanTable').DataTable()
                                            .ajax.reload();

                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: res.message ||
                                        'Gagal Menempatkan.'
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
                        alert('Gagal membuat surat.');
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
                        alert('Gagal membuat surat. Pastikan semua data terisi dengan benar.');
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
                ajax: "{{ route('pengajuan.surat.getAll') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                    [4, 'desc']
                ], // Urutkan berdasarkan tanggal_pengajuan descending
            });

            // Submit Form Tambah/Edit
            $('#formPengajuan').on('submit', function(e) {
                e.preventDefault();
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
                        $('#pengajuan-surat').DataTable().ajax.reload();
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



            $('#pengajuan-surat').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                if (confirm("Yakin ingin menghapus data ini?")) {
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
