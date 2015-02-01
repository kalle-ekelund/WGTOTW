<div class='login-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create('')?>">
        <fieldset>
            <legend>Login</legend>
            <p><label>Username:<br/><input type='text' name='acronym' /></label></p>
            <p><label>Password:<br/><input type='password' name='password'/></label></p>
            <p class=buttons>
                <input type='submit' name='doLogin' value='Login' onClick="this.form.action = '<?=$this->url->create('login/login')?>'"/>
                <input type='reset' value='Reset'/>
            </p>
            <p><?= $output ?></p>
        </fieldset>
    </form>
</div>