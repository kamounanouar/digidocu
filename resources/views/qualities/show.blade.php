@extends('layouts.app')

@section('content')

    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2>Quality Log Details</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $quality->date->format('F j, Y') }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Created:</strong> {{ $quality->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if($quality->comment)
            <div class="alert alert-info">
                <strong>General Comment:</strong>
                <p class="mb-0">{{ $quality->comment }}</p>
            </div>
            @endif
        </div>
    </div>

    @foreach($groupedLogs as $category => $logs)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h3 class="h3 b-0"><strong><u>{{ $loop->iteration }}.{{ $category }}</u></strong></h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="col-xs-1">#</th>
                        <th width="20%">Check list</th>
                        <th width="10%">Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->qualityT2->libel }}</td>
                        <td>
                            <span class="{{ $statusBadges[$log->status] }}">
                                {{ $log->status ? 'Oui' : 'Non' }}
                            </span>
                        </td>
                        <td>{{ $log->comment ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('qualities.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <div>
            <a href="{{ route('qualities.edit', $quality->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection