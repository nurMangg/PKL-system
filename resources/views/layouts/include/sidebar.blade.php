<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        {{-- PKL Dropdown --}}
        @php
            $pklActive = request()->routeIs('penempatan') ||
                         request()->routeIs('presensi') ||
                         request()->routeIs('kendala-saran') ||
                         request()->routeIs('nilai-quesioner') ||
                         request()->routeIs('pengajuan.surat.index') ||
                         request()->routeIs('penilaian.index') ||
                         request()->routeIs('status-pkl.index');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $pklActive ? '' : 'collapsed' }}" data-bs-target="#data-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-tools"></i><span>PKL</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="data-nav" class="nav-content collapse{{ $pklActive ? ' show' : '' }}" data-bs-parent="#sidebar-nav" style="">
                <li>
                    <a class="nav-link {{ request()->routeIs('penempatan') ? 'active' : 'collapsed' }}" href="{{ route('penempatan') }}">
                        <i class="bi bi-circle"></i><span>Penempatan</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('presensi') ? 'active' : 'collapsed' }}" href="{{ route('presensi') }}">
                        <i class="bi bi-circle"></i> <span>Presensi</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('kendala-saran') ? 'active' : 'collapsed' }}" href="{{ route('kendala-saran') }}">
                        <i class="bi bi-circle"></i> <span>Kendala & Saran</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('nilai-quesioner') ? 'active' : 'collapsed' }}" href="{{ route('nilai-quesioner') }}">
                        <i class="bi bi-circle"></i> <span>Kuesioner</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('pengajuan.surat.index') ? 'active' : 'collapsed' }}" href="{{ route('pengajuan.surat.index') }}">
                        <i class="bi bi-circle"></i> <span>Pengajuan Surat</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('penilaian.index') ? 'active' : 'collapsed' }}" href="{{ route('penilaian.index') }}">
                        <i class="bi bi-circle"></i> <span>Penilaian</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('status-pkl.index') ? 'active' : 'collapsed' }}" href="{{ route('status-pkl.index') }}">
                        <i class="bi bi-circle"></i> <span>Status PKL</span>
                    </a>
                </li>
            </ul>
        </li><!-- End PKL Nav -->

        {{-- Siswa --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa') ? 'active' : 'collapsed' }}" href="{{ route('siswa') }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Siswa</span>
            </a>
        </li><!-- End Siswa Nav -->

        {{-- Guru --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('guru') ? 'active' : 'collapsed' }}" href="{{ route('guru') }}">
                <i class="bi bi-person-video3"></i>
                <span>Guru</span>
            </a>
        </li><!-- End Guru Nav -->

        {{-- DUDI Dropdown --}}
        @php
            $dudiActive = request()->routeIs('ketersediaan') ||
                          request()->routeIs('dudi') ||
                          request()->routeIs('instruktur');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $dudiActive ? '' : 'collapsed' }}" data-bs-target="#dudi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-globe"></i><span>DUDI</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="dudi-nav" class="nav-content collapse{{ $dudiActive ? ' show' : '' }}" data-bs-parent="#sidebar-nav" style="">
                <li>
                    <a class="nav-link {{ request()->routeIs('ketersediaan') ? 'active' : 'collapsed' }}" href="{{ route('ketersediaan') }}">
                        <i class="bi bi-circle"></i><span>Ketersediaan</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('dudi') ? 'active' : 'collapsed' }}" href="{{ route('dudi') }}">
                        <i class="bi bi-circle"></i><span>Dudi</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('instruktur') ? 'active' : 'collapsed' }}" href="{{ route('instruktur') }}">
                        <i class="bi bi-circle"></i> <span>Instruktur</span>
                    </a>
                </li>
            </ul>
        </li><!-- End DUDI Nav -->

        {{-- Master Dropdown (role 1 only) --}}
        @if (auth()->user()->role == 1)
            @php
                $masterActive = request()->routeIs('master.jurusan') ||
                                request()->routeIs('master.tahun_akademik') ||
                                request()->routeIs('master.quesioner') ||
                                request()->routeIs('template-penilaian.index') ||
                                request()->routeIs('master.kepala-sekolah');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $masterActive ? '' : 'collapsed' }}" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content collapse{{ $masterActive ? ' show' : '' }}" data-bs-parent="#sidebar-nav" style="">
                    <li>
                        <a class="nav-link {{ request()->routeIs('master.jurusan') ? 'active' : 'collapsed' }}" href="{{ route('master.jurusan') }}">
                            <i class="bi bi-circle"></i><span>Jurusan</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('master.tahun_akademik') ? 'active' : 'collapsed' }}" href="{{ route('master.tahun_akademik') }}">
                            <i class="bi bi-circle"></i><span>Tahun Akademik</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('master.quesioner') ? 'active' : 'collapsed' }}" href="{{ route('master.quesioner') }}">
                            <i class="bi bi-circle"></i> <span>Quisioner</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('template-penilaian.index') ? 'active' : 'collapsed' }}" href="{{ route('template-penilaian.index') }}">
                            <i class="bi bi-circle"></i> <span>Template Penilaian</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('master.kepala-sekolah') ? 'active' : 'collapsed' }}" href="{{ route('master.kepala-sekolah') }}">
                            <i class="bi bi-circle"></i> <span>Kepala Sekolah</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Master Nav -->
        @endif
    </ul>

</aside><!-- End Sidebar-->
