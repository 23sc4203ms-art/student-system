@extends('format.layout')

@section('title', 'Degrees')

@section('content')
    <h1><i class="fas fa-graduation-cap"></i> Degrees</h1>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 10px; margin-bottom: 16px;">
        <a href="{{ route('degrees.create') }}" style="background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; padding: 10px 16px; font-weight: 600;">Add Degree</a>
        <a href="{{ route('students') }}" style="background: #e5e7eb; color: #111827; text-decoration: none; border-radius: 8px; padding: 10px 16px; font-weight: 600;">Students</a>
    </div>

    <div style="background: #fff; border-radius: 12px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 12px; text-align: left; width: 90px;">ID</th>
                    <th style="padding: 12px; text-align: left;">Degree Name</th>
                    <th style="padding: 12px; text-align: left; width: 260px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($degrees as $degree)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">{{ $degree->id }}</td>
                        <td style="padding: 12px;">{{ $degree->name }}</td>
                        <td style="padding: 12px; display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('degrees.show', $degree->id) }}" style="background: #3b82f6; color: #fff; text-decoration: none; border-radius: 8px; padding: 6px 12px; font-weight: 600;">View</a>
                            <a href="{{ route('degrees.edit', $degree->id) }}" style="background: #10b981; color: #fff; text-decoration: none; border-radius: 8px; padding: 6px 12px; font-weight: 600;">Edit</a>
                            <form action="{{ route('degrees.destroy', $degree->id) }}" method="POST" class="ajax-delete" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: #fff; border: 0; border-radius: 8px; padding: 6px 12px; font-weight: 600; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 12px;">No degree records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
