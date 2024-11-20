@extends('layouts.app')

@section('content')
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
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($reports as $report)
                            <tr>
                                <th scope="row">{{ $report->id }}</th>
                                <td>{{ $report->status }}</td>
                                <td>
                                    <a href="{{ route('reports.show', $report->id) }}" class="btn btn-primary">Lihat</a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
