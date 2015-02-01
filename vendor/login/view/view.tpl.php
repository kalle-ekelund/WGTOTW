<h1>Profil <?= $user->id ?></h1>
<ul>
    <li><b>Akronym: </b> <?= $user->acronym ?></li>
    <li><b>Namn: </b> <?= $user->name ?></li>
    <li><b>Email: </b> <?= $user->email ?></li>
    <li><b>Namn: </b> <?= $user->name ?></li>
    <li><b>Skapad: </b> <?= $user->created ?></li>
    <li><b>Uppdaterad: </b> <?= $user->updated ?></li>
    <li><b>Borttagen: </b> <?= $user->deleted ?></li>
    <li><b>Aktiv: </b> <?= $user->active ?></li>
</ul>
<form method="post">
    <input type=hidden name="redirect" value="<?=$this->url->create('')?>">
    <input type='submit' name='doLogout' value='Logout' onClick="this.form.action = '<?=$this->url->create('login/logout')?>'"/>
</form>