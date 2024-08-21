<?php
include 'src/components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Page</title>
    <link rel="stylesheet" href="src/css/store.css">
</head>
<body>
  <main>
    <article>
      <h2>Products</h2>
      <div class="store-container">
        <button class="scroll-btn left" onclick="scrollStore(-1)">
          &#10094;
        </button>
        <div class="store" id="product-list"></div>
        <button class="scroll-btn right" onclick="scrollStore(1)">
          &#10095;
        </button>
      </div>
    </article>
  </main>

  <template id="product-template">
    <div class="product">
      <div class="product-img">
        <img src="" alt="" />
      </div>
      <div class="product-text">
        <a href="#" target="_blank">
          <h2 class="link-titel"></h2>
        </a>
        <p></p>
      </div>
    </div>
  </template>
</body>
</html>

<script src="src/js/store1.js" defer></script>
<?php include 'src/components/footer.php'; ?>
