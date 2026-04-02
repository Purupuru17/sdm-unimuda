<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="full-width">
        <div class="block">
            <div class="block-title">
                <a href="#" class="right"><?= format_date($detail['update_galeri'], 0) ?></a>
                <h2><?= $detail['judul_galeri'] ?></h2>
            </div>
            <div class="block-content">

                <div class="photo-gallery-full">
                    <div class="the-image">
                        <img class="lazyload blur-up" src="<?= load_file($detail['foto_galeri']) ?>" alt="<?= ctk($detail['judul_galeri']) ?>">
                    </div>
                </div>

                <div class="shortcode-content">
                    <h2><?= $detail['judul_galeri'] ?></h2>
                    <p><?= $detail['isi_galeri'] ?></p>
                    <hr />
                </div>
            </div>
        </div>
    </div>
    <div class="clear-float"></div>
</div>