<?php

$pdo->query(
    "INSERT INTO er_users_follow_users (id, user_id, followed_user_id, created_at) VALUES
        (1, 13, 3,  '2016-05-06 09:47:51'),
        (2, 4,  1,  '2016-05-06 09:48:24'),
        (3, 4,  5,  '2015-12-16 13:28:56'),
        (4, 4,  7,  '2016-05-06 09:48:24'),
        (5, 8,  5,  '2015-12-16 13:28:56'),
        (6, 8,  11, '2015-12-16 13:28:56'),
        (7, 3,  7,  '2015-12-16 13:28:56');"
);
