<?php
/**
 * Created by PhpStorm.
 * User: Kalle Ekelund
 * Date: 2015-01-23
 * Time: 16:31
 */

$this->tag = new \Anax\WGTOTW\Tag();
$this->tag->setDI($this->di);
$this->questionToTag = new \Anax\WGTOTW\QuestionToTag();
$this->questionToTag->setDI($this->di);

$tags = $this->tag->findAll();

foreach($tags as $tag) {
    $tag->amount = $this->questionToTag->countTag($tag->id);
}

?>
<article class="article1" style="min-height: 300px">
    <h1>Tags</h1>
    <div class="tagContainer">
        <?php if (is_array($tags)) : ?>
            <?php foreach ($tags as $tag) : ?>
                <span><a href="<?= $this->url->create('question/list/' . $tag->id) ?>"><?= $tag->tag ?></a> (<?= $tag->amount ?>)</span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</article>
