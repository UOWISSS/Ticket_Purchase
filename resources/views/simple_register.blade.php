<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        form{max-width:420px;margin:0 auto}
        label{display:block;margin-top:12px}
        input[type="text"], input[type="email"], input[type="password"]{width:100%;padding:8px;margin-top:6px}
        .errors{background:#fee;padding:10px;border:1px solid #fbb;margin-bottom:12px}
    </style>
</head>
<body>
    <h1>Register</h1>

    @if(session('success'))
        <div class="success" style="background:#efe;padding:10px;border:1px solid #bfb;margin-bottom:12px">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <strong>There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('simple.register.store') }}">
        @csrf
        <input type="hidden" name="previous" value="{{ url()->previous() }}">

        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required>

        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="{{ old('username') }}" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>

        <div style="margin-top:16px">
            <button type="submit">Register</button>
        </div>
    </form>
</body>
</html>
