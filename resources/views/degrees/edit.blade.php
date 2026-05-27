@extends('format.layout')

@section('title', 'Edit Degree')

@section('content')
    <h1><i class="fas fa-pen"></i> Edit Degree</h1>

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('degrees.update.post', $degree->id) }}" method="POST" class="ajax-submit" style="background: #fff; padding: 24px; border-radius: 12px; max-width: 600px;">
        @csrf

        <div style="margin-bottom: 16px;">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: 8px;">Degree Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $degree->name) }}"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;" required>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #2563eb; color: #fff; border: 0; border-radius: 8px; padding: 10px 16px; font-weight: 600;">Update</button>
            <a href="{{ route('degrees.index') }}" style="background: #e5e7eb; color: #111827; text-decoration: none; border-radius: 8px; padding: 10px 16px; font-weight: 600;">Cancel</a>
        </div>
    </form>

    <form action="{{ route('degrees.destroy', $degree->id) }}" method="POST" class="ajax-delete" style="margin-top: 12px; max-width: 600px;">
        @csrf
        @method('DELETE')
        <button type="submit" style="background: #ef4444; color: #fff; border: 0; border-radius: 8px; padding: 10px 16px; font-weight: 600; cursor: pointer;">Delete Degree</button>
    </form>
@endsection
