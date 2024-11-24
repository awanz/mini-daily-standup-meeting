<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php 
        $this->insert('layouts/head',[
            'siteTitle' => $siteTitle,
        ]) 
    ?>
</head>
<body>
    <?php 
        $this->insert('layouts/navbar', [
            'isAdmin' => $isAdmin,
            'isHR' => $isHR,
        ]) 
    ?>
    <div class="container">
        <main>
            <?= $this->section('content'); ?>
        </main>
    </div>
    <?php $this->insert('layouts/foot'); ?>
    <?=$this->section('footAdditional')?>
</body>
</html>