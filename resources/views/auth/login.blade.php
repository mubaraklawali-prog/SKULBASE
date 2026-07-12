<!DOCTYPE html>
<html>

<head>
    <title>Login - Skulbase</title>
</head>

<body>

    <h1>Login</h1>

    @if ($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label>Email</label><br>
            <input type="email" name="email" required>
        </div>

        <br>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <br>

        <button type="submit">
            Login
        </button>
    </form>

    <br>

    <a href="{{ route('register') }}">
        Create an account
    </a>

</body>

</html>
