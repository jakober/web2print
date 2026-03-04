<?php

$password = 'aluca123';
$hashedPassword = bcrypt($password);

echo $hashedPassword;
