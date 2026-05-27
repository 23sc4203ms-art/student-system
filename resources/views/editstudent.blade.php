@extends('format.layout')

@section('title', 'Edit Student')

@section('content')

    <style>
        .edit-student-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .edit-student-header h1 {
            margin: 0;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid white;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row .form-group {
            margin-bottom: 0;
        }

        .alert-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-error li {
            margin: 5px 0;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-submit {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 25px;
            }
        }
    </style>

    <div class="edit-student-header">
        <h1><i class="fas fa-edit"></i> Edit Student</h1>
        <a href="{{ route('students') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.update', $student->id) }}" method="POST" class="form-container ajax-submit">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label for="Fname"><i class="fas fa-user"></i> First Name</label>
                <input type="text" id="Fname" name="Fname" value="{{ old('Fname', $student->Fname) }}" placeholder="Enter first name" required>
            </div>
            <div class="form-group">
                <label for="Mname"><i class="fas fa-user"></i> Middle Name</label>
                <input type="text" id="Mname" name="Mname" value="{{ old('Mname', $student->Mname) }}" placeholder="Enter middle name">
            </div>
        </div>

        <div class="form-group">
            <label for="Lname"><i class="fas fa-user"></i> Last Name</label>
            <input type="text" id="Lname" name="Lname" value="{{ old('Lname', $student->Lname) }}" placeholder="Enter last name" required>
        </div>

        <div class="form-group">
            <label for="Address"><i class="fas fa-location-dot"></i> Address</label>
            <input type="text" id="Address" name="Address" value="{{ old('Address', $student->Address) }}" placeholder="Enter address" required>
        </div>

        <div class="form-group">
            <label for="degree_id"><i class="fas fa-book"></i> Degree</label>
            <select id="degree_id" name="degree_id" required>
                <option value="">Select a Degree</option>
                @foreach($degrees as $degree)
                    <option value="{{ $degree->id }}" {{ (int) old('degree_id', $student->degree_id) === $degree->id ? 'selected' : '' }}>
                        {{ $degree->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="Email" name="Email" value="{{ old('Email', $student->Email) }}" placeholder="Enter email address" required>
        </div>

        <div class="form-group">
            <label for="Contactno"><i class="fas fa-phone"></i> Contact Number</label>
            <input type="text" id="Contactno" name="Contactno" value="{{ old('Contactno', $student->Contactno) }}" placeholder="Enter contact number" required>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> UPDATE!
            </button>
        </div>
    </form>
@endsection

