<?php
$this->users = new \Anax\Users\User();
$this->users->setDI($this->di);

$users = $this->users->findRanked('6');
?>
<article class="article1">
    <h2>Highest ranked users</h2>
    <div class="user-list">
        <?php foreach($users as $user) : ?>
            <div class="user-list-profile" style="width: 200px">
                <img src="<?= get_gravatar($user->email, 35, 'wavatar'); ?>" />
                <div class="profile-info">
                    <p><a href="<?= $this->url->create('users/id/' . $user->id) ?>"><?= $user->acronym ?></a></p>
                    <p><?= $user->rating ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</article>