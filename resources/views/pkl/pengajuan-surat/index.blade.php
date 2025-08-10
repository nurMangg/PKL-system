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
                    <form id="formPengajuan" class="needs-validation" novalidate>
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

                        <div id="siswa-input-group">
                            <div class="row mb-3 siswa-input-row align-items-center flex-nowrap">
                                <div class="col-3 text-start">
                                    <label for="nis1" class="form-label mb-0">Siswa 1</label>
                                </div>
                                <div class="col position-relative" style="min-width: 0">
                                    <select id="nis1" class="form-select w-100 siswa-select"
                                        style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;"
                                        name="namaSiswa[]" required>
                                        <option value="">Pilih Siswa...</option>
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
                                    <select class="form-select w-100" id="perusahaan" name="perusahaan_tujuan" required>
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
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggalMulai" name="tanggal_mulai"
                                required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggalSelesai" name="tanggal_selesai"
                                required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            <div class="invalid-feedback" id="tanggalSelesaiFeedback">Tanggal selesai wajib diisi.</div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                function validateTanggal() {
                                    const mulai = document.getElementById('tanggalMulai').value;
                                    const selesai = document.getElementById('tanggalSelesai').value;
                                    const feedback = document.getElementById('tanggalSelesaiFeedback');
                                    const selesaiInput = document.getElementById('tanggalSelesai');
                                    if (mulai && selesai) {
                                        const mulaiDate = new Date(mulai);
                                        const selesaiDate = new Date(selesai);

                                        // Calculate difference in months
                                        let months = (selesaiDate.getFullYear() - mulaiDate.getFullYear()) * 12;
                                        months += selesaiDate.getMonth() - mulaiDate.getMonth();

                                        // If the end day is less than the start day, reduce a month
                                        if (selesaiDate.getDate() < mulaiDate.getDate()) {
                                            months -= 1;
                                        }

                                        if (months < 3 || months >= 4) {
                                            selesaiInput.setCustomValidity("Tanggal selesai harus lebih dari 3 bulan dan kurang dari 4 bulan dari tanggal mulai.");
                                            feedback.textContent = "Tanggal selesai harus lebih dari 3 bulan dan kurang dari 4 bulan dari tanggal mulai.";
                                            feedback.style.display = "block";
                                        } else {
                                            selesaiInput.setCustomValidity("");
                                            feedback.textContent = "Tanggal selesai wajib diisi.";
                                            feedback.style.display = "";
                                        }
                                    } else {
                                        selesaiInput.setCustomValidity("");
                                        feedback.textContent = "Tanggal selesai wajib diisi.";
                                        feedback.style.display = "";
                                    }
                                }

                                document.getElementById('tanggalMulai').addEventListener('change', validateTanggal);
                                document.getElementById('tanggalSelesai').addEventListener('change', validateTanggal);
                            });
                        </script>

                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Perusahaan -->
    <div class="modal fade" id="modalTambahPerusahaan" tabindex="-1" aria-labelledby="modalTambahPerusahaanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPerusahaanLabel">Tambah Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahPerusahaan">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="dudi_nama" class="form-label">Nama DUDI</label>
                            <input type="text" class="form-control" id="dudi_nama" name="nama" required maxlength="30">
                            <div class="invalid-feedback">Nama wajib diisi dan maksimal 30 karakter.</div>
                        </div>
                        <div class="mb-3">
                            <label for="dudi_nama_pimpinan" class="form-label">Nama Pimpinan</label>
                            <input type="text" class="form-control" id="dudi_nama_pimpinan" name="nama_pimpinan" required maxlength="50">
                            <div class="invalid-feedback">Nama wajib diisi dan maksimal 50 karakter.</div>
                        </div>
                        <div class="mb-3">
                            <label for="dudi_no_kontak" class="form-label">Nomor Kontak</label>
                            <input type="number" class="form-control" id="dudi_no_kontak" name="no_kontak" required maxlength="14">
                            <div class="invalid-feedback">Nomor kontak wajib diisi dan maksimal 14 karakter.</div>
                        </div>
                        <div class="mb-3">
                            <label for="dudi_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="dudi_alamat" name="alamat" maxlength="100" required></textarea>
                            <div class="invalid-feedback">Alamat maksimal 100 karakter.</div>
                        </div>
                        <div class="mb-3">
                            <label for="dudi_latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="dudi_latitude" name="latitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="dudi_longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="dudi_longitude" name="longitude" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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
        <div class="modal-dialog modal-lg">
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
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Initialize select2 for company selection
            $('#perusahaan').select2({
                theme: 'bootstrap-5',
                dropdownParent: $("#pengajuanModal"),
                placeholder: 'Cari Perusahaan...',
                minimumInputLength: 1,
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

            // Initialize select2 for first student
            $('#nis1').select2({
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
                            id_ta: $('#id_ta').val()
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

            // Dynamic student input functionality
            let studentCount = 1;

            $(document).on('click', '.add-siswa-btn', function() {
                if (studentCount < 4) {
                    studentCount++;
                    const newRow = `
                        <div class="row mb-3 siswa-input-row align-items-center flex-nowrap">
                            <div class="col-3 text-start">
                                <label class="form-label mb-0">Siswa ${studentCount}</label>
                            </div>
                            <div class="col position-relative" style="min-width: 0">
                                <select class="form-select w-100 siswa-select"
                                    style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;"
                                    name="namaSiswa[]" required>
                                    <option value="">Pilih Siswa...</option>
                                </select>
                            </div>
                            <div class="col-auto ps-0">
                                <button type="button"
                                    class="btn btn-danger btn-sm remove-siswa-btn d-flex align-items-center justify-content-center"
                                    title="Hapus Siswa" style="width:32px; height:38px;">
                                    <i class="bi bi-dash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    $('#siswa-input-group').append(newRow);

                    // Initialize select2 for new student input
                    const newSelect = $('#siswa-input-group .siswa-input-row:last-child .siswa-select');
                    newSelect.select2({
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
                                    k: 'penempatans',
                                    id_ta: $('#id_ta').val()
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

            $(document).on('click', '.remove-siswa-btn', function() {
                $(this).closest('.siswa-input-row').remove();
                studentCount--;
                // Update labels
                $('#siswa-input-group .siswa-input-row').each(function(index) {
                    $(this).find('label').text(`Siswa ${index + 1}`);
                });
            });

            // Add company functionality
            $('#addPerusahaanBtn').on('click', function() {
                $('#modalTambahPerusahaan').modal('show');
            });

            $('#formTambahPerusahaan').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('dudi.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#modalTambahPerusahaan').modal('hide');
                        form[0].reset();
                        btn.prop('disabled', false).text('Simpan');

                        // Refresh company select
                        $('#perusahaan').empty().append('<option value="">Pilih atau cari perusahaan...</option>');
                        $('#perusahaan').select2('destroy');
                        $('#perusahaan').select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $("#pengajuanModal"),
                            placeholder: 'Cari Perusahaan...',
                            minimumInputLength: 1,
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
                                        results: $.map(data, function(item) {
                                            return {
                                                id: item.id_dudi,
                                                text: item.nama + ' - ' + item.alamat
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        });

                        Toast.fire({
                            icon: "success",
                            title: "Perusahaan berhasil ditambahkan"
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Simpan');
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show validation errors
                            for (const [field, messages] of Object.entries(xhr.responseJSON.errors)) {
                                var input = form.find('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').show().text(messages[0]);
                            }
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Gagal menambahkan perusahaan"
                            });
                        }
                    }
                });
            });

            // Reset form when modal is closed
            $('#pengajuanModal').on('hidden.bs.modal', function() {
                $('#formPengajuan')[0].reset();
                $('#pengajuanId').val('');
                // Remove additional student rows
                $('#siswa-input-group .siswa-input-row:not(:first)').remove();
                studentCount = 1;
                // Reset select2
                $('#perusahaan').val('').trigger('change');
                $('#nis1').val('').trigger('change');
            });
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
                                <td>${siswa.nis}</td>
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
                                    <th>NIS</th>
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

            // Debug: log the form data
            console.log('Form data:', form.serialize());
            console.log('ID:', $('#guruId').val());
            console.log('Guru:', $('#guru').val());

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
                            console.log('Response:', res);
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
                                console.log(res);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: res.message || 'Gagal Menempatkan.'
                                });
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).text('Simpan');
                            console.log('Error response:', xhr.responseJSON);
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
                $('#id_ta').val(data.id_ta).trigger('change');

                // Reset student inputs
                $('#siswa-input-group .siswa-input-row:not(:first)').remove();
                studentCount = 1;

                // Set first student
                if (data.nama_siswa && data.nama_siswa.length > 0) {
                    $('#nis1').val(data.nama_siswa[0]).trigger('change');

                    // Add additional students if any
                    for (let i = 1; i < data.nama_siswa.length; i++) {
                        if (studentCount < 4) {
                            studentCount++;
                            const newRow = `
                                <div class="row mb-3 siswa-input-row align-items-center flex-nowrap">
                                    <div class="col-3 text-start">
                                        <label class="form-label mb-0">Siswa ${studentCount}</label>
                                    </div>
                                    <div class="col position-relative" style="min-width: 0">
                                        <select class="form-select w-100 siswa-select"
                                            style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;"
                                            name="namaSiswa[]" required>
                                            <option value="">Pilih Siswa...</option>
                                        </select>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <button type="button"
                                            class="btn btn-danger btn-sm remove-siswa-btn d-flex align-items-center justify-content-center"
                                            title="Hapus Siswa" style="width:32px; height:38px;">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            $('#siswa-input-group').append(newRow);

                            // Initialize select2 for new student input
                            const newSelect = $('#siswa-input-group .siswa-input-row:last-child .siswa-select');
                            newSelect.select2({
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
                                            k: 'penempatans',
                                            id_ta: $('#id_ta').val()
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

                            // Set value for this student
                            setTimeout(() => {
                                newSelect.val(data.nama_siswa[i]).trigger('change');
                            }, 100);
                        }
                    }
                }

                $('#perusahaan').val(data.perusahaan_tujuan).trigger('change');
                $('#tanggalMulai').val(data.tanggal_mulai);
                $('#tanggalSelesai').val(data.tanggal_selesai);

                $('#pengajuanModal').modal('show');
            });
        }

        // Reset Form Tambah/Edit
        function resetForm() {
            $('#pengajuanModalLabel').text("Tambah Pengajuan");
            $('#formPengajuan')[0].reset();
            $('#pengajuanId').val('');
            // Reset select2
            $('#perusahaan').val('').trigger('change');
            $('#nis1').val('').trigger('change');
            // Remove additional student rows
            $('#siswa-input-group .siswa-input-row:not(:first)').remove();
            studentCount = 1;
        }
    </script>
@endsection

@section('css')
    <link href="{{ asset('assets') }}/vendor/dataTables/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <style>
        .siswa-input-row {
            transition: all 0.3s ease;
        }

        .siswa-input-row:hover {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 5px;
        }

        .add-siswa-btn, .remove-siswa-btn {
            transition: all 0.2s ease;
        }

        .add-siswa-btn:hover {
            transform: scale(1.1);
        }

        .remove-siswa-btn:hover {
            transform: scale(1.1);
        }

        .form-select.siswa-select {
            min-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }

        .modal-dialog {
            max-width: 600px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
        }
    </style>
@endsection
