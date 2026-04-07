<head>
  <title>Phising Simulator - Login</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

  <div class="login-wrapper">
    <div class="login-box">

      <h2>🔐 Phishing Simulator</h2>

      <form class="login-form" method="POST" action="authenticate.php">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Enter your username" />
        
        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter your password" />
        
        <button type="submit">Login</button>
      </form>

      <?php if(isset($_GET['error'])): ?>
        <p class="error">Invalid credentials. Please try again.</p>
      <?php endif; ?>

    </div>
  </div>

</body>