@extends('format.layout')

@section('title', 'Activity Log Details')

@section('content')
    <style>
        .log-wrap {
            max-width: 860px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .log-title {
            margin: 0;
            color: #0f2f73;
            font-size: 1.9rem;
            font-weight: 800;
        }

        .meta {
            margin-top: 10px;
            color: #1f2937;
            line-height: 1.7;
        }

        .meta strong {
            color: #111827;
        }

        .changes-box {
            margin-top: 14px;
            background: #ffffff;
            border: 1px solid #dde3ef;
            border-radius: 10px;
            padding: 12px;
        }

        .change-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 0;
            color: #1f2937;
        }

        .change-item:last-child {
            border-bottom: none;
        }

        .tag-old {
            color: #991b1b;
            font-weight: 700;
        }

        .tag-new {
            color: #065f46;
            font-weight: 700;
        }

        .back-link {
            margin-top: 14px;
            display: inline-block;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
        }

        .empty-note {
            color: #6b7280;
        }
    </style>

    <div class="log-wrap">
        <h1 class="log-title">Activity Log Detail</h1>

        <div class="meta">
            <div><strong>Date:</strong> {{ $log->created_at?->format('M d, Y h:i:s A') }}</div>
            <div><strong>Action:</strong> {{ strtoupper($log->action) }}</div>
            <div><strong>Type:</strong> {{ $log->subject_type }}</div>
            <div><strong>Record ID:</strong> {{ $log->record_id }}</div>
            <div><strong>Description:</strong> {{ $log->description }}</div>
        </div>

        <div class="changes-box">
            <strong>Changes</strong>
            @if(is_array($log->changes) && count($log->changes))
                @foreach($log->changes as $field => $pair)
                    <div class="change-item">
                        <div><strong>{{ $field }}</strong></div>
                        @if(is_array($pair) && array_key_exists('old', $pair) && array_key_exists('new', $pair))
                            <div><span class="tag-old">Old:</span> {{ is_array($pair['old']) ? json_encode($pair['old']) : $pair['old'] }}</div>
                            <div><span class="tag-new">New:</span> {{ is_array($pair['new']) ? json_encode($pair['new']) : $pair['new'] }}</div>
                        @else
                            <div>{{ is_array($pair) ? json_encode($pair) : $pair }}</div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="empty-note">No detailed change data.</p>
            @endif
        </div>

        <a href="{{ route('activity-logs.index') }}" class="back-link">← Back to Activity Logs</a>
    </div>
@endsection
