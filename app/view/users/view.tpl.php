<article class="article1">
    <h1><?= $user->acronym?></h1>
    <div class="user-profile">
        <img src="<?= get_gravatar($user->email, 120, 'wavatar');?>" />
        <div class="user-profile-info">
            <p><?= $user->name ?></p>
            <p><?= $user->email ?></p>
            <p>Medlem sedan <?= $user->created ?></p>
            <?php if($this->di->LoginController->isLoggedInAction() && $user->id == $this->di->LoginController->getId()) : ?>
            <br/><p><a href="<?= $this->url->create('users/edit/' . $user->id) ?>">Edit profile</a></p>
            <?php endif; ?>
        </div>
        <div class="user-profile-info" style="margin: 0px auto;">
            <p>Rating</p>
            <p style="font-size: 30px; font-weight: bold; text-align: center"><?= $user->rating ?></p>
        </div>
    </div>
    <div class="user-profile-links">
        <div style="flex: 1">
            <h3>Latest questions</h3>
            <?php foreach($questions as $question) : ?>
                <p><span class="rating"><?= $question->rating; ?></span><a href="<?= $this->url->create('question/view/'. $question->id); ?>"><?= $question->title; ?></a></p>
            <?php endforeach; ?>
            <p><a href="<?= $this->url->create('question/list/'. $user->id . '/' . $user->acronym); ?>">Show all</a></p>
        </div>
        <div style="flex: 1">
            <h3>Latest answers</h3>
            <?php foreach($answers as $answer) : ?>
                <p><span class="rating"><?= $answer->rating; ?></span><a href="<?= $this->url->create('question/view/'. $answer->question_id); ?>"><?= $answer->title; ?></a></p>
            <?php endforeach; ?>
            <p><a href="<?= $this->url->create('answer/list/'. $user->id . '/' . $user->acronym); ?>">Show all</a></p>
        </div>
        <div>
            <h3>Stats</h3>
            <p>Asked questions: <?= $totalOfQuestions; ?></p>
            <p>Answered questions: <?= $totalOfAnswers; ?></p>
            <p>Comments made: <?= $totalOfComments; ?></p>
            <p>Accepted answers: <?= $totalOfAcceptedAnswers; ?></p>
        </div>
    </div>
</article>