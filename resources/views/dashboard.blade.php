@extends('clientLayout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('My Violations') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Violation</th>
                                <th>Date Committed</th>
                                <th>Offense Level</th>
                                <th>Fee</th>
                                <th>Penalty Description</th>
                                <th>Status</th>
                                <th>Date Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($citations as $citation)
                                <tr>
                                    <td>{{ $citation->violation->name }}</td>
                                    <td>{{ $citation->date_committed ? $citation->date_committed->format('F d, Y') : 'N/A' }}</td>
                                    <td>
                                        @switch($citation->offense_level)
                                            @case(1) 1st @break
                                            @case(2) 2nd @break
                                            @case(3) 3rd @break
                                            @case(4) 4th @break
                                            @default N/A
                                        @endswitch
                                    </td>
                                    <td>₱
                                        @switch($citation->offense_level)
                                            @case(1) {{ $citation->violation->first_offense }} @break
                                            @case(2) {{ $citation->violation->second_offense }} @break
                                            @case(3) {{ $citation->violation->third_offense }} @break
                                            @case(4) {{ $citation->violation->fourth_offense }} @break
                                            @default N/A
                                        @endswitch
                                    </td>
                                    <td>{{ $citation->violation->penalty }}</td>
                                    <td>
                                        @if ($citation->status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{ $citation->paid_at ? $citation->paid_at->format('F d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No violations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="text-right">
                        <h4>Total Unpaid Fees: ₱{{ number_format($totalUnpaidFees, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
