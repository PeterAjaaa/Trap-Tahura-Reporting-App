@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-start mb-3">
                <h1 class="text-center">Buat Laporan Baru</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Nama Laporan (Wajib)</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Laporan (Wajib)</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Perbaikan">Perbaikan dan Pemeliharaan</option>
                            <option value="Komplain">Komplain dan Pengaduan</option>
                            <option value="Ketidaksesuaian">Ketidaksesuaian Prosedur</option>
                            <option value="Bahaya">Kebahayaan (Lokasi, Lingkungan, Atau Kerusakan)</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Laporan (Wajib)</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Upload Foto Laporan (Wajib)</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <div class="d-grid col-6 mx-auto">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
