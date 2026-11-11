
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Admin Login</title>
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card shadow p-4 w-100" style="max-width: 400px;">

            <h1 class="text-center mb-2">Welcome Back</h1>

            <p class="text-center text-secondary mb-4">
                Sign in to your account
            </p>

            <form id="loginForm">

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input type="text" id="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input type="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

                <p id="errorMessage" class="text-danger mt-3 mb-0"></p>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('login.js') }}"></script>

</body>
</html>
