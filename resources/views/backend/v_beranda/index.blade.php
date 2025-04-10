@extends('backend.v_layouts.app')
@section('content')
<!-- contentAwal -->

<!-- <h3> {{$judul}}</h3>
<p>
    Selamat Datang, <b>{{ Auth::user()->nama }}</b> pada aplikasi Toko Online dengan hak akses yang anda miliki sebagai
    <b>
        @if (Auth::user()->role ==1)
        Super Admin
        @elseif(Auth::user()->role ==0)
        Admin
        @endif
    </b>
    ini adalah halaman utama dari aplikasi ini.
</p> -->

<h3> {{$judul}} </h3>
<p>
    <!-- Selamat Datang, <b>Nama_User</b> pada aplikasi Toko Online dengan hak akses yang anda miliki sebagai <b>Role_User</b> ini adalah halaman utama dari aplikasi ini.

-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> {{$judul}} </h5>

                Selamat Datang, <b>{{ Auth::user()->nama }}</b> pada aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                <b>
                    @if (Auth::user()->role ==1)
                    Super Admin
                    @elseif(Auth::user()->role ==0)
                    Admin
                    @endif
                </b>
                ini adalah halaman utama dari aplikasi ini.
            </div>
        </div>
    </div>
</div>

<!-- contentAkhir -->
@endsection