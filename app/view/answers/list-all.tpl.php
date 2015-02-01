<article class="article1">
    <h1><?= $totalOfAnswers ?> Answers</h1>
    <div>
        <?php if (is_array($answers)) : ?>
            <?php foreach ($answers as $answer) : ?>
                <p>
                    <span style="background-color: #3396D4; color: white; padding: 5px; margin-right: 10px;"><?= $answer->rating; ?></span>
                    <a href="<?= $this->url->create('question/view/'. $answer->question_id); ?>"><?= $answer->title; ?></a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <hr>
</article>