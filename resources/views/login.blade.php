<!DOCTYPE html>
<html>
<head>
    <title>Login - Skulbase</title>
</head>
<body>
    <h1>Login Page</h1>

    @if($errors->any())
        <div style="color:red; margin-bottom: 1rem;">
            <strong>Whoops!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Password</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>

    <p>
        Don't have an account?
        <a href="{{ route('register') }}">Register</a>
    </p>
</body>
</html>
