@extends('format.layout')

@section('title', 'Posts')

@section('content')
    <style>
        .posts-wrap {
            max-width: 860px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .posts-title {
            margin: 0;
            color: #0f2f73;
            font-size: 2rem;
            font-weight: 800;
        }

        .posts-sub {
            margin: 8px 0 14px;
            color: #4b5563;
            border-top: 1px solid #d6dce8;
            padding-top: 8px;
            font-size: 1.02rem;
        }

        .posts-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .posts-table th,
        .posts-table td {
            border: 1px solid #dde3ef;
            padding: 11px 12px;
            text-align: left;
            font-size: 0.96rem;
            color: #202938;
            vertical-align: top;
        }

        .posts-table th {
            background: #e9eef7;
            font-weight: 700;
        }

        .empty-note {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .muted {
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>

    <div class="posts-wrap">
        <h1 class="posts-title">Posts Management</h1>
        <p class="posts-sub">Manage posts and user updates.</p>

        <!-- Create / Edit Form (AJAX) -->
        <div style="margin-bottom:14px;">
            <form id="postForm">
                <input type="hidden" id="postId" name="id" value="" />
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input id="postTitle" name="title" placeholder="Title" style="flex:0 0 220px;padding:8px;border:1px solid #ccd5e6;border-radius:6px;" />
                    <input id="postContent" name="content" placeholder="Content (short)" style="flex:1;padding:8px;border:1px solid #ccd5e6;border-radius:6px;" />
                    <button id="savePost" type="submit" style="padding:8px 12px;border-radius:6px;background:#2563eb;color:#fff;border:none;">Save</button>
                    <button id="cancelEdit" type="button" style="padding:8px 12px;border-radius:6px;background:#6b7280;color:#fff;border:none;display:none;">Cancel</button>
                </div>
            </form>
        </div>

        @if($posts->count())
            <table class="posts-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 220px;">Title</th>
                        <th>Content</th>
                        <th style="width: 180px;">Author</th>
                        <th style="width: 150px;">Posted Date</th>
                        <th style="width:160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        @php
                            $postDate = $post->created_at;
                        @endphp
                        <tr data-id="{{ $post->id }}">
                            <td>{{ $post->id }}</td>
                            <td class="titleCell">{{ $post->title }}</td>
                            <td class="contentCell">{{ \Illuminate\Support\Str::limit($post->content, 90) }}</td>
                            <td>{{ $post->user?->name ?? 'Unknown User' }}</td>
                            <td>
                                @if($postDate)
                                    {{ $postDate->format('M d, Y') }}
                                    <div class="muted">{{ $postDate->format('h:i:s A') }}</div>
                                @else
                                    <span class="muted">No created timestamp</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-edit" data-id="{{ $post->id }}" style="margin-right:6px;padding:6px 8px;border-radius:6px;background:#059669;color:#fff;border:none;">Edit</button>
                                <button class="btn-delete" data-id="{{ $post->id }}" style="padding:6px 8px;border-radius:6px;background:#dc2626;color:#fff;border:none;">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">No posts found.</p>
        @endif
    </div>
    <!-- jQuery + app JS for AJAX CRUD -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/js/app.js"></script>
@endsection
