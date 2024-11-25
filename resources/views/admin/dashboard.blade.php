@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex justify-content-center mb-3">
                <h1> Selamat Datang</h1>
            </div>
            <div class="col-md-8 d-flex justify-content-center mb-3">
                <h6>Current User: {{ Auth::user()->name }}</h6>
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
                            <th scope="col" class="text-center">Aksi Laporan</th>
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
                                    <form action="{{ route('reports.update.status', $report->id) }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm me-2"
                                            aria-label="Change Report Status">
                                            <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="Assigned" {{ $report->status == 'Assigned' ? 'selected' : '' }}>
                                                Assigned
                                            </option>
                                            <option value="In Progress"
                                                {{ $report->status == 'In Progress' ? 'selected' : '' }}>
                                                In Progress</option>
                                            <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>
                                                Resolved</option>
                                            <option value="Closed" {{ $report->status == 'Closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
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
