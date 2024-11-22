@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-start mb-3">
                <h1 class="text-center">Laporan ID: {{ $report->id }} </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Laporan: {{ $report->title }}</p>
                </strong>
            </div>
            <div class="col-md-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Jenis Laporan: {{ $report->type }}</p>
                </strong>
            </div>
            <div class="col-md-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Deskripsi Laporan: {{ $report->description }}</p>
                </strong>
            </div>
            <div class="col-md-6 d-flex justify-content-start mb-3">
                <strong>
                    <p class="text-center">Status Laporan: {{ $report->status }}</p>
                </strong>
            </div>
            <div class="col-md-12 d-flex justify-content-center mb-3">
                <strong>
                    <p class="text-center">Foto Laporan:</p>
                </strong>
            </div>
            <div class="col-md-12 d-flex justify-content-center mb-3">
                <img src="{{ route('reports.photo.show', $report->id) }}" alt="Foto Laporan"
                    class="img-fluid img-thumbnail vw-25">
            </div>
        </div>
    </div>
@endsection
