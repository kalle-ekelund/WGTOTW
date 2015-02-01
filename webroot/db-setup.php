<?php

$app->db->dropTableIfExists('user')->execute();

$app->db->createTable(
    'question',
    [
        'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'title' => ['varchar(80)', 'not null'],
        'question' => ['text', 'not null'],
        'rating' => ['integer', 'default 0'],
        'created' => ['datetime', 'not null'],
        'updated' => ['datetime'],
        'user_id' => []
    ]
)->execute();

$app->db->insert(
    'user',
    ['acronym', 'email', 'name', 'password', 'created', 'active']
);

$now = gmdate('Y-m-d H:i:s');

$app->db->execute([
    'admin',
    'admin@dbwebb.se',
    'Administrator',
    password_hash('admin', PASSWORD_DEFAULT),
    $now,
    $now
]);

$app->db->execute([
    'doe',
    'doe@dbwebb.se',
    'John/Jane Doe',
    password_hash('doe', PASSWORD_DEFAULT),
    $now,
    $now
]);

$app->db->execute([
    'kaek14',
    'kaek14@dbwebb.se',
    'Kalle Ekelund',
    password_hash('kaek14', PASSWORD_DEFAULT),
    $now,
    $now
]);