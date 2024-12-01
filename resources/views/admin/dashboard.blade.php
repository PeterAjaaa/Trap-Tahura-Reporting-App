@extends('layouts.app')

@section('content')
    <meta name="user-id" content="{{ Auth::id() }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Dashboard Laporan</h3>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12 text-center">
                                <h1>Selamat Datang</h1>
                                <h6>Pengguna Saat Ini: {{ Auth::user()->name }}</h6>
                            </div>
                        </div>

                        <table id="reports-table" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID Laporan</th>
                                    <th>Judul Laporan</th>
                                    <th>Tipe Laporan</th>
                                    <th>Prioritas Laporan</th>
                                    <th>Deskripsi Laporan</th>
                                    <th>Status Laporan</th>
                                    <th>Aksi Laporan</th>
                                    <th>Foto Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Active Reports Section --}}
                                @foreach ($activeReports as $report)
                                    <tr data-id="{{ $report->id }}"
                                        class="{{ $report->priority == 5 ? 'table-danger' : '' }}">
                                        <td>{{ $report->id }}</td>
                                        <td class="title-cell">{{ $report->title }}</td>
                                        <td class="type-cell">{{ $report->type }}</td>
                                        <td class="priority-cell">
                                            <span
                                                class="badge bg-{{ $report->priority == 5 ? 'danger' : ($report->priority >= 3 ? 'warning' : 'success') }}">
                                                {{ $report->priority }}
                                            </span>
                                        </td>
                                        <td class="description-cell">{{ $report->description }}</td>
                                        <td class="status-cell">{{ $report->status }}</td>
                                        <td>
                                            <form action="{{ route('reports.update.status', $report->id) }}" method="POST"
                                                class="d-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm me-2">
                                                    @php
                                                        $statuses = [
                                                            'Pending',
                                                            'Assigned',
                                                            'In Progress',
                                                            'Resolved',
                                                            'Closed',
                                                        ];
                                                    @endphp
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status }}"
                                                            {{ $report->status === $status ? 'selected' : '' }}>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                                            </form>
                                        </td>
                                        <td class="photo-cell">
                                            @if ($report->photo)
                                                <img src="{{ route('reports.photo.show', $report->id) }}"
                                                    alt="Foto Laporan" class="img-fluid img-thumbnail"
                                                    style="max-width: 100px;">
                                            @else
                                                Foto Laporan Tidak Tersedia
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Separator Row --}}
                                @if ($closedReports->count() > 0)
                                    <tr>
                                        <td colspan="8" class="text-center bg-secondary text-white">
                                            Laporan Ditutup
                                        </td>
                                    </tr>
                                @endif

                                {{-- Closed Reports Section --}}
                                @foreach ($closedReports as $report)
                                    <tr data-id="{{ $report->id }}" class="text-muted bg-light">
                                        <td>{{ $report->id }}</td>
                                        <td class="title-cell">{{ $report->title }}</td>
                                        <td class="type-cell">{{ $report->type }}</td>
                                        <td class="priority-cell">
                                            <span class="badge bg-secondary">
                                                {{ $report->priority }}
                                            </span>
                                        </td>
                                        <td class="description-cell">{{ $report->description }}</td>
                                        <td class="status-cell">{{ $report->status }}</td>
                                        <td>
                                            <form class="d-flex align-items-center" disabled>
                                                <select class="form-select form-select-sm me-2" disabled>
                                                    <option selected>{{ $report->status }}</option>
                                                </select>
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    Ditutup
                                                </button>
                                            </form>
                                        </td>
                                        <td class="photo-cell">
                                            @if ($report->photo)
                                                <img src="{{ route('reports.photo.show', $report->id) }}"
                                                    alt="Foto Laporan" class="img-fluid img-thumbnail"
                                                    style="max-width: 100px;">
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
        </div>
    </div>
@endsection
