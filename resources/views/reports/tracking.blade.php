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
                <h1 class="text-center">Lacak Laporan</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center text-success">ID</th>
                            <th class="text-center text-success">Status</th>
                            <th class="text-center text-success">Bukti Foto</th>
                            <th class="text-center text-success">Pesan Laporan Oleh Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="text-center">{{ $report->id }}</td>
                                <td class="text-center">{{ $report->status }}</td>
                                <td class="text-center">
                                    <img src="{{ route('reports.photo.show', ['id' => $report->id]) }}"
                                        class="img-thumbnail w-25" alt="Report Photo">
                                </td>
                                <td>
                                    PLACEHOLDER
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
