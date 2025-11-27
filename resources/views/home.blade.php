<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-200">
        <div style="background-image: url('{{ asset("/images/mapbg.png") }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh;">
            <div class="p-8">
                <header class="w-full lg:max-w-4xl mx-auto text-sm">
                    <nav class="flex items-center justify-between p-2 bg-[#2D2D37] rounded-full text-white">
                        <div class="flex items-center gap-x-8">
                            <a href="/" class="text-xl font-semibold pl-4">
                                HULE!
                            </a>
                            <a href="/" class="hidden sm:block text-gray-300 hover:text-white">Home</a>
                            <a href="/about" class="hidden sm:block text-gray-300 hover:text-white">About</a>
                            <a href="/contact" class="hidden sm:block text-gray-300 hover:text-white">Contact Us</a>
                        </div>
                        <div class="flex items-center gap-x-4 pr-4">
                            <button type="button" class="px-4 py-2 text-white border-dashed border-gray-500 rounded-full hover:bg-gray-700 dashed-border" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Login/Signup
                            </button>
                        </div>
                    </nav>
                </header>
            </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title w-100 text-center" id="loginModalLabel">Choose Account Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-around text-center mb-4">
            <div id="user-account" class="account-type selected-account" style="cursor: pointer;">
                <div class="account-box">
                    <img src="images/user.png" alt="User" width="80" height="80">
                    <div class="checkmark">✔</div>
                </div>
                <p>User</p>
            </div>
            
            <div id="admin-account" class="account-type" style="cursor: pointer;">
                <div class="account-box">
                    <img src="images/admin.png" alt="Administrator" width="90" height="90">
                    <div class="checkmark">✔</div>
                </div>
                <p>Administrator</p>
            </div>
        </div>
        <p id="greeting-text" class="text-center text-muted small">Hello User!</p>
        <p class="text-center text-muted small">Please fill out the form below to get started</p>
        <form>
          <div class="mb-3">
            <input type="email" class="form-control" id="email" placeholder="Username or email">
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" id="password" placeholder="Password">
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary login-btn" style="background-color: blue;">LOGIN</button>
          </div>
          <div class="text-end">
            <a href="#" class="text-muted small">Forgot password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userAccount = document.getElementById('user-account');
        const adminAccount = document.getElementById('admin-account');
        const greetingText = document.getElementById('greeting-text');

        function updateSelection(selected, unselected, text) {
            greetingText.innerText = text;
            selected.classList.add('selected-account');
            unselected.classList.remove('selected-account');
        }

        userAccount.addEventListener('click', function () {
            updateSelection(userAccount, adminAccount, 'Hello User!');
        });

        adminAccount.addEventListener('click', function () {
            updateSelection(adminAccount, userAccount, 'Hello Administrator!');
        });
    });
</script>
    </body>
</html>
