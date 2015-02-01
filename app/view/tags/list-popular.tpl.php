<?php
$this->tag = new \Anax\WGTOTW\Tag();
$this->tag->setDI($this->di);
$this->questionToTag = new \Anax\WGTOTW\QuestionToTag();
$this->questionToTag->setDI($this->di);

$tags = $this->questionToTag->findPopularTags("8");

foreach($tags as $tag) {
    $tag->tag = $this->tag->find($tag->tag_id)->tag;
}
?>
<article class="article1">
    <h2>Popular tags</h2>
    <div class="tagContainer">
        <?php if (is_array($tags)) : ?>
            <?php foreach ($tags as $tag) : ?>
                <span><a href="<?= $this->url->create('question/list/' . $tag->tag_id) ?>"><?= $tag->tag ?></a> (<?= $tag->count ?>)</span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</article>