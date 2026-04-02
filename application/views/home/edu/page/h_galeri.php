<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="full-width">
        <div class="block">
            <div class="block-title">
                <h2>Galeri Foto</h2>
            </div>
            <div class="block-content">
                <div class="overflow-fix">
                    <div class="photo-gallery-grid js-masonry" data-masonry-options='{ "itemSelector": ".photo-gallery-block" }'>
                        <?php
                        foreach ($galeri['data'] as $gl) {
                            ?>
                            <div class="photo-gallery-block" style="min-height:420px;height: 420px;">
                                <div class="gallery-photo">
                                    <a href="<?= site_url('galeri/' . $gl['slug_galeri']) ?>" class="hover-effect">
                                        <img  class="lazyload blur-up" src="<?= load_file($gl['foto_galeri']) ?>" alt="<?= ctk($gl['judul_galeri']) ?>">
                                    </a>
                                </div>
                                <div class="gallery-content">
                                    <h3>
                                        <a href="<?= site_url('galeri/' . $gl['slug_galeri']) ?>">
                                            <?= limit_text($gl['judul_galeri'],50) ?>
                                        </a>
                                    </h3>
                                    <p><?= limit_text($gl['isi_galeri'], 50) ?></p>
                                    <a href="<?= site_url('galeri/' . $gl['slug_galeri']) ?>" class="more">
                                        Selengkapnya&nbsp;&nbsp;<span class="icon-text">&#9656;</span>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="pagination">
                    <?= $pagination ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear-float"></div>
</div>