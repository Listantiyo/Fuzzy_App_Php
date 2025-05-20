<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Simple Login</title>
  <!-- Bootstrap CSS -->
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    crossorigin="anonymous"
  />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    main {
      height: 90vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-form {
      background: #fff;
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 320px;
    }
    .login-form h2 {
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 600;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="mb-2 pt-3 px-3 d-flex justify-content-between" style="width:100%;text-align:right;">
    <a href="login_admin.php">Home</a>
    <a href="login_admin.php?login=true">Masuk Admin</a>
  </div>
  
  <main>
    <form class="login-form " method="post" action="user/proses-login.php">
      <h2>Login User</h2>
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <div class="mt-3">
        <a href="user/register.php">Register</a>
      </div>
    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
