<article class="article1">
<h1><?=$title?></h1>

<?php if (is_array($users)) : ?>
    <div class="user-list">
    <?php foreach ($users as $id => $user) : ?>
        <?php if($user->active) : ?>
            <div class="user-list-profile">
                <img src="<?= get_gravatar($user->email, 60, 'wavatar'); ?>" alt="Profile picture" />
                <div class="profile-info">
                    <p><a href="id/<?=$user->id; ?>"><?=$user->acronym; ?></a></p>
                    <p><?=$user->name; ?></p>
                    <p><?=$user->rating; ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
</article>