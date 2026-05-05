<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Length Converter</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      background: #eef6fb;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px 16px;
    }

    .wrapper { width: 100%; max-width: 420px; }

    .deco {
      position: fixed;
      border-radius: 50%;
      opacity: 0.18;
      z-index: 0;
    }
    .deco1 { width: 220px; height: 220px; background: #3a9bd5; top: -60px; left: -70px; }
    .deco2 { width: 160px; height: 160px; background: #f9a825; bottom: 40px; right: -50px; }
    .deco3 { width: 90px; height: 90px; background: #3a9bd5; bottom: 180px; left: 20px; }

    .container {
      position: relative;
      z-index: 1;
      background: #ffffff;
      border-radius: 16px;
      padding: 30px 28px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    }

    .title-shape {
      background: #3a9bd5;
      border-radius: 12px;
      padding: 16px 20px;
      text-align: center;
      margin-bottom: 20px;
    }
    .title-shape h1 {
      font-family: 'Fredoka One', cursive;
      font-size: 1.8rem;
      color: #ffffff;
      margin: 0;
    }

    .instruction {
      font-size: 0.88rem;
      color: #555;
      margin-bottom: 18px;
    }

    label {
      display: block;
      font-size: 0.85rem;
      color: #444;
      margin-bottom: 5px;
    }

    input[type="number"] {
      width: 100%;
      padding: 10px 12px;
      border: 2px solid #cce4f6;
      border-radius: 8px;
      font-size: 1rem;
      box-sizing: border-box;
      outline: none;
      transition: border 0.2s;
    }
    input[type="number"]:focus { border-color: #3a9bd5; }

    .options {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin: 18px 0;
    }

    .opt-btn {
      padding: 10px 8px;
      border: 2px solid #cce4f6;
      border-radius: 10px;
      background: #f5fbff;
      font-size: 0.78rem;
      color: #2c2c2c;
      cursor: pointer;
      text-align: center;
      transition: all 0.15s;
      line-height: 1.4;
    }
    .opt-btn:hover { background: #daeef9; border-color: #3a9bd5; }
    .opt-btn.active { background: #3a9bd5; border-color: #1a6fa8; color: #fff; font-weight: bold; }

    button[type="submit"] {
      width: 100%;
      padding: 12px;
      background: #3a9bd5;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      cursor: pointer;
      font-family: 'Fredoka One', cursive;
      letter-spacing: 0.5px;
      transition: background 0.2s;
    }
    button[type="submit"]:hover { background: #1a6fa8; }

    .result {
      margin-top: 22px;
      background: #f0f9ff;
      border-left: 4px solid #3a9bd5;
      border-radius: 8px;
      padding: 16px 18px;
      font-size: 1rem;
      color: #1a1a1a;
      line-height: 1.8;
    }
    .result strong { color: #1a6fa8; font-size: 1.15rem; }

    .error {
      margin-top: 18px;
      background: #fff3f3;
      border-left: 4px solid #e74c3c;
      border-radius: 8px;
      padding: 12px 16px;
      color: #c0392b;
      font-size: 0.9rem;
    }

    .cat {
      text-align: center;
      margin-top: 24px;
    }
    .cat img { width: 80px; }
  </style>
</head>
<body>

<div class="deco deco1"></div>
<div class="deco deco2"></div>
<div class="deco deco3"></div>

<div class="wrapper">
  <div class="container">

    <div class="title-shape">
      <h1>Length Converter</h1>
    </div>

    <p class="instruction">Enter a value and pick a conversion below, then click Convert.</p>

    <form method="POST" action="">
      <label for="val">Enter Value:</label>
      <input type="number" step="any" id="val" name="val"
             placeholder="e.g. 5"
             value="<?php echo isset($_POST['val']) ? htmlspecialchars($_POST['val']) : ''; ?>">

      <div class="options">
        <?php
          // list of conversion options
          $choices = [
            'cm_to_mm' => 'Centimeter to Millimeter',
            'dm_to_cm' => 'Decimeter to Centimeter',
            'm_to_cm'  => 'Meter to Centimeter',
            'km_to_m'  => 'Kilometer to Meter',
          ];

          $selected = isset($_POST['conv']) ? $_POST['conv'] : '';

          foreach ($choices as $key => $label) {
            $active = ($selected == $key) ? 'active' : '';
            echo '<button type="button" class="opt-btn ' . $active . '" onclick="pickConv(\'' . $key . '\', this)">' . $label . '</button>';
          }
        ?>
      </div>

      <input type="hidden" name="conv" id="convInput"
             value="<?php echo isset($_POST['conv']) ? htmlspecialchars($_POST['conv']) : ''; ?>">

      <button type="submit" name="submit">Convert</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {

      $val  = isset($_POST['val'])  ? $_POST['val']  : '';
      $conv = isset($_POST['conv']) ? $_POST['conv'] : '';

      // check if input is valid
      if ($val === '' || !is_numeric($val)) {
        echo '<div class="error">Please enter a valid number.</div>';

      } elseif ($conv === '') {
        echo '<div class="error">Please choose a conversion option.</div>';

      } else {
        $val = floatval($val);

        // apply formula depending on what unit was chosen
        // based on: 1cm=10mm, 1dm=10cm, 1m=100cm, 1km=1000m

        if ($conv == 'cm_to_mm') {
          $result = $val * 10;
          $from = 'Centimeter(s)';
          $to = 'Millimeter(s)';
          $formula = $val . ' x 10 = ' . $result;

        } elseif ($conv == 'dm_to_cm') {
          $result = $val * 10;
          $from = 'Decimeter(s)';
          $to = 'Centimeter(s)';
          $formula = $val . ' x 10 = ' . $result;

        } elseif ($conv == 'm_to_cm') {
          $result = $val * 100;
          $from = 'Meter(s)';
          $to = 'Centimeter(s)';
          $formula = $val . ' x 100 = ' . $result;

        } elseif ($conv == 'km_to_m') {
          $result = $val * 1000;
          $from = 'Kilometer(s)';
          $to = 'Meter(s)';
          $formula = $val . ' x 1000 = ' . $result;
        }

        echo '<div class="result">';
        echo $val . ' ' . $from . '<br>';
        echo '= <strong>' . $result . ' ' . $to . '</strong><br>';
        echo '<small style="color:#888;">Formula: ' . $formula . '</small>';
        echo '</div>';
      }
    }
    ?>

  </div>

  <div class="cat">
    <img src="https://s3.getstickerpack.com/storage/uploads/sticker-pack/mochi-cat-animated/sticker_2.webp?e880619b04d1c3bc40d31ec638aaee0c&d=200x200" alt="cat">
  </div>

</div>

<script>
  function pickConv(val, btn) {
    document.querySelectorAll('.opt-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('convInput').value = val;
  }
</script>

</body>
</html>
