<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Change Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .box {
            background: #fff;
            padding: 20px;
            max-width: 400px;
            border-radius: 6px;
        }
        button {
            padding: 8px 14px;
            cursor: pointer;
        }
        .msg {
            margin-bottom: 15px;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="box">
    <h3>TEST: Change Menu (Admin)</h3>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="msg">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="msg error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- TEST FORM -->
    <form method="post" action="<?= site_url('admin/subscription-deliveries/change-menu') ?>">

        <div>
            <label>Delivery ID</label><br>
            <input type="number" name="delivery_id" value="1" required>
        </div>

        <br>

        <div>
            <label>Menu ID</label><br>
            <input type="number" name="menu_id" value="1" required>
        </div>

        <br>

        <button type="submit">TEST CHANGE MENU</button>
    </form>
</div>

</body>
</html>
