@extends('format.layout')

@section('title', 'Degree Details')

@section('content')
    <h1><i class="fas fa-graduation-cap"></i> Degree Details</h1>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #fff; padding: 24px; border-radius: 12px; max-width: 700px;">
        <p><strong>ID:</strong> {{ $degree->id }}</p>
        <p><strong>Degree Name:</strong> {{ $degree->name }}</p>

        <div style="display: flex; gap: 10px; margin-top: 20px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('degrees.index') }}" style="background: #e5e7eb; color: #111827; text-decoration: none; border-radius: 8px; padding: 10px 16px; font-weight: 600;">Back to Degrees</a>
        </div>
    </div>
@endsection
