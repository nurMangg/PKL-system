@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@section('pagetitle')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('content')

<div class="row">
@if(auth()->user() && auth()->user()->role == 2)
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Profil</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ auth()->user()->guru->id_guru }}</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;">Nama</th>
                                <td>{{ auth()->user()->guru->nama }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>Admin {{ auth()->user()->guru->jurusan->jurusan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
<div id="ida3f070c2edcbb" a='{"t":"r","v":"1.2","lang":"id","locs":[1656],"ssot":"c","sics":"ds","cbkg":"rgb(69,90,100)","cfnt":"#FFFFFF","codd":"rgb(84,110,122)","cont":"#E0E0E0"}'>Sumber Data Cuaca: <a href="https://cuacalab.id/cuaca_tegal/2_minggu/">cuaca Tegal 15 hari</a></div><script async src="https://static1.cuacalab.id/widgetjs/?id=ida3f070c2edcbb"></script>


{{-- <div class="card">
    <div class="card-header">
        <h5 class="card-title" style="padding:unset;">Target Capaian  <span>| 2020</span></h5>
    </div>
    <div class="card-body">
    </div>
</div> --}}
@endsection
@section('js')

@endsection
