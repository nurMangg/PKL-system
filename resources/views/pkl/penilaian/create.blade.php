@extends('layouts.main')
@section('title')
    Buat Penilaian PKL
@endsection
@section('pagetitle')
<div class="pagetitle">
    <h1>Buat Penilaian PKL</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">PKL</li>
            <li class="breadcrumb-item"><a href="{{ route('penilaian.index') }}">Penilaian</a></li>
            <li class="breadcrumb-item active">Buat Penilaian</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Siswa</h5>
        <div class="row mb-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">NIS</th>
                        <td>{{ $siswa->nis }}</td>
                    </tr>
                    <tr>
                        <th>Nama Siswa</th>
                        <td>{{ $siswa->nama }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Jurusan</th>
                        <td>{{ $siswa->jurusan->jurusan ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <form action="{{ route('penilaian.store') }}" method="POST" id="penilaianForm">
            @csrf
            <input type="hidden" name="id_siswa" value="{{ $siswa->nis }}">

            <h5 class="card-title">Form Penilaian</h5>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Petunjuk Penilaian:</strong><br>
                • Masukkan nilai 0-100 untuk setiap indikator penilaian
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="60%">Indikator Penilaian</th>
                            <th width="20%">Nilai (0-100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($templates as $template)
                            @foreach ($template->mainItems as $mainItem)
                                {{-- Level 1 - Main Indicator --}}
                                <tr>
                                    <td><strong>{{ $no++ }}</strong></td>
                                    <td>
                                        <strong>{{ $mainItem->indikator }}</strong>
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="nilai[{{ $mainItem->id }}]"
                                            class="form-control main-indicator-value"
                                            readonly
                                            hidden
                                            data-main-id="{{ $mainItem->id }}"
                                            placeholder="Dihitung otomatis">
                                    </td>
                                </tr>

                                {{-- Level 2 - Sub Indicators --}}
                                @if ($mainItem->children->isNotEmpty())
                                    @foreach ($mainItem->children as $subItem)
                                        <tr>
                                            <td></td>
                                            <td class="ps-3">
                                                {{ $subItem->indikator }}
                                            </td>
                                            <td>
                                                @if ($subItem->level3Children->isNotEmpty())
                                                    {{-- Has Level 3, so this is calculated --}}
                                                    <input type="number"
                                                        class="form-control sub-indicator-value"
                                                        readonly
                                                        hidden
                                                        data-sub-id="{{ $subItem->id }}"
                                                        data-main-id="{{ $mainItem->id }}"
                                                        placeholder="Dihitung dari Level 3">
                                                @else
                                                    {{-- No Level 3, so this is assessed directly --}}
                                                    <input type="number"
                                                        class="form-control sub-direct-assessment"
                                                        name="nilai-sub[{{ $subItem->id }}]"
                                                        min="0"
                                                        max="100"
                                                        step="1"
                                                        data-sub-id="{{ $subItem->id }}"
                                                        data-main-id="{{ $mainItem->id }}"
                                                        placeholder="Masukkan nilai 0-100"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Level 3 - Sub-Sub Indicators --}}
                                        @if ($subItem->level3Children->isNotEmpty())
                                            @foreach ($subItem->level3Children as $subSubItem)
                                                <tr>
                                                    <td></td>
                                                    <td class="ps-5">
                                                        {{ $subSubItem->indikator }}
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            class="form-control level3-assessment"
                                                            name="nilai-sub[{{ $subSubItem->id }}]"
                                                            min="0"
                                                            max="100"
                                                            step="1"
                                                            data-subsub-id="{{ $subSubItem->id }}"
                                                            data-sub-id="{{ $subItem->id }}"
                                                            data-main-id="{{ $mainItem->id }}"
                                                            placeholder="Masukkan nilai 0-100"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mb-3 mt-4">
                <div class="col-md-12">
                    <label for="catatan" class="form-label">Catatan Penilaian</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="4"
                              placeholder="Masukkan catatan atau komentar tambahan untuk penilaian ini..."></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-end">
                    <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Simpan Penilaian</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Function to update keterangan based on value
    function updateKeterangan(elementId, value) {
        const ketElement = $('#' + elementId);
        if (value >= 80) {
            ketElement.removeClass('bg-secondary bg-danger').addClass('bg-success').text('Tercapai');
        } else if (value >= 0) {
            ketElement.removeClass('bg-secondary bg-success').addClass('bg-danger').text('Tidak Tercapai');
        } else {
            ketElement.removeClass('bg-success bg-danger').addClass('bg-secondary').text('Menunggu');
        }
    }

    // Function to calculate Level 2 from Level 3
    function calculateLevel2(subId) {
        let total = 0;
        let count = 0;

        $(`input[data-sub-id="${subId}"].level3-assessment`).each(function() {
            const val = $(this).val();
            if (val !== '') {
                total += parseInt(val);
                count++;
            }
        });

        // Check if all level 3 items for this sub are assessed
        const totalLevel3Items = $(`input[data-sub-id="${subId}"].level3-assessment`).length;

        if (count === totalLevel3Items && count > 0) {
            // All level 3 items assessed
            const level2Value = Math.round(total / count);
            $(`input[data-sub-id="${subId}"].sub-indicator-value`).val(level2Value);
            updateKeterangan(`ket-sub-${subId}`, level2Value);

            // Calculate main indicator
            const mainId = $(`input[data-sub-id="${subId}"]`).first().data('main-id');
            calculateMainIndicator(mainId);
        } else {
            // Not all assessed yet
            $(`input[data-sub-id="${subId}"].sub-indicator-value`).val('');
            updateKeterangan(`ket-sub-${subId}`, null);
        }
    }

    // Function to calculate Main Indicator from Sub Indicators
    function calculateMainIndicator(mainId) {
        let total = 0;
        let count = 0;

        // Check direct sub assessments
        $(`input[data-main-id="${mainId}"].sub-direct-assessment`).each(function() {
            const val = $(this).val();
            if (val !== '') {
                total += parseInt(val);
                count++;
            }
        });

        // Check calculated sub values
        $(`input[data-main-id="${mainId}"].sub-indicator-value`).each(function() {
            const val = $(this).val();
            if (val !== '') {
                total += parseInt(val);
                count++;
            }
        });

        // Check total sub indicators for this main
        const totalSubItems = $(`input[data-main-id="${mainId}"].sub-direct-assessment`).length +
                             $(`input[data-main-id="${mainId}"].sub-indicator-value`).length;

        if (count === totalSubItems && count > 0) {
            // All sub indicators assessed
            const percentage = Math.round(total / count);
            $(`input[data-main-id="${mainId}"].main-indicator-value`).val(percentage);

            if (percentage >= 80) {
                updateKeterangan(`ket-main-${mainId}`, 1);
            } else {
                updateKeterangan(`ket-main-${mainId}`, 0);
            }
        } else {
            $(`input[data-main-id="${mainId}"].main-indicator-value`).val('');
            updateKeterangan(`ket-main-${mainId}`, null);
        }

        checkFormCompletion();
    }

    // Function to check if form is complete
    function checkFormCompletion() {
        let allAssessed = true;

        // Check if all assessable items are completed
        $('.level3-assessment, .sub-direct-assessment').each(function() {
            const val = $(this).val();
            if (val === '' || val === null || val === undefined) {
                allAssessed = false;
                return false;
            }
        });

        $('#submitBtn').prop('disabled', !allAssessed);
    }

    // Event handlers
    $('.level3-assessment').on('input', function() {
        const subSubId = $(this).data('subsub-id');
        const subId = $(this).data('sub-id');
        const value = $(this).val();

        // Validate input range
        if (value !== '' && (value < 0 || value > 100)) {
            alert('Nilai harus antara 0-100');
            $(this).val('');
            return;
        }

        updateKeterangan(`ket-subsub-${subSubId}`, value);
        calculateLevel2(subId);
    });

    $('.sub-direct-assessment').on('input', function() {
        const subId = $(this).data('sub-id');
        const mainId = $(this).data('main-id');
        const value = $(this).val();

        // Validate input range
        if (value !== '' && (value < 0 || value > 100)) {
            alert('Nilai harus antara 0-100');
            $(this).val('');
            return;
        }

        updateKeterangan(`ket-sub-${subId}`, value);
        calculateMainIndicator(mainId);
    });

    // Form validation before submission
    $('#penilaianForm').on('submit', function(e) {
        e.preventDefault();

        // Validate all numeric inputs
        let isValid = true;
        $('.level3-assessment, .sub-direct-assessment').each(function() {
            const value = $(this).val();
            if (value !== '' && (value < 0 || value > 100)) {
                alert('Semua nilai harus antara 0-100');
                $(this).focus();
                isValid = false;
                return false;
            }
        });

        if (!isValid) {
            return false;
        }

        // Check if form is complete
        let allAssessed = true;
        $('.level3-assessment, .sub-direct-assessment').each(function() {
            const val = $(this).val();
            if (val === '' || val === null || val === undefined) {
                allAssessed = false;
                return false;
            }
        });

        if (!allAssessed) {
            alert('Semua indikator penilaian harus diisi');
            return false;
        }

        $('#submitBtn').prop('disabled', !allAssessed);

        // Submit form if valid
        this.submit();
    });

    // Initial form completion check
    checkFormCompletion();
});
</script>
@endsection
