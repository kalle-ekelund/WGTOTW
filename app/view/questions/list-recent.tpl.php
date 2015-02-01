<?php
$this->questions = new \Anax\WGTOTW\Question();
$this->questions->setDI($this->di);
$this->answers = new \Anax\WGTOTW\Answer();
$this->answers->setDI($this->di);

$questions = $this->questions->findRecent("5");

foreach($questions as $question) {
    $question->answers = $this->answers->count($question->id);
}
?>
<article class="article1">
    <h2>Recent questions</h2>
    <div>
        <?php foreach($questions as $question) : ?>
            <div class="recentQuestions">
                <div class="statsContainer">
                    <p><?= $question->rating ?></p>
                    <p style="font-size: small;">rating</p>
                </div>
                <div class="statsContainer">
                    <p><?= $question->answers ?></p>
                    <p style="font-size: small;">answers</p>
                </div>
                <div class="titleContainer">
                    <p><a href="<?= $this->url->create('question/view/' . $question->id) ?>"><?= $question->title ?></a></p>
                    <p class="created">created <?= $question->created ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</article>