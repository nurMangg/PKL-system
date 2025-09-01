@extends('layouts.main')

@section('title', 'Master Kepala Sekolah')

@section('styles')
<style>
.signature-canvas {
    border: 2px solid #ccc;
    background: white;
    cursor: crosshair;
    touch-action: none;
    width: 600px;
    height: 300px;
    display: block;
    border-radius: 8px;
}

.signature-container {
    position: relative;
    display: inline-block;
    margin: 10px 0;
}

.signature-container canvas {
    display: block;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.signature-container {
    position: relative;
    display: inline-block;
}

.signature-container canvas {
    display: block;
}
</style>
@endsection

@section('content')
<div class="pagetitle">
    <h1>Master Kepala Sekolah</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Kepala Sekolah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Kepala Sekolah</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-circle"></i> Tambah Kepala Sekolah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableKepalaSekolah">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIY</th>
                                    <th>Jabatan</th>
                                    <th>Tanda Tangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Kepala Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambah">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIY <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nip" name="nip" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="signature_pad" class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                        <div class="border rounded p-3">
                            <p class="text-muted small mb-2">Gunakan mouse atau jari untuk menggambar tanda tangan di area putih di bawah ini:</p>
                            <div class="signature-container">
                                <canvas id="signatureCanvas" class="signature-canvas"></canvas>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="clearSignature">Hapus Tanda Tangan</button>
                            </div>
                        </div>
                        <input type="hidden" id="signature_pad" name="signature_pad" required>
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Kepala Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nip" class="form-label">NIY <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nip" name="nip" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_signature_pad" class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                        <div class="border rounded p-3">
                            <p class="text-muted small mb-2">Gunakan mouse atau jari untuk menggambar tanda tangan di area putih di bawah ini:</p>
                            <div class="signature-container">
                                <canvas id="editSignatureCanvas" class="signature-canvas"></canvas>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="clearEditSignature">Hapus Tanda Tangan</button>
                            </div>
                        </div>
                        <input type="hidden" id="edit_signature_pad" name="signature_pad" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let signaturePad, editSignaturePad;
let table;

$(document).ready(function() {
        // Initialize signature pads with fallback
    async function initializeSignaturePads() {
        // Resize canvas to fit container
        function resizeCanvas(canvas) {
            canvas.width = 600;
            canvas.height = 300;
            canvas.style.width = '600px';
            canvas.style.height = '300px';
        }

        // Load SignaturePad library
        const SignaturePadLib = await loadSignaturePad();
        console.log('SignaturePad library loaded:', SignaturePadLib ? 'Yes' : 'No, using fallback');

        // Initialize Signature Pad for Add
        const canvas = document.getElementById('signatureCanvas');
        resizeCanvas(canvas);

        if (SignaturePadLib) {
            signaturePad = new SignaturePadLib(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 2.5
            });
        } else {
            signaturePad = SimpleSignaturePad(canvas);
        }

        // Initialize Signature Pad for Edit
        const editCanvas = document.getElementById('editSignatureCanvas');
        resizeCanvas(editCanvas);

        if (SignaturePadLib) {
            editSignaturePad = new SignaturePadLib(editCanvas, {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 2.5
            });
        } else {
            editSignaturePad = SimpleSignaturePad(editCanvas);
        }

        // Clear signature buttons
        $('#clearSignature').click(function() {
            signaturePad.clear();
            $('#signature_pad').val('');
        });

        $('#clearEditSignature').click(function() {
            editSignaturePad.clear();
            $('#edit_signature_pad').val('');
        });

        // Reinitialize signature pad when modal is shown
        $('#modalTambah').on('shown.bs.modal', function() {
            setTimeout(function() {
                resizeCanvas(canvas);
                signaturePad.clear();
            }, 100);
        });

        $('#modalEdit').on('shown.bs.modal', function() {
            setTimeout(function() {
                resizeCanvas(editCanvas);
                editSignaturePad.clear();
            }, 100);
        });
    }

    // Initialize signature pads
    initializeSignaturePads().then(() => {
        console.log('Signature pads initialized successfully');
    }).catch(error => {
        console.error('Error initializing signature pads:', error);
    });

    // Debug: Check if SignaturePad is loaded
    console.log('SignaturePad loaded:', typeof SignaturePad !== 'undefined');
    console.log('SimpleSignaturePad loaded:', typeof SimpleSignaturePad !== 'undefined');

    // Debug: Check CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token available:', csrfToken ? 'Yes' : 'No');
    console.log('CSRF Token:', csrfToken);

    // Test canvas
    setTimeout(function() {
        const canvas = document.getElementById('signatureCanvas');
        if (canvas) {
            console.log('Canvas dimensions:', canvas.width, 'x', canvas.height);
            console.log('Canvas offset dimensions:', canvas.offsetWidth, 'x', canvas.offsetHeight);
            console.log('Canvas context:', canvas.getContext('2d') ? 'Available' : 'Not available');

            // Test drawing
            const ctx = canvas.getContext('2d');
            if (ctx) {
                ctx.strokeStyle = '#ff0000';
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.moveTo(50, 50);
                ctx.lineTo(100, 100);
                ctx.stroke();
                console.log('Test drawing completed');
            }
        }
    }, 1000);

    // Initialize DataTable
    table = $('#tableKepalaSekolah').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("master.kepala-sekolah.data") }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.log('DataTable error:', error);
                console.log('DataTable response:', xhr.responseText);
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {data: 'nama', name: 'nama'},
            {data: 'nip', name: 'nip'},
            {data: 'jabatan', name: 'jabatan'},
            {
                data: 'signature_pad',
                name: 'signature_pad',
                render: function(data) {
                    return data ? '<img src="' + data + '" style="max-width: 100px; max-height: 50px;" />' : 'Tidak ada tanda tangan';
                }
            },
            {
                data: 'id_kepala_sekolah',
                name: 'id_kepala_sekolah',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${data}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        order: [[1, 'asc']]
    });

    // Handle form submission for Add
    $('#formTambah').submit(function(e) {
        e.preventDefault();

        if (signaturePad.isEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Tanda tangan harus diisi!'
            });
            return;
        }

        const signatureData = signaturePad.toDataURL();
        $('#signature_pad').val(signatureData);

        // Debug: Log form data
        const formData = $(this).serialize();
        console.log('Form data being sent:', formData);
        console.log('Signature data length:', signatureData.length);

        // Validate form data
        const nama = $('#nama').val();
        const nip = $('#nip').val();
        const jabatan = $('#jabatan').val();

        console.log('Form validation:');
        console.log('- Nama:', nama);
        console.log('- NIY:', nip);
        console.log('- Jabatan:', jabatan);
        console.log('- Signature:', signatureData ? 'Available' : 'Missing');

        if (!nama || !nip || !jabatan || !signatureData) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Semua field harus diisi!'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("master.kepala-sekolah.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    $('#modalTambah').modal('hide');
                    $('#formTambah')[0].reset();
                    signaturePad.clear();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Error response:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);

                let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Handle form submission for Edit
    $('#formEdit').submit(function(e) {
        e.preventDefault();

        if (editSignaturePad.isEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Tanda tangan harus diisi!'
            });
            return;
        }

        const signatureData = editSignaturePad.toDataURL();
        $('#edit_signature_pad').val(signatureData);

        const id = $('#edit_id').val();

        // Debug: Log form data
        const formData = $(this).serialize();
        console.log('Edit form data being sent:', formData);
        console.log('Edit signature data length:', signatureData.length);

        // Show loading
        Swal.fire({
            title: 'Mengupdate...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/master/kepala-sekolah/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Update success response:', response);
                if (response.success) {
                    $('#modalEdit').modal('hide');
                    $('#formEdit')[0].reset();
                    editSignaturePad.clear();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Terjadi kesalahan saat mengupdate data'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Update error response:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);

                let errorMessage = 'Terjadi kesalahan saat mengupdate data';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');

        $.ajax({
            url: `/master/kepala-sekolah/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#edit_id').val(data.id_kepala_sekolah);
                    $('#edit_nama').val(data.nama);
                    $('#edit_nip').val(data.nip);
                    $('#edit_jabatan').val(data.jabatan);

                    if (data.signature_pad) {
                        const img = new Image();
                        img.onload = function() {
                            editSignaturePad.clear();
                            editSignaturePad.fromDataURL(data.signature_pad);
                        };
                        img.src = data.signature_pad;
                    } else {
                        editSignaturePad.clear();
                    }

                    $('#modalEdit').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengambil data'
                });
            }
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/master/kepala-sekolah/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    });
});
</script>

<!-- Signature Pad Library -->
<script>
// Load SignaturePad library dynamically
function loadSignaturePad() {
    return new Promise((resolve, reject) => {
        if (window.SignaturePad) {
            resolve(window.SignaturePad);
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
        script.onload = () => {
            console.log('SignaturePad loaded from CDN 1');
            resolve(window.SignaturePad);
        };
        script.onerror = () => {
            console.log('CDN 1 failed, trying alternative...');
            const script2 = document.createElement('script');
            script2.src = 'https://unpkg.com/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
            script2.onload = () => {
                console.log('SignaturePad loaded from CDN 2');
                resolve(window.SignaturePad);
            };
            script2.onerror = () => {
                console.log('CDN 2 failed, trying jsdelivr...');
                const script3 = document.createElement('script');
                script3.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
                script3.onload = () => {
                    console.log('SignaturePad loaded from CDN 3');
                    resolve(window.SignaturePad);
                };
                script3.onerror = () => {
                    console.log('All CDNs failed, using fallback...');
                    resolve(null);
                };
                document.head.appendChild(script3);
            };
            document.head.appendChild(script2);
        };
        document.head.appendChild(script);
    });
}

// Simple signature pad implementation if library fails
window.SimpleSignaturePad = function(canvas) {
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    const ctx = canvas.getContext('2d');

    // Set canvas size
    canvas.width = 600;
    canvas.height = 300;

    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    function getTouchPos(e) {
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        return {
            x: (touch.clientX - rect.left) * scaleX,
            y: (touch.clientY - rect.top) * scaleY
        };
    }

    function startDrawing(pos) {
        isDrawing = true;
        lastX = pos.x;
        lastY = pos.y;
    }

    function draw(pos) {
        if (!isDrawing) return;

        console.log('Drawing from', lastX, lastY, 'to', pos.x, pos.y);

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();

        lastX = pos.x;
        lastY = pos.y;
    }

    function stopDrawing() {
        isDrawing = false;
    }

    // Mouse events
    canvas.addEventListener('mousedown', (e) => {
        console.log('Mouse down event');
        startDrawing(getMousePos(e));
    });

    canvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            draw(getMousePos(e));
        }
    });

    canvas.addEventListener('mouseup', () => {
        console.log('Mouse up event');
        stopDrawing();
    });
    canvas.addEventListener('mouseout', () => {
        console.log('Mouse out event');
        stopDrawing();
    });

    // Touch events for mobile
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        console.log('Touch start event');
        startDrawing(getTouchPos(e));
    });

    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (isDrawing) {
            draw(getTouchPos(e));
        }
    });

    canvas.addEventListener('touchend', (e) => {
        e.preventDefault();
        console.log('Touch end event');
        stopDrawing();
    });

    return {
        clear: () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        },
        isEmpty: () => {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            for (let i = 3; i < data.length; i += 4) {
                if (data[i] > 0) return false; // Check alpha channel
            }
            return true;
        },
        toDataURL: () => canvas.toDataURL('image/png'),
        fromDataURL: (dataURL) => {
            const img = new Image();
            img.onload = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);
            };
            img.src = dataURL;
        }
    };
};
</script>
@endsection
