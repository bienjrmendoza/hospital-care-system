@extends('layouts.app')

@section('content')
<h3>My Generated Reports</h3>

@if($vitals->count() > 0)

<div class="table-responsive">
    <table class="table table-striped assessment">
        <thead>
            <tr>
                <th>Date</th>
                <th>Initial Assessment</th>
                <th>Recommendation / Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vitals as $vital)
            <tr>
                <td>{{ $vital->date }}</td>
                <td>{{ $vital->initial_assessment ?? '-' }}</td>
                <td>{{ $vital->remarks ?? '-' }}</td>
                <td>
                    <a href="{{ route('user.vitals.view', $vital->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">View PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p>No vitals reports generated yet.</p>
@endif
@endsection