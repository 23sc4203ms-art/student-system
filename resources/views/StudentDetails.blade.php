@extends('format.layout')

@section('title', 'Student Details')

@section('content')
    <style>
        * {
            transition: all 0.3s ease;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 20px;
        }

        .details-header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .details-header h1 i {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            padding: 11px 22px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(102, 126, 234, 0.15);
            border-color: rgba(102, 126, 234, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: auto;
            padding: 20px;
            width: 100%;
        }

        .details-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 249, 255, 0.95) 100%);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 800px;
            min-width: 300px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(20px);
            margin: 0 auto;
        }

        .student-name {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #e5e7eb;
        }

        .student-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
        }

        .student-name h2 {
            color: #1f2937;
            margin: 10px 0 6px 0;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .student-course {
            color: #667eea;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #f3f4f6 100%);
            padding: 18px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #667eea;
        }

        .detail-item:hover {
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.12);
            border-left-color: #764ba2;
        }

        .detail-label {
            font-weight: 700;
            color: #667eea;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-label i {
            font-size: 0.9rem;
        }

        .detail-value {
            color: #1f2937;
            font-size: 1rem;
            font-weight: 600;
        }

        .detail-item.email,
        .detail-item.contact {
            grid-column: 1 / -1;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #e5e7eb;
        }

        .btn-edit {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 13px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.3);
            letter-spacing: 0.3px;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(102, 126, 234, 0.45);
        }

        .btn-edit:active {
            transform: translateY(-1px);
        }

        @media (max-width: 600px) {
            .details-header h1 {
                font-size: 1.8rem;
            }

            .btn-back {
                width: 100%;
                justify-content: center;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .detail-item.email,
            .detail-item.contact {
                grid-column: 1;
            }

            .details-container {
                padding: 30px 20px;
                max-width: 95%;
            }

            .student-avatar {
                width: 85px;
                height: 85px;
                font-size: 2rem;
            }

            .student-name h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .details-container {
                padding: 25px 18px;
                max-width: 98%;
            }

            .details-header h1 {
                font-size: 1.6rem;
            }

            .student-name h2 {
                font-size: 1.3rem;
            }

            .details-grid {
                gap: 12px;
            }

            .detail-item {
                padding: 16px;
            }
        }
    </style>

    <div class="details-header">
        <h1><i class="fas fa-id-card"></i> Student Details</h1>
        <a href="{{ route('students') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="details-container">
        <div class="student-name">
            <div class="student-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>{{ $student->Fname }} {{ $student->Mname }} {{ $student->Lname }}</h2>
            <div class="student-course">{{ $student->degree?->name ?? 'No Degree Assigned' }}</div>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user"></i> First Name
                </div>
                <div class="detail-value">{{ $student->Fname }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user"></i> Middle Name
                </div>
                <div class="detail-value">{{ $student->Mname ?? 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user"></i> Last Name
                </div>
                <div class="detail-value">{{ $student->Lname }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-location-dot"></i> Address
                </div>
                <div class="detail-value">{{ $student->Address }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-book"></i> Degree
                </div>
                <div class="detail-value">{{ $student->degree?->name ?? 'N/A' }}</div>
            </div>

            <div class="detail-item email">
                <div class="detail-label">
                    <i class="fas fa-envelope"></i> Email
                </div>
                <div class="detail-value">
                    <a href="mailto:{{ $student->Email }}" style="color: #000000; text-decoration: none;">
                        {{ $student->Email }}
                    </a>
                </div>
            </div>

            <div class="detail-item contact">
                <div class="detail-label">
                    <i class="fas fa-phone"></i> Contact Number
                </div>
                <div class="detail-value">
                    <a href="tel:{{ $student->Contactno }}" style="color: #000000; text-decoration: none;">
                        {{ $student->Contactno }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

