<hr>

<h2>Inlägg</h2>

<?php if (is_array($comments)) : ?>
<div class='comments'>
<?php foreach ($comments as $id => $comment) : ?>
<h4>#<?=$id?></h4>
<p class="comment-name"><?=$comment->name ?></p>
<p class="comment"><?=$comment->content?></p>
    <p class="comment-date"><?=$comment->created?>
    <form method='post'>
        <input type='hidden' name="redirect" value="<?=$this->url->create($comment->page_key)?>">
        <input type='hidden' name="id" value="<?=$comment->id?>">
        <input type='hidden' name="key" value="<?=$comment->page_key?>">
        <input type='submit' name='doEdit' value='Redigera inlägg' onClick="this.form.action = '<?=$this->url->create('comment/editForm/' .$comment->id)?>'" />
        <input type='submit' name='doRemove' value='Ta bort inlägg' onClick="this.form.action = '<?=$this->url->create('comment/remove/' .$comment->id . '/' . $comment->page_key)?>'" />
    </form>
    </p>

<?php endforeach; ?>
</div>
<?php endif; ?>