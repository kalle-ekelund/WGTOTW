<?php
/*$this->di->UsersController->initialize();
$this->di->AnswerController->initialize();

$userController = $this->di->UsersController;
$answerController = $this->di->AnswerController;

<?php $user = $userController->getUser((int)$question->user_id); ?>
                    <p>created <?= $question->created ?></p>
                    <div class="profile" style="margin-top: 5px;">
                        <img src="<?= get_gravatar($user->email, 30, 'wavatar'); ?>" alt="Profile picture" />
                        <div style="margin-left: 5px;">
                            <p><a href="../users/id/<?= $user->id?>"><?= $user->acronym ?></a></p>
                            <p><?= $user->rating ?></p>
                        </div>
                    </div>*/
?>
<article id="article1" class="article1">
    <h1><?= $question->title ?></h1>
    <div class="questionContainer">
        <div class="statsContainer" style="line-height: 0px">
            <?php if($this->di->LoginController->isLoggedInAction() && $user->id != $this->di->LoginController->getId()) : ?>
                <a href="<?= $this->url->create('question/vote/1/' . $question->id) ?>"><i class="fa fa-sort-up fa-5x"></i></a>
            <?php else : ?>
                <i class="fa fa-sort-up fa-5x"></i>
            <?php endif; ?>
            <p class="statNumber"><?= $question->rating ?></p>
            <?php if($this->di->LoginController->isLoggedInAction() && $user->id != $this->di->LoginController->getId()) : ?>
                <a href="<?= $this->url->create('question/vote/-1/' . $question->id) ?>"><i class="fa fa-sort-down fa-5x"></i></a>
            <?php else : ?>
                <i class="fa fa-sort-down fa-5x"></i>
            <?php endif; ?>
        </div>
        <div class="summary">
            <div class="questionText">
                <?= $this->di->textFilter->doFilter($question->question, 'shortcode, markdown'); ?>
            </div>
            <?php if($this->di->LoginController->isLoggedInAction() && $user->id == $this->di->LoginController->getId()) : ?>
                <p><a href="<?= $this->url->create('question/edit/' . $question->id) ?>">Edit question</a></p>
            <?php endif; ?>
            <div class="questionByline">
                <div class="tagContainer">
                    <?php if (is_array($question->tags)) : ?>
                        <?php foreach ($question->tags as $tag) : ?>
                            <span><a href="<?= $this->url->create('question/list/' . $tag->id) ?>"><?= $tag->tag ?></a></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p style="margin: 0px">edited <?= $question->updated ?></p>
                <div id="profileContainer" class="profileContainer">
                    <p>created <?= $question->created ?></p>
                    <div class="profile" style="margin-top: 5px;">
                        <img src="<?= get_gravatar($user->email, 30, 'wavatar'); ?>" alt="Profile picture" />
                        <div style="margin-left: 5px;">
                            <p><a href="../users/id/<?= $user->id?>"><?= $user->acronym ?></a></p>
                            <p><?= $user->rating ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php if (is_array($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="commentContainer">
                        <div class="statsContainer" style="line-height: 0px">
                            <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id != $this->di->LoginController->getId()) : ?>
                                <a href="<?= $this->url->create('comment/vote/1/' . $question->id . '/' . $comment->id) ?>"><i class="fa fa-sort-up fa-2x"></i></a>
                            <?php else : ?>
                                <i class="fa fa-sort-up fa-2x"></i>
                            <?php endif; ?>
                            <p class="statNumberComment"><?= $comment->rating ?></p>
                            <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id != $this->di->LoginController->getId()) : ?>
                                <a href="<?= $this->url->create('comment/vote/-1/' . $question->id . '/' . $comment->id) ?>"><i class="fa fa-sort-down fa-2x"></i></a>
                            <?php else : ?>
                                <i class="fa fa-sort-down fa-2x"></i>
                            <?php endif; ?>
                        </div>
                        <div class="questionText">
                            <?= $this->di->textFilter->doFilter($comment->comment, 'shortcode, markdown'); ?>
                            <p style="font-size: 12px">
                                - <a href="<?= $this->url->create('users/id/' . $comment->user->id) ?>"><?= $comment->user->acronym; ?></a>
                                <?= $comment->created ?>
                                <?php if($comment->updated) : ?>
                                    edited <?= $comment->updated ?>
                                <?php endif; ?>
                                <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id == $this->di->LoginController->getId()) : ?>
                                    <a href="<?= $this->url->create('comment/edit/' . $comment->id) . '/' . $question->id ?>">Edit comment</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <hr style="margin-right: 30px; margin-left: 30px;">
            <?php endforeach; ?>
        <?php endif; ?>
            <div id="newCommentQuestion" style="display: none">
                <form method=post>
                    <input type=hidden name='userId' value="<?=$this->di->LoginController->getId()?>" />
                    <input type=hidden name='questionId' value="<?=$question->id?>" />
                    <fieldset>
                        <textarea name='comment' style="min-height: 50px"></textarea>
                        <input type="submit" name='doCreate' value="Comment" onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
                    </fieldset>
                </form>
            </div>
            <?php if($this->di->LoginController->isLoggedInAction()) : ?>
                <p><a class="addCommentToQuestion" href="javascript:void(0)">Add comment</a> </p>
            <?php endif; ?>
        </div>
    </div>
    <h2><?= $numberOfAnswers ?>  Answers</h2>
    <p>Sort by: <a href="<?= $this->url->create('question/view/' . $question->id . '/rating') ?>">Rating</a>
                <a href="<?= $this->url->create('question/view/' . $question->id . '/created') ?>">Created</a>
    </p>
    <?php if (is_array($answers)) : ?>
        <?php foreach ($answers as $index => $answer) : ?>
            <div class="questionContainer">
                <div class="statsContainer" style="line-height: 0px">
                    <?php if($this->di->LoginController->isLoggedInAction() && $answer->user->id != $this->di->LoginController->getId()) : ?>
                        <a href="<?= $this->url->create('answer/vote/1/' . $question->id . '/' . $answer->id) ?>"><i class="fa fa-sort-up fa-5x"></i></a>
                    <?php else : ?>
                        <i class="fa fa-sort-up fa-5x"></i>
                    <?php endif; ?>
                    <p class="statNumber"><?= $answer->rating ?></p>
                    <?php if($this->di->LoginController->isLoggedInAction() && $answer->user->id != $this->di->LoginController->getId()) : ?>
                        <a href="<?= $this->url->create('answer/vote/-1/' . $question->id . '/' . $answer->id) ?>"><i class="fa fa-sort-down fa-5x"></i></a>
                    <?php else : ?>
                        <i class="fa fa-sort-down fa-5x"></i>
                    <?php endif; ?>
                    <?php if($this->di->LoginController->isLoggedInAction() && $user->id == $this->di->LoginController->getId()) : ?>
                        <?php if(!$question->accepted && !$answer->accepted) : ?>
                            <a style="margin-top: 20px;" href="<?= $this->url->create('answer/accept/' . $question->id . '/' . $answer->id) ?>"><i class="cflaticon-tick9"></i></a>
                        <?php elseif($answer->accepted) : ?>
                            <i style="margin-top: 20px;" class="cflaticon-check65"></i>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if($question->accepted && $answer->accepted) : ?>
                            <i style="margin-top: 20px;" class="cflaticon-check65"></i>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="summary">
                    <div class="questionText">
                        <?= $this->di->textFilter->doFilter($answer->answer, 'shortcode, markdown'); ?>
                    </div>
                    <?php if($this->di->LoginController->isLoggedInAction() && $answer->user->id == $this->di->LoginController->getId()) : ?>
                        <p><a href="<?= $this->url->create('answer/edit/' . $answer->id . '/' . $question->id) ?>">Edit answer</a></p>
                    <?php endif; ?>
                    <div class="questionByline">
                        <div></div>
                        <p>edited <?= $answer->updated ?></p>
                        <div class="profileContainer">
                            <p>created <?= $answer->created ?></p>
                            <div class="profile" style="margin-top: 5px;">
                                <img src="<?= get_gravatar($answer->user->email, 30, 'wavatar'); ?>" alt="Profile picture" />
                                <div style="margin-left: 5px;">
                                    <p><a href="../users/id/<?= $answer->user->id?>"><?= $answer->user->acronym ?></a></p>
                                    <p><?= $answer->user->rating ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if (is_array($answer->comments)) : ?>
                        <?php foreach ($answer->comments as $comment) : ?>
                            <div class="commentContainer">
                                <div class="statsContainer" style="line-height: 0px">
                                    <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id != $this->di->LoginController->getId()) : ?>
                                        <a href="<?= $this->url->create('comment/vote/1/' . $question->id . '/' . $comment->id) ?>"><i class="fa fa-sort-up fa-2x"></i></a>
                                    <?php else : ?>
                                        <i class="fa fa-sort-up fa-2x"></i>
                                    <?php endif; ?>
                                    <p class="statNumberComment"><?= $comment->rating ?></p>
                                    <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id != $this->di->LoginController->getId()) : ?>
                                        <a href="<?= $this->url->create('comment/vote/-1/' . $question->id . '/' . $comment->id) ?>"><i class="fa fa-sort-down fa-2x"></i></a>
                                    <?php else : ?>
                                        <i class="fa fa-sort-down fa-2x"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="questionText">
                                    <?= $this->di->textFilter->doFilter($comment->comment, 'shortcode, markdown'); ?>
                                    <p style="font-size: 12px">
                                        - <a href="<?= $this->url->create('users/id/' . $comment->user->id) ?>"><?= $comment->user->acronym; ?></a>
                                        <?= $comment->created ?>
                                        <?php if($comment->updated) : ?>
                                            edited <?= $comment->updated ?>
                                        <?php endif; ?>
                                        <?php if($this->di->LoginController->isLoggedInAction() && $comment->user->id == $this->di->LoginController->getId()) : ?>
                                            <a href="<?= $this->url->create('comment/edit/' . $comment->id) . '/' . $question->id ?>">Edit comment</a>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <hr style="margin-right: 30px; margin-left: 30px;">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div id="newCommentToAnswer-<?= $index ?>" style="display: none">
                        <form method=post>
                            <input type=hidden name='userId' value="<?=$this->di->LoginController->getId()?>" />
                            <input type=hidden name='questionId' value="<?=$question->id?>" />
                            <input type=hidden name='answerId' value="<?=$answer->id?>" />
                            <fieldset>
                                <textarea name='comment' style="min-height: 50px"></textarea>
                                <input type="submit" name='doCreate' value="Comment" onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
                            </fieldset>
                        </form>
                    </div>
                    <?php if($this->di->LoginController->isLoggedInAction()) : ?>
                        <p><a id="addCommentToAnswer-<?= $index ?>" class="addCommentToAnswer" href="javascript:void(0)">Add comment</a> </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <hr>
    <h2><?= $answerForm["title"] ?></h2>
    <?= $answerForm["form"] ?>
</article>