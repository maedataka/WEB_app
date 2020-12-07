<h1>会社情報の一覧</h1>
<form action="create.php" method="POST">
    <?php if (count($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?php echo $error; ?> </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div>
        <div class="alert alert-primary" role="alert">
            <label for="name">会社名</label>
            <input type="text" id="name" name="name" value="<?php echo $company['name'] ?>">
        </div>
        <div>
            <label for=" establishment_date">設立日</label>
            <input type="date" id="establishment_date" name="establishment_date" value="<?php echo $company['establishment_date'] ?>">
        </div>
        <div>
            <label for="founder">代表者</label>
            <input type="text" id="founder" name="founder" value="<?php echo $company['founder'] ?>">
        </div>
        <button type="submit" class="btn btn-secondary">登録する</button>
    </div>
</form>
