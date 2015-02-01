<article class="article1">
    <h1>Questions</h1>
    <a href="<?= $this->url->create('question/create') ?>">Create a question</a>
    <?php if (is_array($questions)) : ?>
        <?php foreach ($questions as $id => $question) : ?>
            <div class="questionContainer">
                <div class="statsContainer">
                    <div style="margin-bottom: 10px;">
                        <p class="statNumber"><?= $question->rating ?></p>
                        <p>rating</p>
                    </div>
                    <div>
                        <p class="statNumber"><?= $question->answers ?></p>
                        <p>answers</p>
                    </div>
                </div>
                <div class="summary">
                    <h3><a href="<?= $this->url->create('question/view/' . $question->id) ?>"><?= $question->title ?></a></h3>
                    <p><?= trim_text($question->question, 180) ?></p>
                    <div class="questionByline">
                        <div class="tagContainer">
                            <?php if (is_array($question->tags)) : ?>
                                <?php foreach ($question->tags as $tag) : ?>
                                    <span><a href="<?= $this->url->create('question/list/' . $tag->id) ?>"><?= $tag->tag ?></a></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <p>edited <?= $question->updated ?></p>
                        <div class="profileContainer">
                            <p>created <?= $question->created ?></p>
                            <div class="profile" style="margin-top: 5px;">
                                <img src="<?= get_gravatar($question->user[0]->email, 30, 'wavatar'); ?>" alt="Profile picture" />
                                <div style="margin-left: 5px;">
                                    <p><a href="../users/id/<?= $question->user[0]->id ?>"><?= $question->user[0]->acronym ?></a></p>
                                    <p><?= $question->user[0]->rating ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</article>