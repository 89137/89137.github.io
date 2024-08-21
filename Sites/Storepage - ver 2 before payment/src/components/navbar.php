<!-- <?php
echo '<pre>';
print_r($_SESSION); // This will show all session data for debugging
echo '</pre>';
?> -->

<nav>
  <header>
    <h1>Bells store</h1>
    <div class="menu-btn" onclick="toggleMenu()">
      <span>☰</span>
    </div>
  </header>
  <div id="nav-menu" class="nav-menu">
    <ul>
      <li><a href="index.php">Store</a></li>
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <li><a href="account.php">Account</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li><a href="add_product.php">Add Product</a></li>
          <li><a href="view_orders.php">View Orders</a></li>
        <?php endif; ?>
        <li><a href="#" id="logout-link" onclick="logout()">Logout</a></li>
        <li class="user-info">
          Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>
          <br>
          <?php
          if (isset($_SESSION['role'])) {
            echo ($_SESSION['role'] === 'admin') ? 'Admin' : 'User';
          } else {
            echo 'User'; // Default value if role is not set
          }
          ?>
        </li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

