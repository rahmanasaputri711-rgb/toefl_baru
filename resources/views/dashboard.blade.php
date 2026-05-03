{{-- Redirect ke user dashboard --}}
@php
    if(auth()->user()->role === 'admin') {
        header('Location: /admin');
    } else {
        header('Location: /dashboard');
    }
    exit;
@endphp
