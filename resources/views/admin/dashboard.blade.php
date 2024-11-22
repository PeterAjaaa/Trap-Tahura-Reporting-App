@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex justify-content-center mb-3">
                <h1> Selamat Datang</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col d-flex justify-content-center mb-3">
                <table class="table table-striped table-hover table-bordered table-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">ID Laporan</th>
                            <th scope="col" class="text-center">Judul Laporan</th>
                            <th scope="col" class="text-center">Tipe Laporan</th>
                            <th scope="col" class="text-center">Prioritas Laporan</th>
                            <th scope="col" class="text-center">Deskripsi Laporan</th>
                            <th scope="col" class="text-center">Status Laporan</th>
                            <th scope="col" class="text-center">Foto Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr class="{{ $report->priority == 5 ? 'table-danger' : '' }}">
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->title }}</td>
                                <td>{{ $report->type }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $report->priority == 5 ? 'danger' : ($report->priority >= 3 ? 'warning' : 'success') }}">
                                        {{ $report->priority }}
                                    </span>
                                </td>
                                <td>{{ $report->description }}</td>
                                <td>{{ $report->status }}</td>
                                <td>
                                    @if ($report->photo)
                                        <img src="{{ route('reports.photo.show', $report->id) }}" alt="Foto Laporan"
                                            class="img-fluid img-thumbnail vw-25">
                                    @else
                                        Foto Laporan Tidak Tersedia
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
